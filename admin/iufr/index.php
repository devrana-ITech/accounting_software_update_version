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



<hr class="border-border bg-primary"> -->
<div class="row">
	<div class="col-md-12">
		<p style="text-align: center; margin-bottom: 5px; font-size:22px;"><b>Interim Unaudited Financial Reports (IUFRs)</b></p>
	</div>
</div>
<hr class="border-border bg-primary">
<div class="row">
	<div class="col-12 col-sm-12 col-md-6 col-lg-4">
			<div class="info-box bg-gradient-light shadow">
				 <span style="width: 100px;" class="info-box-icon bg-gradient-info elevation-1"><i class="fa fa-balance-scale"></i></span>
				 <span style="padding-left: 5px;" class="info-box-text">
					 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/a1_pdf.php" enctype='multipart/form-data'>
						<div class="form-container">
							<div class="side-by-side">
							  <select id="year_id" name="year_id" class="from-control" data-width="auto">
									<?php 
									$year = $conn->query("SELECT * FROM `fy` order by `id` desc ");
									while($row = $year->fetch_assoc()):
									?>
									<option value="<?= $row['id'] ?>"><?= $row['fy'] ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="side-by-side">
							  <select id="qtr_id" name="qtr_id" class="from-control" data-width="auto">
									<option value="1">JUL-SEP</option>
									<option value="2">OCT-DEC</option>
									<option value="3">JAN-MAR</option>
									<option value="4">APR-JUN</option>
								</select>
							</div>
							<div class="side-by-side">
							     <select id="d_type" name="d_type" class="from-control" data-width="auto">
									<option value="Non-DLI">Non-DLI</option>
									<option value="DLI">DLI</option>
								</select>
							</div>
							
							<div class="full-width">
								<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-info btn-flat btn-sm"><i class="fa fa-file-pdf"></i> 1A. Project Sources & Use of Funds</button>
							</div>
						</div>
					</form>
				 </span>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
	</div>
	<div class="col-12 col-sm-12 col-md-6 col-lg-4">
			<div class="info-box bg-gradient-light shadow">
				 <span style="width: 100px;" class="info-box-icon bg-gradient-info elevation-1"><i class="fa fa-balance-scale"></i></span>
				 <span style="padding-left: 5px;" class="info-box-text">
					 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/b1_pdf.php" enctype='multipart/form-data'>
						<div class="form-container">
							<div class="side-by-side">
							  <select id="year_id" name="year_id" class="from-control" data-width="auto">
									<?php 
									$year = $conn->query("SELECT * FROM `fy` order by `id` desc ");
									while($row = $year->fetch_assoc()):
									?>
									<option value="<?= $row['id'] ?>"><?= $row['fy'] ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="side-by-side">
							  <select id="qtr_id" name="qtr_id" class="from-control" data-width="auto">
									<option value="1">JUL-SEP</option>
									<option value="2">OCT-DEC</option>
									<option value="3">JAN-MAR</option>
									<option value="4">APR-JUN</option>
								</select>
							</div>
							<div class="side-by-side">
							     <select id="d_type" name="d_type" class="from-control" data-width="auto">
									<option value="Non-DLI">Non-DLI</option>
									<option value="DLI">DLI</option>
								</select>
							</div>
							
							<div class="full-width">
								<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-info btn-flat btn-sm"><i class="fa fa-file-pdf"></i> 1B. Uses of Funds by Project Activity</button>
							</div>
						</div>
					</form>
				 </span>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
	</div>
	<div class="col-12 col-sm-12 col-md-6 col-lg-4">
			<div class="info-box bg-gradient-light shadow">
				 <span style="width: 100px;" class="info-box-icon bg-gradient-info elevation-1"><i class="fa fa-balance-scale"></i></span>
				 <span style="padding-left: 5px;" class="info-box-text">
					 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/c1_pdf.php" enctype='multipart/form-data'>
						<div class="form-container">
							<div class="side-by-side">
							    <select id="year_id" name="year_id" class="from-control" data-width="auto">
									<?php 
									$year = $conn->query("SELECT * FROM `fy` order by `id` desc ");
									while($row = $year->fetch_assoc()):
									?>
									<option value="<?= $row['id'] ?>"><?= $row['fy'] ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="side-by-side">
							    <select id="qtr_id" name="qtr_id" class="from-control" data-width="auto">
									<option value="1">JUL-SEP</option>
									<option value="2">OCT-DEC</option>
									<option value="3">JAN-MAR</option>
									<option value="4">APR-JUN</option>
								</select>
							</div>
							<div class="side-by-side">
							     <select id="d_type" name="d_type" class="from-control" data-width="auto">
									<option value="Non-DLI">Non-DLI</option>
									<option value="DLI">DLI</option>
								</select>
							</div>
							
							<div class="full-width">
								<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-info btn-flat btn-sm"><i class="fa fa-file-pdf"></i> 1C. Project Cash Withdrawals</button>
							</div>
						</div>
					</form>
				 </span>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
	</div>
	
