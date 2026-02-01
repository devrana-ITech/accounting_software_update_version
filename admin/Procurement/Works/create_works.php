<?php include('./../../../config.php'); ?>

<?php 

if(isset($_GET['id'])){
    $sql = $conn->query("SELECT * FROM `works` where id =" .$_GET['id']);
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
    <form action="" id="works-form">
        <input type="hidden" name="id" value="<?= isset($meta['id']) ? $meta['id'] : '' ?>">
        <div class="row">
            <div class="col-md-3 form-group">
                Package No<input style="background-color: #f0dcf7;" type="text" id="package_no" name="package_no" class="form-control form-control-sm form-control-border rounded-0" value="<?=  isset($meta['package_no'])  ? $meta['package_no'] : "" ?>" >
			
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
                Est. Cost Plan<input oninput="calculateData()" style="background-color: #f0dcf7;" type="text" id="cost_plan" name="cost_lac1" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['cost_lac1'])  ? $meta['cost_lac1'] : "" ?>" >
               
            </div>
			<div class="col-md-3 form-group">
                Est. Cost Actual<input oninput="calculateData()" style="background-color: #f0dcf7;" type="text" id="cost_actual" name="cost_lac2" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['cost_lac2'])  ? $meta['cost_lac2'] : "" ?>" >
               
            </div>
			<div class="col-md-3 form-group">
                Est. Cost Deviation<input style="background-color: #f0dcf7;" type="text" id="cost_deviation" name="cost_lac3" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['cost_lac3'])  ? $meta['cost_lac3'] : "" ?>" readonly>
               
            </div>
			<div class="col-md-2 form-group">
                Invitation Prequalific Plan<input oninput="calculateData()" style="background-color: #f0dcf7;" type="date" id="Prequalific_plan" name="invitation_prequalific1" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['invitation_prequalific1'])  ? $meta['invitation_prequalific1'] : "" ?>" >
               
            </div>
			<div class="col-md-2 form-group">
                Invitation Prequalific Actual<input oninput="calculateData()" style="background-color: #f0dcf7;" type="date" id="Prequalific_actual" name="invitation_prequalific2" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['invitation_prequalific2'])  ? $meta['invitation_prequalific2'] : "" ?>" >
               
            </div>
			<div class="col-md-2 form-group">
               Invitation Prequalific Deviation<input style="background-color: #f0dcf7;" type="text" id="Prequalific_deviation" name="invitation_prequalific3" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['invitation_prequalific3'])  ? $meta['invitation_prequalific3'] : "" ?>" readonly>
               
            </div>
			<div class="col-md-2 form-group">
                Invitation Tender Plan<input oninput="calculateData()" style="background-color: #f0dcf7;" type="date" id="tender_plan" name="invitation_tender1" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['invitation_tender1'])  ? $meta['invitation_tender1'] : "" ?>" >
               
            </div>
			<div class="col-md-2 form-group">
                Invitation Tender Actual<input oninput="calculateData()" style="background-color: #f0dcf7;" type="date" id="tender_actual" name="invitation_tender2" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['invitation_tender2'])  ? $meta['invitation_tender2'] : "" ?>" >
               
            </div>
			<div class="col-md-2 form-group">
                Invitation Tender Deviation<input style="background-color: #f0dcf7;" type="text" id="tender_deviation" name="invitation_tender3" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['invitation_tender3'])  ? $meta['invitation_tender3'] : "" ?>" readonly>
               
            </div>
			<div class="col-md-2 form-group">
                Signing Contract Plan<input oninput="calculateData()" style="background-color: #f0dcf7;" type="date" id="signing_plan" name="signing_contract1" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['signing_contract1'])  ? $meta['signing_contract1'] : "" ?>" >
              
            </div>
			<div class="col-md-2 form-group">
                Signing Contract Actual<input oninput="calculateData()" style="background-color: #f0dcf7;" type="date" id="signing_actual" name="signing_contract2" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['signing_contract2'])  ? $meta['signing_contract2'] : "" ?>" >
              
            </div>
			<div class="col-md-2 form-group">
                Signing Contract Deviation<input style="background-color: #f0dcf7;" type="text" id="signing_deviation" name="signing_contract3" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['signing_contract3'])  ? $meta['signing_contract3'] : "" ?>" >
              
            </div>
			<div class="col-md-2 form-group">
                Completion Contract Plan<input oninput="calculateData()" style="background-color: #f0dcf7;" type="date" id="conpletion_plan" name="conpletion_contract1" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['conpletion_contract1'])  ? $meta['conpletion_contract1'] : "" ?>" >
               
            </div>
			<div class="col-md-2 form-group">
                Completion Contract Actual<input oninput="calculateData()" style="background-color: #f0dcf7;" type="date" id="conpletion_actual" name="conpletion_contract2" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['conpletion_contract2'])  ? $meta['conpletion_contract2'] : "" ?>" >
               
            </div>
			<div class="col-md-2 form-group">
                Completion Contract Deviation<input style="background-color: #f0dcf7;" type="text" id="conpletion_deviation" name="conpletion_contract3" class="form-control form-control-sm rounded-0" value="<?=  isset($meta['conpletion_contract3'])  ? $meta['conpletion_contract3'] : "" ?>" readonly>
               
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


