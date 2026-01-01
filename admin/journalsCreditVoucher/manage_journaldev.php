<?php 
require_once('./../../config.php');
$account_arr = [];
$group_arr = [];
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `journal_entries` where id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
?>
<?php
function format_num($number){
	$decimals = 0;
	$num_ex = explode('.',$number);
	$decimals = isset($num_ex[1]) ? strlen($num_ex[1]) : 0 ;
	return number_format($number,$decimals);
}
?>
<style>
	select {
      background: #f0dcf7;
	  width: 100%;
}
.table th, .table td {
	padding: 5px;
}

tr
{
  line-height: 20px;
}
.select2-results {  background: #f0dcf7; }
.select2-search input { background: #f0dcf7; }
.select2-selection__rendered { background: #f0dcf7; }
.select2-search { background: #f0dcf7; }
.select2-results__option--highlighted { background: #f0dcf7; }
.select2-results__option[aria-selected=true] { background: #f0dcf7; }


.select2-container .select2-selection--single{
	padding: 0px;
	margin-top: 4px;
	height:34px !important;
	width: 100% !important;
}

.select2-container--default .select2-selection--single{
	padding: 0px;
	border: 1px solid blue !important; 
	border-radius: 0px !important;
}
</style>
<div class="container-fluid">
    <form action="" id="journal-form">
        <input type="hidden" name="id" value="<?= isset($id) ? $id :'' ?>">
        <div class="row">
            <div class="col-md-2 form-group">
                Entry Date<input style="background-color: #f0dcf7;" type="date" id="journal_date" name="journal_date" class="form-control form-control-sm form-control-border rounded-0" value="<?= isset($journal_date) ? $journal_date : date("Y-m-d") ?>" required>
            </div>

			<div class="col-md-6 form-group">
                Voucher Number<input style="background-color: #f0dcf7;" type="text" id="voucher_number" name="voucher_number" class="form-control form-control-sm form-control-border rounded-0" value="<?= isset($voucher_number) ? $voucher_number : "" ?>" required>
            </div>
			
			<div class="col-md-2 form-group">
                DLI Type<br><select id="dli_type" name="dli_type" class="from-control form-control-sm" required>
                    <option value="Non-DLI">Non-DLI</option>
                    <option value="DLI">DLI</option>
                </select>
            </div>
			<div class="col-md-2 form-group">
                Component<br><select id="component_number" name="component_number" class="from-control form-control-sm" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
					<option value="3">3</option>
                </select>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-12 form-group">
                Narration of Transaction<input style="background-color: #f0dcf7;" type="text" id="description" name="description" class="form-control form-control-sm rounded-0" value="<?= isset($description) ? $description : "" ?>" required>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-5">
                Account (Economic Code)<select id="account_id" class="from-control form-control-sm select2" data-width="auto">
                    <option value="" disabled selected></option>
                    <?php 
                    $accounts = $conn->query("SELECT * FROM `account_list` where delete_flag = 0 and `status` = 1 and id <> 51 and id <> 52 order by `acc_code` asc ");
                    while($row = $accounts->fetch_assoc()):
                        unset($row['description']);
                        $account_arr[$row['id']] = $row;
                    ?>
                    <option value="<?= $row['id'] ?>"><?= $row['acc_code']. '--' .$row['name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group col-md-2">
                Source of Fund<select id="gr_id" class="from-control form-control-sm form-control-border select2">
                    <option value="" disabled selected></option>
                    <?php 
                    $groups = $conn->query("SELECT * FROM `account_list` where id >= 51 and id <= 52 order by `name` asc ");
                    while($row = $groups->fetch_assoc()):
                        unset($row['description']);
                        $group_arr[$row['id']] = $row;
                    ?>
                    <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
			<div class="col-md-2 form-group">
                Debit<input style="background-color: #f0dcf7; font-size: 18px; width: 100%;" type="number" step="any" id="debit" class="form-control form-control-sm rounded-0" value="" onkeyup="sync()">
				<input type="hidden" id="group_iddebit" value="1">
            </div>
			<div class="col-md-2 form-group">
                Credit<input style="background-color: #f0dcf7; font-size: 18px; width: 100%;" type="number" step="any" id="credit" class="form-control form-control-sm rounded-0" value="">
				<input type="hidden" id="group_idcredit" value="4">
            </div>
			<div class="form-group col-md-1">
                <button style="width: 100%; margin-top: 23px;" class="btn btn-default bg-gradient-navy btn-flat btn-sm" id="add_to_list" type="button"><i class="fa fa-plus"></i> Add</button>
            </div>
        </div>
		
        <table id="account_list" class="table table-stripped table-bordered">
            <colgroup>
                <col width="5%">
                <col width="55%">
                <col width="20%">
                <col width="20%">
            </colgroup>
            <thead>
                <tr style="background-color: #f7e6b5;">
                    <th class="text-center">Action</th>
                    <th class="text-left">Account</th>
                    <th class="text-right">Debit</th>
                    <th class="text-right">Credit</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(isset($id)):
                $jitems = $conn->query("SELECT j.*,a.name as account, g.name as `group`, g.type FROM `journal_items` j inner join account_list a on j.account_id = a.id inner join group_list g on j.group_id = g.id where journal_id = '{$id}' order by g.type asc");
                while($row = $jitems->fetch_assoc()):
                ?>
                <tr>
                    <td class="text-center">
                        <button style="font-size: 10px;" class="btn btn-sm btn-outline btn-danger btn-flat delete-row" type="button"><i class="fa fa-times"></i></button>
                    </td>
                    <td class="">
                        <input type="hidden" name="account_id[]" value="<?= $row['account_id'] ?>">
                        <input type="hidden" name="group_id[]" value="<?= $row['group_id'] ?>">
                        <input type="hidden" name="amount[]" value="<?= $row['amount'] ?>">
                        <span class="account"><?= $row['account'] ?></span>
                    </td>
                    <td class="debit_amount text-right"><?= $row['type'] == 1 ? format_num($row['amount']) : '' ?></td>
                    <td class="credit_amount text-right"><?= $row['type'] == 2 ? format_num($row['amount']) : '' ?></td>
                </tr>
                <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr class="bg-gradient-secondary">
                    <tr>
                        <th colspan="2" class="text-center">Total</th>
                        <th class="text-right total_debit">0.00</th>
                        <th class="text-right total_credit">0.00</th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-center"></th>
                        <th colspan="2" class="text-center total-balance">0</th>
                    </tr>
                </tr>
            </tfoot>
        </table>
    </form>
</div>
<noscript id="item-clone">
<tr>
    <td class="text-center">
        <button style="font-size: 10px;" class="btn btn-sm btn-outline btn-danger btn-flat delete-row" type="button"><i class="fa fa-times"></i></button>
    </td>
    <td class="">
        <input type="hidden" name="account_id[]" value="">
        <input type="hidden" name="group_id[]" value="">
        <input type="hidden" name="amount[]" value="">
        <span class="account"></span>
		<span class="group"></span>
    </td>
    <td class="debit_amount text-right"></td>
    <td class="credit_amount text-right"></td>
</tr>
</noscript>
<script>
	function sync()
		{
		  var n1 = document.getElementById('debit');
		  var n2 = document.getElementById('credit');
		  n2.value = n1.value;
		}
	/*
	const tableEl = document.getElementById("account_list");
	function onDeleteRow(e) {
        if (!e.target.classList.contains("delete-row")) {
			const btn = e.target;
			btn.closest("tr").remove();
			cal_tb();
        }
      }
      tableEl.addEventListener("click", onDeleteRow);
*/
	  
    var account = $.parseJSON('<?= json_encode($account_arr) ?>')
    var group = $.parseJSON('<?= json_encode($group_arr) ?>')

    function cal_tb(){
        var debit = 0;
        var credit = 0;
        $('#account_list tbody tr').each(function(){
            if($(this).find('.debit_amount').text() != "")
                debit += parseFloat(($(this).find('.debit_amount').text()).replace(/,/gi,''));
            if($(this).find('.credit_amount').text() != "")
                credit += parseFloat(($(this).find('.credit_amount').text()).replace(/,/gi,''));
        })
        $('#account_list').find('.total_debit').text(parseFloat(debit).toLocaleString('en-US',{style:'decimal'}))
        $('#account_list').find('.total_credit').text(parseFloat(credit).toLocaleString('en-US',{style:'decimal'}))
        $('#account_list').find('.total-balance').text(parseFloat(debit - credit).toLocaleString('en-US',{style:'decimal'}))
    }
    $(function(){
        if('<?= isset($id) ?>' == 1){
            cal_tb()
        }
        $('#account_list th,#account_list td').addClass('align-middle px-2 py-1')
        $('#uni_modal').on('shown.bs.modal',function(){
            $('.select2').select2({
                placeholder:"Please select here",
                width:"100%",
                dropdownParent:$('#uni_modal')
            })
        })
        $('#uni_modal').trigger('shown.bs.modal')
		
		
        $('#add_to_list').click(function(){
            var account_id = $('#account_id').val()
            var group_iddebit = $('#group_iddebit').val() //=1
			var group_idcredit = $('#group_idcredit').val() //=4
			var gr_id = $('#gr_id').val()
            var debit = $('#debit').val()
			var credit = $('#credit').val()
						
            var account_data = !!account[account_id] ? account[account_id] : {};
            var group_data = !!group[gr_id] ? group[gr_id] : {};
			
			if((debit > 0) && (credit > 0)) {
				if (gr_id == null){
					alert("Please select source of fund!");
					return false;
				}
				if (account_id == null){
					alert("Please select account!");
					return false;
				}
				var amount = $('#debit').val()
				var tr = $($('noscript#item-clone').html()).clone()
				tr.find('input[name="account_id[]"]').val(gr_id)
				tr.find('input[name="group_id[]"]').val(group_iddebit)
				tr.find('input[name="amount[]"]').val(amount)
				
				tr.find('.group').text(!!group_data.name ? group_data.name : "N/A")
                tr.find('.debit_amount').text(parseFloat(amount).toLocaleString('en-US',{style:'decimal'}))
				
				var amount = $('#credit').val()
				var tr1 = $($('noscript#item-clone').html()).clone()
				tr1.find('input[name="account_id[]"]').val(account_id)
				tr1.find('input[name="group_id[]"]').val(group_idcredit)
				tr1.find('input[name="amount[]"]').val(amount)
				
				tr1.find('.account').text(!!account_data.name ? account_data.name : "N/A")
                tr1.find('.credit_amount').text(parseFloat(amount).toLocaleString('en-US',{style:'decimal'}))
				 $('#account_list').append(tr)
				 $('#account_list').append(tr1)
				
			}
			
			if((debit > 0) && (credit == "")) {
				if (gr_id == null){
					alert("Please select source of fund!");
					return false;
				}
				var amount = $('#debit').val()
				var tr = $($('noscript#item-clone').html()).clone()
				tr.find('input[name="account_id[]"]').val(gr_id)
				tr.find('input[name="group_id[]"]').val(group_iddebit)
				tr.find('input[name="amount[]"]').val(amount)
				
				tr.find('.group').text(!!group_data.name ? group_data.name : "N/A")
                tr.find('.debit_amount').text(parseFloat(amount).toLocaleString('en-US',{style:'decimal'}))
				
				$('#account_list').append(tr)
				
			}
			if((debit == "") && (credit > 0)) {
				if (account_id == null){
					alert("Please select account!");
					return false;
				}
				var amount = $('#credit').val()
				var tr1 = $($('noscript#item-clone').html()).clone()
				tr1.find('input[name="account_id[]"]').val(account_id)
				tr1.find('input[name="group_id[]"]').val(group_idcredit)
				tr1.find('input[name="amount[]"]').val(amount)
				
				tr1.find('.account').text(!!account_data.name ? account_data.name : "N/A")
                tr1.find('.credit_amount').text(parseFloat(amount).toLocaleString('en-US',{style:'decimal'}))
				$('#account_list').append(tr1)
			}
			
			
			
			
			tr.find('.delete-row').click(function(){
                    $(this).closest('tr').remove()
                    cal_tb()
                })
            cal_tb()
            $('#account_id').val('').trigger('change')
            $('#gr_id').val('').trigger('change')
			$('#debit').val('').trigger('change')
			$('#credit').val('').trigger('change')
        })
		
		
		
		
        $('#uni_modal #journal-form').submit(function(e){
            e.preventDefault();
            var _this = $(this)
            $('.pop-msg').remove()
            var el = $('<div>')
                el.addClass("pop-msg alert")
                el.hide()
            if($('#account_list tbody tr').length <=0){
                el.addClass('alert-danger').text(" Account Table is empty.")
                _this.prepend(el)
                el.show('slow')
                $('#uni_modal').scrollTop(0)
                return false;
            }
            if($('#account_list tfoot .total-balance').text() != '0'){
                el.addClass('alert-danger').text(" Trial Balance is not equal.")
                _this.prepend(el)
                el.show('slow')
                $('#uni_modal').scrollTop(0)
                return false;
            }
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_journal",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
                success:function(resp){
                    if(resp.status == 'success'){
                        location.reload();
                    }else if(!!resp.msg){
                        el.addClass("alert-danger")
                        el.text(resp.msg)
                        _this.prepend(el)
                    }else{
                        el.addClass("alert-danger")
                        el.text("An error occurred due to unknown reason.")
                        _this.prepend(el)
                    }
                    el.show('slow')
                    $('html,body,.modal').animate({scrollTop:0},'fast')
                    end_loader();
                }
            })
        })
    })
</script>