</div>


<hr class="border-border bg-primary">
<div class="row">
	
	
	<div class="col-12 col-sm-12 col-md-6 col-lg-4">
			<div class="info-box bg-gradient-light shadow">
				 <span style="width: 100px;" class="info-box-icon bg-gradient-info elevation-1"><i class="fa fa-balance-scale"></i></span>
				 <span style="padding-left: 5px;" class="info-box-text">
					 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/d1_pdf.php" enctype='multipart/form-data'>
						<div class="form-container">
							<div class="side-by-side">
							     <select id="year_id" name="year_id" class="from-control" data-width="auto">
									<?php 
									$year = $conn->query("SELECT * FROM `fy` order by `id` desc ");
									while($row = $year->fetch_assoc()):
									?>
									<option value="<?= $row['id'] ?>"><?= $row['fy'] ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="side-by-side">
							    <select id="qtr_id" name="qtr_id" class="from-control" data-width="auto">
									<option value="1">JUL-SEP</option>
									<option value="2">OCT-DEC</option>
									<option value="3">JAN-MAR</option>
									<option value="4">APR-JUN</option>
								</select>
							</div>
							<div class="side-by-side">
							     <select id="d_type" name="d_type" class="from-control" data-width="auto">
									<option value="Non-DLI">Non-DLI</option>
									<option value="DLI">DLI</option>
								</select>
							</div>
				
							<div class="full-width">
								<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-info btn-flat btn-sm"><i class="fa fa-file-pdf"></i> 1D. Designated Account Activity</button>
							</div>
						</div>
					</form>
				 </span>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
	</div>
	<div class="col-12 col-sm-12 col-md-6 col-lg-4">
			<div class="info-box bg-gradient-light shadow">
				 <span style="width: 100px;" class="info-box-icon bg-gradient-info elevation-1"><i class="fa fa-balance-scale"></i></span>
				 <span style="padding-left: 5px;" class="info-box-text">
					 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/e1_pdf.php" enctype='multipart/form-data'>
						<div class="form-container">
							<div class="side-by-side">
							    <select id="year_id" name="year_id" class="from-control" data-width="auto">
									<?php 
									$year = $conn->query("SELECT * FROM `fy` order by `id` desc ");
									while($row = $year->fetch_assoc()):
									?>
									<option value="<?= $row['id'] ?>"><?= $row['fy'] ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="side-by-side">
							    <select id="qtr_id" name="qtr_id" class="from-control" data-width="auto">
									<option value="1">JUL-SEP</option>
									<option value="2">OCT-DEC</option>
									<option value="3">JAN-MAR</option>
									<option value="4">APR-JUN</option>
								</select>
							</div>
							<div class="side-by-side">
							     <select id="d_type" name="d_type" class="from-control" data-width="auto">
									<option value="Non-DLI">Non-DLI</option>
									<option value="DLI">DLI</option>
								</select>
							</div>
							
							
							<div class="full-width">
								<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-info btn-flat btn-sm"><i class="fa fa-file-pdf"></i> 1E. Projected Cash Forecast Statements</button>
							</div>
						</div>
					</form>
				 </span>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
	</div>
	<div class="col-12 col-sm-12 col-md-6 col-lg-4">
			<div class="info-box bg-gradient-light shadow">
				 <span style="width: 100px;" class="info-box-icon bg-gradient-info elevation-1"><i class="fa fa-balance-scale"></i></span>
				 <span style="padding-left: 5px;" class="info-box-text">
					 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/c1_works_pdf.php" enctype='multipart/form-data'>
						<div class="form-container">
							<div class="side-by-side">
							    <select id="year_id" name="year_id" class="from-control" data-width="auto">
									<?php 
									$year = $conn->query("SELECT * FROM `fy` order by `id` desc ");
									while($row = $year->fetch_assoc()):
									?>
									<option value="<?= $row['id'] ?>"><?= $row['fy'] ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="side-by-side">
							    <select id="qtr_id" name="qtr_id" class="from-control" data-width="auto">
									<option value="1">JUL-SEP</option>
									<option value="2">OCT-DEC</option>
									<option value="3">JAN-MAR</option>
									<option value="4">APR-JUN</option>
								</select>
							</div>
							<div class="side-by-side">
							     <select id="d_type" name="d_type" class="from-control" data-width="auto">
									<option value="DLI">DLI</option>
								</select>
							</div>
							
							<div class="full-width">
								<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-info btn-flat btn-sm"><i class="fa fa-file-pdf"></i> 1C. Project Cash Withdrawals</button>
							</div>
						</div>
					</form>
				 </span>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
	</div>
