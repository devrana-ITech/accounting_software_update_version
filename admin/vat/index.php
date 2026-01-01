<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
#banner-img{
	width:100%;
	height:40vh;
	object-fit:cover;
	object-position:center center;
}

.form-container {
	width: 100%;
	display: flex;
	flex-wrap: wrap; /* Allow items to wrap to the next line if needed */
	gap: 10px; /* Adjust spacing between items as needed */
}

/* Style for the side-by-side input boxes */
.side-by-side {
	flex: 1; /* Grow to take available space */
}

/* Style for the full-width input box */
.full-width {
	flex: 2; /* Grow to take more available space */
	width: 100%; /* Take 100% of available width */
}
select {
      background: #f0dcf7;
	  
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
	height: 540px;
	border: 1px solid blue !important; 
	border-radius: 0px !important;
}


.cl1 {
	width: 12%;
	text-align: left;
}
.cl2 {
	width: 22%;
	text-align: right;
}
.cl3 {
	width: 20%;
	text-align: left;
}

.cl4 {
	width: 20%;
	text-align: right;
}

table, th, td {
  border: 1px solid #d9d0f7;
  border-collapse: collapse;
  width: 100%;
  padding: 5px;
  font-size: 20px;
}
		
</style>
<!-- <h1><?php //echo $_settings->info('name') ?> </h1>


<hr class="border-border bg-primary"> --->
<div class="row">
	<div class="col-md-12">
		<p style="text-align: center; margin-bottom: 5px; font-size:22px;"><b>VAT and Income Tax Statement</b></p>
	</div>
</div>

<hr class="border-border bg-primary">
<div class="row">
	<div class="col-md-12">
		<div class="info-box bg-gradient-light shadow">
			<span style="width: 180px;" class="info-box-icon bg-gradient-success elevation-1"><i class="fas fa-receipt"></i></span>

			<span style="padding-left: 5px;" class="info-box-text">
				 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/vattax_report_pdf.php" enctype='multipart/form-data'>
					<div class="form-container">
						<div class="side-by-side">
							<label for="from_date">From:</label>
							<input style="background-color: #f0dcf7;" type="date" id="from_date" name="from_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						<div class="side-by-side">
							<label for="to_date">To:</label>
							<input style="background-color: #f0dcf7;" type="date" id="to_date" name="to_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						
						<div class="full-width">
							<select name="account_id" id="mySelect" style="width: auto;">
								<option value="">Select Consulting Firm</option>
								<?php 
								$accounts = $conn->query("SELECT * FROM `payee` order by `payee_name` asc ");
								while($row = $accounts->fetch_assoc()):
								?>
								<option value="<?= $row['id'] ?>"><?= $row['payee_name'] ?></option>
								<?php endwhile; ?>
							</select>
						</div>
						
						<div class="full-width">
							<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-success btn-flat btn-sm"><i class="fa fa-file-pdf"></i>  Click here to generate the Statement (VAT & IT only)</button>
						</div>
					</div>
				</form>
			 </span>
			<!-- /.info-box-content -->
		</div>
	</div>	
</div>

<hr class="border-border bg-primary">
<div class="row">
	<div class="col-md-12">
		<div class="info-box bg-gradient-light shadow">
			<span style="width: 180px;" class="info-box-icon bg-gradient-success elevation-1"><i class="fas fa-receipt"></i></span>

			<span style="padding-left: 5px;" class="info-box-text">
				 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/vattaxdetailed_report_pdf.php" enctype='multipart/form-data'>
					<div class="form-container">
						<div class="side-by-side">
							<label for="from_date">From:</label>
							<input style="background-color: #f0dcf7;" type="date" id="from_date" name="from_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						<div class="side-by-side">
							<label for="to_date">To:</label>
							<input style="background-color: #f0dcf7;" type="date" id="to_date" name="to_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						
						<div class="full-width">
							<select name="account_id" id="mySelect1" style="width: auto;">
								<option value="">Select Consulting Firm</option>
								<?php 
								$accounts = $conn->query("SELECT * FROM `payee` order by `payee_name` asc ");
								while($row = $accounts->fetch_assoc()):
								?>
								<option value="<?= $row['id'] ?>"><?= $row['payee_name'] ?></option>
								<?php endwhile; ?>
							</select>
						</div>
						
						<div class="full-width">
							<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-success btn-flat btn-sm"><i class="fa fa-file-pdf"></i>  Click here to generate the Statement (Detailed payment)</button>
						</div>
					</div>
				</form>
			 </span>
			<!-- /.info-box-content -->
		</div>
	</div>	
</div>

<hr class="border-border bg-primary">
<script>
  $(document).ready(function() {
    $('#mySelect').select2({
      placeholder: "Select the Contractor/Firm/Consultant or type in the text box!!!",
      allowClear: true
    });
	
	$('#mySelect1').select2({
      placeholder: "Select the Contractor/Firm/Consultant or type in the text box!!!",
      allowClear: true
    });
	
	
  });
</script>
