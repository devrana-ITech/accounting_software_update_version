<?php
require_once('../../config.php');
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `dpp` where id = '{$_GET['id']}'");
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
                Account Name<select id="account_id" name="account_id" class="from-control form-control-sm select2" data-width="auto">
                    <option value="" disabled selected></option>
                    <?php 
					$accounts = $conn->query("SELECT * FROM `account_list` where delete_flag = 0 and `status` = 1 order by `name` asc ");
                    while($row = $accounts->fetch_assoc()):
                    ?>
                    <option value="<?= $row['id'] ?>" <?= isset($account_id) && $account_id == $row['id'] ? "selected" : "" ?>><?= $row['acc_code']. '--' .$row['name']  ?></option>
                    <?php endwhile; ?>
                </select>
        </div>
		<div class="form-group">
			Select Report Group Name<select id="group_id" name="group_id" data-width="auto">
				<option value="1" <?= isset($group_id) && $group_id == 1 ? "selected" : "" ?>>Recurrent Expenditure</option>
				<option value="2" <?= isset($group_id) && $group_id == 2 ? "selected" : "" ?>>Capital Expenditure</option>
			</select>
		</div>
		<div class="form-group">
			Select Report Sub-Group Name<select id="sub_group_id" name="sub_group_id" data-width="auto">
				 <option value="1" <?= isset($sub_group_id) && $sub_group_id == 1 ? "selected" : "" ?>>Recurrent Expenditure</option>
				 <option value="2" <?= isset($sub_group_id) && $sub_group_id == 2 ? "selected" : "" ?>>Goods</option>
				 <option value="3" <?= isset($sub_group_id) && $sub_group_id == 3 ? "selected" : "" ?>>Works</option>
			</select>
		</div>
		<div class="form-group">
			<input type="number" name="dpp_amount" id="dpp_amount" class="form-control form-control-border" placeholder="Enter DPP Amount" value ="<?php echo isset($dpp_amount) ? $dpp_amount : '' ?>" >
        </div>
    </form>
</div>
<script>
const groupSubGroupMap = {
  1: [
	{ code: '1', name: 'Recurrent Expenditure' }
  ],
  2: [
	{ code: '2', name: 'Goods' },
	{ code: '3', name: 'Works' }
  ]
};

function updateSubGroup() {
      const group_id = document.getElementById('group_id');
      const sub_group_id = document.getElementById('sub_group_id');
      const selectedGroup = group_id.value;

      // Clear existing options
      sub_group_id.innerHTML = '';

      // Populate city dropdown with options based on the selected country
      groupSubGroupMap[selectedGroup].forEach(subgroup => {
        const option = document.createElement('option');
        option.value = subgroup.code;
        option.textContent = subgroup.name;
        sub_group_id.appendChild(option);
      });
    }

    // Add event listener to the country dropdown to trigger the updateCities function
    document.getElementById('group_id').addEventListener('change', updateSubGroup);

    // Initial population of the city dropdown based on the default selected country
    //updateSubGroup();

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
                url:_base_url_+"classes/Master.php?f=save_dpp",
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