</div>

<hr class="border-border bg-primary">
<div class="row">
	<div class="col-12 col-sm-12 col-md-6 col-lg-4">
			<div class="info-box bg-gradient-light shadow">
				 <span style="width: 100px;" class="info-box-icon bg-gradient-info elevation-1"><i class="fa fa-balance-scale"></i></span>
				 <span style="padding-left: 5px;" class="info-box-text">
					 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/a2_pdf.php" enctype='multipart/form-data'>
						<div class="form-container">
							<div class="side-by-side">
							  <select id="year_id" name="year_id" class="from-control" data-width="auto">
									<?php 
									$year = $conn->query("SELECT * FROM `fy` order by `id` desc ");
									while($row = $year->fetch_assoc()):
									?>
									<option value="<?= $row['id'] ?>"><?= $row['fy'] ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="side-by-side">
							  <select id="qtr_id" name="qtr_id" class="from-control" data-width="auto">
									<option value="1">JUL-SEP</option>
									<option value="2">OCT-DEC</option>
									<option value="3">JAN-MAR</option>
									<option value="4">APR-JUN</option>
								</select>
							</div>
							<div class="side-by-side">
							     <select id="d_type" name="d_type" class="from-control" data-width="auto">
									<option value="Non-DLI">Non-DLI</option>
									<option value="DLI">DLI</option>
								</select>
							</div>
							<div class="full-width">
								Expenditure Group  <select id="exp_group" name="exp_group" class="from-control form-control-sm select2" data-width="auto">
								<option value="">Select Expenditure Group</option>
									<?php 
									$accounts = $conn->query("SELECT * FROM `exp_group` where id <= 8;");
									while($row = $accounts->fetch_assoc()):
									?>
									<option value="<?= $row['id'] ?>"><?= $row['exp_name'] ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							
							<div class="full-width">
								<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-info btn-flat btn-sm"><i class="fa fa-file-pdf"></i> 2A. DA Expenditure for Contracts/Prior Review</button>
							</div>
						</div>
					</form>
				 </span>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
	</div>
	<div class="col-12 col-sm-12 col-md-6 col-lg-4">
			<div class="info-box bg-gradient-light shadow">
				 <span style="width: 100px;" class="info-box-icon bg-gradient-info elevation-1"><i class="fa fa-balance-scale"></i></span>
				 <span style="padding-left: 5px;" class="info-box-text">
					 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/b2_pdf.php" enctype='multipart/form-data'>
						<div class="form-container">
							<div class="side-by-side">
							  <select id="year_id" name="year_id" class="from-control" data-width="auto">
									<?php 
									$year = $conn->query("SELECT * FROM `fy` order by `id` desc ");
									while($row = $year->fetch_assoc()):
									?>
									<option value="<?= $row['id'] ?>"><?= $row['fy'] ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="side-by-side">
							  <select id="qtr_id" name="qtr_id" class="from-control" data-width="auto">
									<option value="1">JUL-SEP</option>
									<option value="2">OCT-DEC</option>
									<option value="3">JAN-MAR</option>
									<option value="4">APR-JUN</option>
								</select>
							</div>
							<div class="side-by-side">
							     <select id="d_type" name="d_type" class="from-control" data-width="auto">
									<option value="Non-DLI">Non-DLI</option>
									<option value="DLI">DLI</option>
								</select>
							</div>
							<div class="full-width">
								Expenditure Group  <select id="exp_group" name="exp_group" class="from-control form-control-sm select2" data-width="auto">
								<option value="">Select Expenditure Group</option>
									<?php 
									$accounts = $conn->query("SELECT * FROM `exp_group` where id <= 8 and id <> 2;;");
									while($row = $accounts->fetch_assoc()):
									?>
									<option value="<?= $row['id'] ?>"><?= $row['exp_name'] ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="full-width">
								<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-info btn-flat btn-sm"><i class="fa fa-file-pdf"></i> 2B. DA Expenditure for Contracts/Post Review</button>
							</div>
						</div>
					</form>
				 </span>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
	</div>
	<div class="col-12 col-sm-12 col-md-6 col-lg-4">
			<div class="info-box bg-gradient-light shadow">
				 <span style="width: 100px;" class="info-box-icon bg-gradient-info elevation-1"><i class="fa fa-balance-scale"></i></span>
				 <span style="padding-left: 5px;" class="info-box-text">
					 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/b2_works_pdf.php" enctype='multipart/form-data'>
						<div class="form-container">
							<div class="side-by-side">
							  <select id="year_id" name="year_id" class="from-control" data-width="auto">
									<?php 
									$year = $conn->query("SELECT * FROM `fy` order by `id` desc ");
									while($row = $year->fetch_assoc()):
									?>
									<option value="<?= $row['id'] ?>"><?= $row['fy'] ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="side-by-side">
							  <select id="qtr_id" name="qtr_id" class="from-control" data-width="auto">
									<option value="1">JUL-SEP</option>
									<option value="2">OCT-DEC</option>
									<option value="3">JAN-MAR</option>
									<option value="4">APR-JUN</option>
								</select>
							</div>
							<div class="side-by-side">
							     <select id="d_type" name="d_type" class="from-control" data-width="auto">
									<option value="Non-DLI">Non-DLI</option>
									<option value="DLI">DLI</option>
								</select>
							</div>
							<div class="full-width">
								Expenditure Group  <select id="exp_group" name="exp_group" class="from-control form-control-sm select2" data-width="auto">
								<option value="">Select Expenditure Group</option>
									<?php 
									$accounts = $conn->query("SELECT * FROM `exp_group` where id = 2;");
									while($row = $accounts->fetch_assoc()):
									?>
									<option value="<?= $row['id'] ?>"><?= $row['exp_name'] ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="full-width">
								<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-info btn-flat btn-sm"><i class="fa fa-file-pdf"></i> 2B. DA Expenditure for Contracts/Post Review</button>
							</div>
						</div>
					</form>
				 </span>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
	</div>
