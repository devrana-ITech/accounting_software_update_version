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
            <div class="col-md-3 form-group">
                Package No<input style="background-color: #f0dcf7;" type="text" id="package_no" name="package_no" class="form-control form-control-sm form-control-border rounded-0" value="<?=  isset($meta['package_no'])  ? $meta['package_no'] : "" ?>" required>
			
            </div>

			<div class="col-md-3 form-group">
                Package Description <textarea style="background-color: #f0dcf7;" type="text" id="package_descrip" name="package_descrip" class="form-control form-control-sm form-control-border rounded-0"><?= isset($meta['package_descrip']) ? htmlspecialchars($meta['package_descrip']) : "" ?></textarea>
            </div>
			<div class="col-md-3 form-group">
                Unit<input style="background-color: #f0dcf7;" type="text" id="unit" name="unit" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['unit'])  ? $meta['unit'] : "" ?>" >
				
            </div>
			<div class="col-md-3 form-group">
				Quantity<input style="background-color: #f0dcf7;" type="text" id="quantity" name="quantity" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['quantity'])  ? $meta['quantity'] : "" ?>" >
            </div>
			
			
			<div class="col-md-3 form-group">
                Method Type Plan<input style="background-color: #f0dcf7;" type="text" id="procuement_type" name="procuement_type1" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['procuement_type1'])  ? $meta['procuement_type1'] : "" ?>" >
                
            </div>
			<div class="col-md-3 form-group">
                Method Type Actual<input style="background-color: #f0dcf7;" type="text" id="procuement_type" name="procuement_type2" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['procuement_type2'])  ? $meta['procuement_type2'] : "" ?>" >
               
            </div>
			<div class="col-md-3 form-group">
                Method Type Deviation<input style="background-color: #f0dcf7;" type="text" id="procuement_type" name="procuement_type3" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['procuement_type3'])  ? $meta['procuement_type3'] : "" ?>" >
               
            </div>
			<div class="col-md-3 form-group">
                Tender Approval Plan<input style="background-color: #f0dcf7;" type="text" id="tender_approval" name="tender_approval1" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['tender_approval1'])  ? $meta['tender_approval1'] : "" ?>" >
            </div>
			<div class="col-md-3 form-group">
                Tender Approval Actual<input style="background-color: #f0dcf7;" type="text" id="tender_approval" name="tender_approval2" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['tender_approval2'])  ? $meta['tender_approval2'] : "" ?>" >
               </div>
			<div class="col-md-3 form-group">
                Tender Approval Deviation<input style="background-color: #f0dcf7;" type="text" id="tender_approval" name="tender_approval3" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['tender_approval3'])  ? $meta['tender_approval3'] : "" ?>" >
               
            </div>
			<div class="col-md-3 form-group">
                Source Funds Plan<input style="background-color: #f0dcf7;" type="text" id="source_funds" name="source_funds1" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['source_funds1'])  ? $meta['source_funds1'] : "" ?>" >
               
            </div>
			<div class="col-md-3 form-group">
                Source Funds Actual<input style="background-color: #f0dcf7;" type="text" id="source_funds" name="source_funds2" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['source_funds2'])  ? $meta['source_funds2'] : "" ?>" >
               
            </div>
			<div class="col-md-3 form-group">
                Source Funds Deviation<input style="background-color: #f0dcf7;" type="text" id="source_funds" name="source_funds3" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['source_funds3'])  ? $meta['source_funds3'] : "" ?>" >
               
            </div>
			<div class="col-md-3 form-group">
                Est. Cost Plan<input style="background-color: #f0dcf7;" type="text" id="cost_lac" name="cost_lac1" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['cost_lac1'])  ? $meta['cost_lac1'] : "" ?>" >
               
            </div>
			<div class="col-md-3 form-group">
                Est. Cost Actual<input style="background-color: #f0dcf7;" type="text" id="cost_lac" name="cost_lac2" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['cost_lac2'])  ? $meta['cost_lac2'] : "" ?>" >
               
            </div>
			<div class="col-md-3 form-group">
                Est. Cost Deviation<input style="background-color: #f0dcf7;" type="text" id="cost_lac" name="cost_lac3" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['cost_lac3'])  ? $meta['cost_lac3'] : "" ?>" >
               
            </div>
			<div class="col-md-2 form-group">
                Invitation Prequalific Plan<input style="background-color: #f0dcf7;" type="date" id="cost_lac" name="invitation_prequalific1" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['invitation_prequalific1'])  ? $meta['invitation_prequalific1'] : "" ?>" >
               
            </div>
			<div class="col-md-2 form-group">
                Invitation Prequalific Actual<input style="background-color: #f0dcf7;" type="date" id="cost_lac" name="invitation_prequalific2" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['invitation_prequalific2'])  ? $meta['invitation_prequalific2'] : "" ?>" >
               
            </div>
			<div class="col-md-2 form-group">
               Invitation Prequalific Deviation<input style="background-color: #f0dcf7;" type="date" id="cost_lac" name="invitation_prequalific3" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['invitation_prequalific3'])  ? $meta['invitation_prequalific3'] : "" ?>" >
               
            </div>
			<div class="col-md-2 form-group">
                Invitation Tender Plan<input style="background-color: #f0dcf7;" type="date" id="invitation_tender" name="invitation_tender1" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['invitation_tender1'])  ? $meta['invitation_tender1'] : "" ?>" >
               
            </div>
			<div class="col-md-2 form-group">
                Invitation Tender Actual<input style="background-color: #f0dcf7;" type="date" id="invitation_tender" name="invitation_tender2" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['invitation_tender2'])  ? $meta['invitation_tender2'] : "" ?>" >
               
            </div>
			<div class="col-md-2 form-group">
                Invitation Tender Deviation<input style="background-color: #f0dcf7;" type="date" id="invitation_tender" name="invitation_tender3" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['invitation_tender3'])  ? $meta['invitation_tender3'] : "" ?>" >
               
            </div>
			<div class="col-md-2 form-group">
                Signing Contract Plan<input style="background-color: #f0dcf7;" type="date" id="signing_contract" name="signing_contract1" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['signing_contract1'])  ? $meta['signing_contract1'] : "" ?>" >
              
            </div>
			<div class="col-md-2 form-group">
                Signing Contract Actual<input style="background-color: #f0dcf7;" type="date" id="signing_contract" name="signing_contract2" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['signing_contract2'])  ? $meta['signing_contract2'] : "" ?>" >
              
            </div>
			<div class="col-md-2 form-group">
                Signing Contract Deviation<input style="background-color: #f0dcf7;" type="date" id="signing_contract" name="signing_contract3" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['signing_contract3'])  ? $meta['signing_contract3'] : "" ?>" >
              
            </div>
			<div class="col-md-2 form-group">
                Completion Contract Plan<input style="background-color: #f0dcf7;" type="date" id="conpletion_contract" name="conpletion_contract1" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['conpletion_contract1'])  ? $meta['conpletion_contract1'] : "" ?>" >
               
            </div>
			<div class="col-md-2 form-group">
                Completion Contract Actual<input style="background-color: #f0dcf7;" type="date" id="conpletion_contract" name="conpletion_contract2" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['conpletion_contract2'])  ? $meta['conpletion_contract2'] : "" ?>" >
               
            </div>
			<div class="col-md-2 form-group">
                Completion Contract Deviation<input style="background-color: #f0dcf7;" type="date" id="conpletion_contract" name="conpletion_contract3" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['conpletion_contract3'])  ? $meta['conpletion_contract3'] : "" ?>" >
               
            </div>
			<div class="col-md-2 form-group">
                Conpletion Date<input style="background-color: #f0dcf7;" type="date" id="procurement_status" name="conpletion_date" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['conpletion_date'])  ? $meta['conpletion_date'] : "" ?>" >
               
            </div>
			<div class="col-md-4 form-group">
                Name and Address<input style="background-color: #f0dcf7;" type="text" id="procurement_status" name="name_address" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['name_address'])  ? $meta['name_address'] : "" ?>" >
               
            </div>
			<div class="col-md-4 form-group">
                Firm Focal<input style="background-color: #f0dcf7;" type="text" id="procurement_status" name="firm_focal" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['firm_focal'])  ? $meta['firm_focal'] : "" ?>" >
               
            </div>
			<div class="col-md-2 form-group">
                Paid Date<input style="background-color: #f0dcf7;" type="date" id="procurement_status" name="paid_date" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['paid_date'])  ? $meta['paid_date'] : "" ?>" >
               
            </div>
			<div class="col-md-4 form-group">
                Financial Progress<input style="background-color: #f0dcf7;" type="text" id="procurement_status" name="financial_progress" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['financial_progress'])  ? $meta['financial_progress'] : "" ?>" >
               
            </div>
			<div class="col-md-4 form-group">
                Physical Progress<input style="background-color: #f0dcf7;" type="text" id="procurement_status" name="physical_progress" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['physical_progress'])  ? $meta['physical_progress'] : "" ?>" >
               
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