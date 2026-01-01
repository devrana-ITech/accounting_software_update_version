<?php
require_once('../../config.php');
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `payee` where id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
?>

<style>
	select {
      background: #f0dcf7;
	  width: 100%;
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
    <form action="" id="aid-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="form-group">
                <input type="text" name="payee_name" id="payee_name" class="form-control form-control-border" placeholder="Enter Payee Name" value ="<?php echo isset($payee_name) ? $payee_name : '' ?>" >
        </div>
		<div class="form-group">
            <label for="address" class="control-label">Address</label>
            <textarea rows="3" name="payee_address" id="payee_address" class="form-control form-control-sm rounded-0" required><?php echo isset($payee_address) ? $payee_address : '' ?></textarea>
        </div>
		<div class="form-group">
                <input type="text" name="payee_phone" id="payee_phone" class="form-control form-control-border" placeholder="Enter Phone Number" value ="<?php echo isset($payee_phone) ? $payee_phone : '' ?>" >
        </div>
		<div class="form-group">
                <input type="text" name="payee_email" id="payee_email" class="form-control form-control-border" placeholder="Enter Email" value ="<?php echo isset($payee_email) ? $payee_email : '' ?>" >
        </div>
		<div class="form-group">
			<input type="float" name="contract_value" id="contract_value" class="form-control form-control-border" placeholder="Enter Contract Value BDT" value ="<?php echo isset($contract_value) ? $contract_value : '' ?>" >
        </div>
		<div class="form-group">
			<input type="float" name="contract_value_us" id="contract_value_us" class="form-control form-control-border" placeholder="Enter Contract Value USD" value ="<?php echo isset($contract_value_us) ? $contract_value_us : '' ?>" >
        </div>
		<div class="form-group">
                Select Package<select id="pack_number" name="pack_number" class="from-control form-control-sm select2" data-width="auto">
                    <option value="" disabled selected></option>
                    <?php 
					$accounts = $conn->query("SELECT * FROM `pkg` order by `pack_name` asc");
                    while($row = $accounts->fetch_assoc()):
                    ?>
                    <option value="<?= $row['id'] ?>" <?= isset($pack_number) && $pack_number == $row['id'] ? "selected" : "" ?>><?= $row['pack_name'] ?></option>
                    <?php endwhile; ?>
                </select>
        </div>
		<div class="form-group">
			Contract Date <input type="date" name="contract_date" id="contract_date" class="form-control form-control-border" placeholder="Enter Contract Date" value ="<?php echo isset($contract_date) ? $contract_date : date("Y-m-d") ?>" >
        </div>
		<div class="form-group">
            <input type="text" name="selection_method" id="selection_method" class="form-control form-control-border" placeholder="Selection Method" value ="<?php echo isset($selection_method) ? $selection_method : '' ?>" >
        </div>
		<div class="form-group">
            <input type="text" name="contract_currency" id="contract_currency" class="form-control form-control-border" placeholder="Contract Currency" value ="<?php echo isset($contract_currency) ? $contract_currency : '' ?>" >
        </div>
		<div class="form-group">
			<input type="float" name="percent_bank" id="percent_bank" class="form-control form-control-border" placeholder="% Financed by Bank" value ="<?php echo isset($percent_bank) ? $percent_bank : '' ?>" >
        </div>
		<div class="form-group">
                Consultant (Prior/Post Review)<select id="iufr_flag" name="iufr_flag" class="from-control form-control-sm select2" data-width="auto">
                    <option value="" disabled selected></option>
                    <option value="1" <?= isset($iufr_flag) && $iufr_flag == 1 ? "selected" : "" ?>>Prior Review</option>
					<option value="2" <?= isset($iufr_flag) && $iufr_flag == 2 ? "selected" : "" ?>>Post Review</option>
                </select>
        </div>
		
    </form>
</div>
<script>

    $(function(){
		
		$('#uni_modal').on('shown.bs.modal',function(){
            $('.select2').select2({
                placeholder:"Please select here",
                width:"100%",
                dropdownParent:$('#uni_modal')
            })
        })
        $('#uni_modal').trigger('shown.bs.modal')
		
		$('.btn-secondary').click(function(){
			location.reload();
		})
		
        $('#uni_modal #aid-form').submit(function(e){
            e.preventDefault();
            var _this = $(this)
            $('.pop-msg').remove()
            var el = $('<div>')
                el.addClass("pop-msg alert")
                el.hide()
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_payee",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured....",'error');
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