</div>
    
<hr class="border-border bg-primary">
<div class="row">
	<div class="col-12 col-sm-12 col-md-6 col-lg-4">
			<div class="info-box bg-gradient-light shadow">
				 <span style="width: 100px;" class="info-box-icon bg-gradient-info elevation-1"><i class="fa fa-balance-scale"></i></span>
				 <span style="padding-left: 5px;" class="info-box-text">
					 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/acc_bal_pdf_ffigure.php" enctype='multipart/form-data'>
						<div class="form-container">
							<div class="side-by-side">
							  <select id="year_id" name="year_id" class="from-control" data-width="auto">
									<?php 
									$year = $conn->query("SELECT * FROM `fy` order by `id` desc ");
									while($row = $year->fetch_assoc()):
									?>
									<option value="<?= $row['id'] ?>"><?= $row['fy'] ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							
							<div class="full-width">
								<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-info btn-flat btn-sm"><i class="fa fa-file-pdf"></i> Account Balance</button>
							</div>
						</div>
					</form>
				 </span>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
	</div>


	<div class="col-12 col-sm-12 col-md-6 col-lg-4">
			<div class="info-box bg-gradient-light shadow">
				 <span style="width: 100px;" class="info-box-icon bg-gradient-info elevation-1"><i class="fa fa-balance-scale"></i></span>
				 <span style="padding-left: 5px;" class="info-box-text">
					 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/acc_bal_pdf_pd.php" enctype='multipart/form-data'>
						<div class="form-container">
							
							
							<div class="full-width">
								<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-info btn-flat btn-sm"><i class="fa fa-file-pdf"></i> Account Balance (PD)</button>
							</div>
						</div>
					</form>
				 </span>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
	</div>
	
	<div class="col-12 col-sm-12 col-md-6 col-lg-4">
			<div class="info-box bg-gradient-light shadow">
				 <span style="width: 100px;" class="info-box-icon bg-gradient-info elevation-1"><i class="fa fa-balance-scale"></i></span>
				 <span style="padding-left: 5px;" class="info-box-text">
					 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/pkg_report_pdf.php" enctype='multipart/form-data'>
						<div class="form-container">
							
							
							<div class="full-width">
								<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-info btn-flat btn-sm"><i class="fa fa-file-pdf"></i> Package-wise Expenditure</button>
							</div>
						</div>
					</form>
				 </span>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
	</div>
</div>    
<hr class="border-border bg-primary">
<!--
<div class="row">
    <div class="col-md-12">
        <img src="<?= validate_image($_settings->info('cover')) ?>" alt="Website Page" id="banner-img" class="w-100">
    </div>
</div> -->