function calculateData(){

    // Cost
    let cost_plan = parseFloat(document.getElementById("cost_plan").value) || 0;
    let cost_actual = parseFloat(document.getElementById("cost_actual").value) || 0;

    if(cost_plan && cost_actual){

    let cost_deviation = (cost_plan - cost_actual);

    document.getElementById("cost_deviation").value = cost_deviation.toFixed(3);
    }else{
         document.getElementById("cost_deviation").value = "";
    }

    

    // Prequalific
    let prequalific_plan   = document.getElementById("Prequalific_plan").value;
    let prequalific_actual = document.getElementById("Prequalific_actual").value;

    if (prequalific_plan && prequalific_actual) {

    let prequalific_planDate   = new Date(prequalific_plan);
    let prequalific_actualDate = new Date(prequalific_actual);

    let prequalific_diffDays = (prequalific_planDate - prequalific_actualDate) / (1000 * 60 * 60 * 24);

    document.getElementById("Prequalific_deviation").value = prequalific_diffDays.toFixed(0);

    }else{
        document.getElementById("Prequalific_deviation").value = "";
    }

   



    // Invitation Tender 

    let tender_plan = document.getElementById("tender_plan").value;
    let tender_actual = document.getElementById("tender_actual").value;

    if(tender_plan && tender_actual){

    let tender_planDate = new Date(tender_plan);
    let tender_actualDate = new Date(tender_actual);

    let tender_diffDays = (tender_planDate - tender_actualDate) / (1000 * 60 * 60 * 24);

    document.getElementById("tender_deviation").value = tender_diffDays.toFixed(0);

    }else{
        document.getElementById("tender_deviation").value = "";
    }

    // Singing Tender 

    let signing_plan = document.getElementById("signing_plan").value;
    let signing_actual = document.getElementById("signing_actual").value;

    if(signing_plan && signing_actual){

    let signing_planDate = new Date(signing_plan);
    let signing_actualDate = new Date(signing_actual);

    let tender_diffDays = (signing_planDate - signing_actualDate) / (1000 * 60 * 60 * 24);

    document.getElementById("signing_deviation").value = tender_diffDays.toFixed(0);

    }else{
        document.getElementById("signing_deviation").value = "";
    }

    // conpletion

    let conpletion_plan = document.getElementById("conpletion_plan").value;
    let conpletion_actual = document.getElementById("conpletion_actual").value;

    if(conpletion_plan && conpletion_actual){

    let conpletion_planDate = new Date(conpletion_plan);
    let conpletion_actualDate = new Date(conpletion_actual);

    let tender_diffDays = (conpletion_planDate - conpletion_actualDate) / (1000 * 60 * 60 * 24);

    document.getElementById("conpletion_deviation").value = tender_diffDays.toFixed(0);

    }else{
        document.getElementById("conpletion_deviation").value = "";
    }


}




$(document).ready(function(){
    $('#works-form').submit(function(e){
        e.preventDefault();
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=save_works",
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