<?php
require_once('../../config.php');
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `pr_aid` where id = '{$_GET['id']}'");
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
			<input type="number" name="gov_amount" id="gov_amount" class="form-control form-control-border" placeholder="Enter GoB Amount" value ="<?php echo isset($gov_amount) ? $gov_amount : '' ?>" >
        </div>
        <div class="form-group">
			<input type="number" name="aid_amount" id="aid_amount" class="form-control form-control-border" placeholder="Enter Aid Amount" value ="<?php echo isset($aid_amount) ? $aid_amount : '' ?>" >
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
                url:_base_url_+"classes/Master.php?f=save_aid",
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