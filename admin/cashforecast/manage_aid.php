<?php
require_once('../../config.php');
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `cashforecast` where id = '{$_GET['id']}'");
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
                Select Category<select id="category" name="category" class="from-control form-control-sm select2" data-width="auto">
                    <option value="" disabled selected></option>
                    <?php 
					$accounts = $conn->query("SELECT * FROM `tbl_category` order by `id` asc");
                    while($row = $accounts->fetch_assoc()):
                    ?>
                    <option value="<?= $row['id'] ?>" <?= isset($category) && $category == $row['id'] ? "selected" : "" ?>><?= $row['categoryname'] ?></option>
                    <?php endwhile; ?>
                </select>
        </div>
		<div class="form-group">
                Select Year<select id="year" name="year" class="from-control form-control-sm select2" data-width="auto">
                    <option value="" disabled selected></option>
                    <?php 
					$accounts = $conn->query("SELECT * FROM `fy` order by `id` desc");
                    while($row = $accounts->fetch_assoc()):
                    ?>
                    <option value="<?= $row['id'] ?>" <?= isset($year) && $year == $row['id'] ? "selected" : "" ?>><?= $row['fy'] ?></option>
                    <?php endwhile; ?>
                </select>
        </div>
		<div class="form-group">
			Select Qtr<select id="qtr" name="qtr" data-width="auto">
				<option value="1" <?= isset($qtr) && $qtr == 1 ? "selected" : "" ?>>Jul-Sep</option>
				<option value="2" <?= isset($qtr) && $qtr == 2 ? "selected" : "" ?>>Oct-Dec</option>
				<option value="3" <?= isset($qtr) && $qtr == 3 ? "selected" : "" ?>>Jan-Mar</option>
				<option value="4" <?= isset($qtr) && $qtr == 4 ? "selected" : "" ?>>Apr-Jun</option>
			</select>
		</div>
		<div class="form-group">
			Select Fund Type<select id="fund_type" name="fund_type" data-width="auto">
				 <option value="Non-DLI" <?= isset($fund_type) && $fund_type == 'Non-DLI' ? "selected" : "" ?>>Non-DLI</option>
				 <option value="DLI" <?= isset($fund_type) && $fund_type == 'DLI' ? "selected" : "" ?>>DLI</option>
			</select>
		</div>
		<div class="form-group">
			<input type="float" name="fund_amt" id="fund_amt" class="form-control form-control-border" placeholder="Enter Forecasted Cash" value ="<?php echo isset($fund_amt) ? $fund_amt : '' ?>" >
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
                url:_base_url_+"classes/Master.php?f=save_cashforecast",
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