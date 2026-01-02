<?php include('./../../../config.php'); ?>

<?php 

if(isset($_GET['id'])){
    $sql = $conn->query("SELECT * FROM `goods` where id =" .$_GET['id']);
    foreach($sql->fetch_array() as $k => $v){
        $meta[$k] = $v;
    }
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
    <form action="" id="goods-form">
        <input type="hidden" name="id" value="<?= isset($meta['id']) ? $meta['id'] : '' ?>">
        <div class="row">
            <div class="col-md-4 form-group">
                Package No<input style="background-color: #f0dcf7;" type="text" id="package_no" name="package_no" class="form-control form-control-sm form-control-border rounded-0" value="<?=  isset($meta['package_no'])  ? $meta['package_no'] : "" ?>" required>
			
            </div>

			<div class="col-md-4 form-group">
                Package Description <textarea style="background-color: #f0dcf7;" type="text" id="package_descrip" name="package_descrip" class="form-control form-control-sm form-control-border rounded-0"><?= isset($meta['package_descrip']) ? htmlspecialchars($meta['package_descrip']) : "" ?></textarea>
            </div>
			<div class="col-md-2 form-group">
                Unit<input style="background-color: #f0dcf7;" type="text" id="unit" name="unit" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['unit'])  ? $meta['unit'] : "" ?>" required>
				
            </div>
			<div class="col-md-2 form-group">
				Quantity<input style="background-color: #f0dcf7;" type="text" id="quantity" name="quantity" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['quantity'])  ? $meta['quantity'] : "" ?>" required>
            </div>
			
			
			<div class="col-md-3 form-group">
                Method Type<input style="background-color: #f0dcf7;" type="text" id="procuement_type" name="procuement_type" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['procuement_type'])  ? $meta['procuement_type'] : "" ?>" required>
                </select>
            </div>
			<div class="col-md-3 form-group">
                Tender Approval<input style="background-color: #f0dcf7;" type="text" id="tender_approval" name="tender_approval" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['tender_approval'])  ? $meta['tender_approval'] : "" ?>" required>
                </select>
            </div>
			<div class="col-md-3 form-group">
                Source Funds<input style="background-color: #f0dcf7;" type="text" id="source_funds" name="source_funds" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['source_funds'])  ? $meta['source_funds'] : "" ?>" required>
                </select>
            </div>
			<div class="col-md-3 form-group">
                Est. Cost (Lac Taka)<input style="background-color: #f0dcf7;" type="text" id="cost_lac" name="cost_lac" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['cost_lac'])  ? $meta['cost_lac'] : "" ?>" required>
                </select>
            </div>
			<div class="col-md-2 form-group">
                Invitation Tender<input style="background-color: #f0dcf7;" type="date" id="invitation_tender" name="invitation_tender" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['invitation_tender'])  ? $meta['invitation_tender'] : "" ?>" required>
                </select>
            </div>
			<div class="col-md-2 form-group">
                Signing Contract<input style="background-color: #f0dcf7;" type="date" id="signing_contract" name="signing_contract" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['signing_contract'])  ? $meta['signing_contract'] : "" ?>" required>
                </select>
            </div>
			<div class="col-md-2 form-group">
                Completion Contract<input style="background-color: #f0dcf7;" type="date" id="conpletion_contract" name="conpletion_contract" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['conpletion_contract'])  ? $meta['conpletion_contract'] : "" ?>" required>
                </select>
            </div>
			<div class="col-md-4 form-group">
                Procurement Status<input style="background-color: #f0dcf7;" type="text" id="procurement_status" name="procurement_status" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['procurement_status'])  ? $meta['procurement_status'] : "" ?>" required>
                </select>
            </div>
</form>
			
<script>
$(document).ready(function(){
    $('#goods-form').submit(function(e){
        e.preventDefault();
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=save_goods",
            data: new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            success: function(res){
                if(res == 1){
                    location.reload();
                }else{
                    alert("Failed to save");
                }
                end_loader();
            }
        })
    })
})

</script>