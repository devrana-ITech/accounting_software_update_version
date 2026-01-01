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


<hr class="border-border bg-primary"> --->
<div class="row">
	<div class="col-md-12">
		<p style="text-align: center; margin-bottom: 5px; font-size:22px;"><b>Cash Book, Ledger and Trial Balance Generation</b></p>
	</div>
</div>
<hr class="border-border bg-primary">
<div class="row">
	<div class="col-12 col-sm-12 col-md-6 col-lg-4">
        <div class="info-box bg-gradient-light shadow">
		  
             <span style="width: 100px;" class="info-box-icon bg-secondary bg-gradient elevation-1"><i class="fas fa-coins"></i></span>
			 <span style="padding-left: 5px;" class="info-box-text">
				 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/cb_nondli_pdf.php" enctype='multipart/form-data'>
					<div class="form-container">
						<div class="side-by-side">
							<input style="background-color: #f0dcf7;" type="date" id="from_date" name="from_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						<div class="side-by-side">
							<input style="background-color: #f0dcf7;" type="date" id="to_date" name="to_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						
						<div class="full-width">
							<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-secondary btn-flat btn-sm"><i class="fa fa-file-pdf"></i> View Cash Book (Non-DLI)</button>
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
		  
             <span style="width: 100px;" class="info-box-icon bg-primary bg-gradient elevation-1"><i class="fas fa-coins"></i></span>
			 <span style="padding-left: 5px;" class="info-box-text">
				 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/cb_dli_pdf.php" enctype='multipart/form-data'>
					<div class="form-container">
						<div class="side-by-side">
							<input style="background-color: #f0dcf7;" type="date" id="from_date" name="from_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						<div class="side-by-side">
							<input style="background-color: #f0dcf7;" type="date" id="to_date" name="to_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						
						<div class="full-width">
							<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-primary btn-flat btn-sm"><i class="fa fa-file-pdf"></i> View Cash Book (DLI)</button>
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
					 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/tb_pdf.php" enctype='multipart/form-data'>
						<div class="form-container">
							<div class="side-by-side">
								<input style="background-color: #f0dcf7;" type="date" id="from_date" name="from_date" class="form-control form-control-sm form-control-border rounded-0" value="">
							</div>
							<div class="side-by-side">
								<input style="background-color: #f0dcf7;" type="date" id="to_date" name="to_date" class="form-control form-control-sm form-control-border rounded-0" value="">
							</div>
							
							<div class="full-width">
								<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-info btn-flat btn-sm"><i class="fa fa-file-pdf"></i> View Trial Balance</button>
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
	<div class="col-md-6">
        <div class="info-box bg-gradient-light shadow">
            <span style="width: 180px;" class="info-box-icon bg-gradient-success elevation-1"><i class="fas fa-receipt"></i></span>

            <span style="padding-left: 5px;" class="info-box-text">
				 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/ledger_nondli_pdf.php" enctype='multipart/form-data'>
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
							<select name="account_id" class="from-control form-control-sm select2" data-width="100%">
								<option value="">Select Economic Code</option>
								<?php 
								$accounts = $conn->query("SELECT * FROM `account_list` where delete_flag = 0 and `status` = 1 order by `acc_code` asc ");
								while($row = $accounts->fetch_assoc()):
								?>
								<option value="<?= $row['id'] ?>"><?= $row['acc_code']. '--' .$row['name'] ?></option>
								<?php endwhile; ?>
							</select>
						</div>
						
						<div class="full-width">
							<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-success btn-flat btn-sm"><i class="fa fa-file-pdf"></i> View Ledger (Non-DLI)</button>
						</div>
					</div>
				</form>
			 </span>
            <!-- /.info-box-content -->
        </div>
    </div>
	<div class="col-md-6">
		<div class="info-box bg-gradient-light shadow">
			<span style="width: 180px;" class="info-box-icon bg-gradient-success elevation-1"><i class="fas fa-receipt"></i></span>

			<span style="padding-left: 5px;" class="info-box-text">
				 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/ledger_dli_pdf.php" enctype='multipart/form-data'>
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
							<select name="account_id" class="from-control form-control-sm select2" data-width="100%">
								<option value="">Select Economic Code</option>
								<?php 
								$accounts = $conn->query("SELECT * FROM `account_list` where delete_flag = 0 and `status` = 1 order by `acc_code` asc ");
								while($row = $accounts->fetch_assoc()):
								?>
								<option value="<?= $row['id'] ?>"><?= $row['acc_code']. '--' .$row['name'] ?></option>
								<?php endwhile; ?>
							</select>
						</div>
						
						<div class="full-width">
							<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-success btn-flat btn-sm"><i class="fa fa-file-pdf"></i> View Ledger (DLI)</button>
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
	<div class="col-12 col-sm-12 col-md-6 col-lg-4">
        <div class="info-box bg-gradient-light shadow">
		  
             <span style="width: 100px;" class="info-box-icon bg-secondary bg-gradient elevation-1"><i class="fas fa-coins"></i></span>
			 <span style="padding-left: 5px;" class="info-box-text">
				 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/list_debit_report_pdf.php" enctype='multipart/form-data'>
					<div class="form-container">
						<div class="side-by-side">
							<input style="background-color: #f0dcf7;" type="date" id="from_date" name="from_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						<div class="side-by-side">
							<input style="background-color: #f0dcf7;" type="date" id="to_date" name="to_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						
						<div class="full-width">
							<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-secondary btn-flat btn-sm"><i class="fa fa-file-pdf"></i> View Debit Voucher (Non-DLI)</button>
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
		  
             <span style="width: 100px;" class="info-box-icon bg-secondary bg-gradient elevation-1"><i class="fas fa-coins"></i></span>
			 <span style="padding-left: 5px;" class="info-box-text">
				 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/ic_report_pdf.php" enctype='multipart/form-data'>
					<div class="form-container">
						<div class="side-by-side">
							<input style="background-color: #f0dcf7;" type="date" id="from_date" name="from_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						<div class="side-by-side">
							<input style="background-color: #f0dcf7;" type="date" id="to_date" name="to_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						
						<div class="full-width">
							<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-secondary btn-flat btn-sm"><i class="fa fa-file-pdf"></i> Summary Payment (Individual Consultants)</button>
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
		  
             <span style="width: 100px;" class="info-box-icon bg-secondary bg-gradient elevation-1"><i class="fas fa-coins"></i></span>
			 <span style="padding-left: 5px;" class="info-box-text">
				 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/nc_report_pdf.php" enctype='multipart/form-data'>
					<div class="form-container">
						<div class="side-by-side">
							<input style="background-color: #f0dcf7;" type="date" id="from_date" name="from_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						<div class="side-by-side">
							<input style="background-color: #f0dcf7;" type="date" id="to_date" name="to_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						
						<div class="full-width">
							<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-secondary btn-flat btn-sm"><i class="fa fa-file-pdf"></i> Summary Payment (Non-Consultants' Services)</button>
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
		  
             <span style="width: 100px;" class="info-box-icon bg-secondary bg-gradient elevation-1"><i class="fas fa-coins"></i></span>
			 <span style="padding-left: 5px;" class="info-box-text">
				 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/con_report_pdf.php" enctype='multipart/form-data'>
					<div class="form-container">
						<div class="side-by-side">
							<input style="background-color: #f0dcf7;" type="date" id="from_date" name="from_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						<div class="side-by-side">
							<input style="background-color: #f0dcf7;" type="date" id="to_date" name="to_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						
						<div class="full-width">
							<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-secondary btn-flat btn-sm"><i class="fa fa-file-pdf"></i> Summary Payment (Consulting Firms)</button>
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
		  
             <span style="width: 100px;" class="info-box-icon bg-secondary bg-gradient elevation-1"><i class="fas fa-coins"></i></span>
			 <span style="padding-left: 5px;" class="info-box-text">
				 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/news_report_pdf.php" enctype='multipart/form-data'>
					<div class="form-container">
						<div class="side-by-side">
							<input style="background-color: #f0dcf7;" type="date" id="from_date" name="from_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						<div class="side-by-side">
							<input style="background-color: #f0dcf7;" type="date" id="to_date" name="to_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						
						<div class="full-width">
							<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-secondary btn-flat btn-sm"><i class="fa fa-file-pdf"></i> Summary Payment (Advertisement-Newspaper)</button>
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
		  
             <span style="width: 100px;" class="info-box-icon bg-secondary bg-gradient elevation-1"><i class="fas fa-coins"></i></span>
			 <span style="padding-left: 5px;" class="info-box-text">
				 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/nc_report_pdf.php" enctype='multipart/form-data'>
					<div class="form-container">
						<div class="side-by-side">
							<input style="background-color: #f0dcf7;" type="date" id="from_date" name="from_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						<div class="side-by-side">
							<input style="background-color: #f0dcf7;" type="date" id="to_date" name="to_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						
						<div class="full-width">
							<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-secondary btn-flat btn-sm"><i class="fa fa-file-pdf"></i> Summary Payment (Non-Consultants' Services)</button>
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
	<div class="col-md-6">
        <div class="info-box bg-gradient-light shadow">
            <span style="width: 180px;" class="info-box-icon bg-gradient-success elevation-1"><i class="fas fa-receipt"></i></span>

            <span style="padding-left: 5px;" class="info-box-text">
				 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/ind_con_report_pdf.php" enctype='multipart/form-data'>
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
							<select name="account_id" class="from-control form-control-sm select2" data-width="100%">
								<option value="">Select Consultant's Name</option>
								<?php 
								$accounts = $conn->query("SELECT * FROM `payee` where payee_type = 1 order by `payee_name` asc");
								while($row = $accounts->fetch_assoc()):
								?>
								<option value="<?= $row['id'] ?>"><?= $row['payee_name']. '--' .$row['designation'] ?></option>
								<?php endwhile; ?>
							</select>
						</div>
						
						<div class="full-width">
							<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-success btn-flat btn-sm"><i class="fa fa-file-pdf"></i> Individual Consultant's Detailed Payment</button>
						</div>
					</div>
				</form>
			 </span>
            <!-- /.info-box-content -->
        </div>
    </div>
	<div class="col-md-6">
		<div class="info-box bg-gradient-light shadow">
			<span style="width: 180px;" class="info-box-icon bg-gradient-success elevation-1"><i class="fas fa-receipt"></i></span>

			<span style="padding-left: 5px;" class="info-box-text">
				 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/ind_con_firm_report_pdf.php" enctype='multipart/form-data'>
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
							<select name="account_id" class="from-control form-control-sm select2" data-width="100%">
								<option value="">Select Consulting Firm</option>
								<?php 
								$accounts = $conn->query("SELECT * FROM `payee` where payee_type = 3 order by `payee_name` asc ");
								while($row = $accounts->fetch_assoc()):
								?>
								<option value="<?= $row['id'] ?>"><?= $row['payee_name'] ?></option>
								<?php endwhile; ?>
							</select>
						</div>
						
						<div class="full-width">
							<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-success btn-flat btn-sm"><i class="fa fa-file-pdf"></i> Individual Consulting Firm's Detailed Payment</button>
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
	<div class="col-md-6">
        <div class="info-box bg-gradient-light shadow">
            <span style="width: 180px;" class="info-box-icon bg-gradient-success elevation-1"><i class="fas fa-receipt"></i></span>

            <span style="padding-left: 5px;" class="info-box-text">
				 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/ind_noncon_firm_report_pdf.php" enctype='multipart/form-data'>
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
							<select name="account_id" class="from-control form-control-sm select2" data-width="100%">
								<option value="">Select Non-Consulting Firm's Name</option>
								<?php 
								$accounts = $conn->query("SELECT * FROM `payee` where payee_type = 2 order by `payee_name` asc");
								while($row = $accounts->fetch_assoc()):
								?>
								<option value="<?= $row['id'] ?>"><?= $row['payee_name']. '--' .$row['designation'] ?></option>
								<?php endwhile; ?>
							</select>
						</div>
						
						<div class="full-width">
							<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-success btn-flat btn-sm"><i class="fa fa-file-pdf"></i> Individual Non-Consulting Firm's Detailed Payment</button>
						</div>
					</div>
				</form>
			 </span>
            <!-- /.info-box-content -->
        </div>
    </div>
	<div class="col-md-6">
		<div class="info-box bg-gradient-light shadow">
			<span style="width: 180px;" class="info-box-icon bg-gradient-success elevation-1"><i class="fas fa-receipt"></i></span>

			<span style="padding-left: 5px;" class="info-box-text">
				 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/ind_news_report_pdf.php" enctype='multipart/form-data'>
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
							<select name="account_id" class="from-control form-control-sm select2" data-width="100%">
								<option value="">Select Newspaper Name</option>
								<?php 
								$accounts = $conn->query("SELECT * FROM `payee` where payee_type = 4 order by `payee_name` asc ");
								while($row = $accounts->fetch_assoc()):
								?>
								<option value="<?= $row['id'] ?>"><?= $row['payee_name'] ?></option>
								<?php endwhile; ?>
							</select>
						</div>
						
						<div class="full-width">
							<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-success btn-flat btn-sm"><i class="fa fa-file-pdf"></i> Individual Newspaper's Detailed Payment</button>
						</div>
					</div>
				</form>
			 </span>
			<!-- /.info-box-content -->
		</div>
	</div>
</div>

<!--- Works       -->

<hr class="border-border bg-primary">
<div class="row">
	<div class="col-12 col-sm-12 col-md-6 col-lg-4">
        <div class="info-box bg-gradient-light shadow">
		  
             <span style="width: 100px;" class="info-box-icon bg-secondary bg-gradient elevation-1"><i class="fas fa-coins"></i></span>
			 <span style="padding-left: 5px;" class="info-box-text">
				 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/works_report_pdf.php" enctype='multipart/form-data'>
					<div class="form-container">
						<div class="side-by-side">
							<input style="background-color: #f0dcf7;" type="date" id="from_date" name="from_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						<div class="side-by-side">
							<input style="background-color: #f0dcf7;" type="date" id="to_date" name="to_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						<div class="side-by-side">
							 <select id="d_type" name="d_type" class="from-control" data-width="auto">
								<option value="Non-DLI">Non-DLI</option>
								<option value="DLI">DLI</option>
								<option value="Combined">Combined</option>
							</select>
						</div>
						
						<div class="full-width">
							<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-secondary btn-flat btn-sm"><i class="fa fa-file-pdf"></i> Summary Payment (Works)</button>
						</div>
					</div>
				</form>
			 </span>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>

	<div class="col-12 col-sm-12 col-md-8 col-lg-8">
		<div class="info-box bg-gradient-light shadow">
			<span style="width: 180px;" class="info-box-icon bg-gradient-success elevation-1"><i class="fas fa-receipt"></i></span>

			<span style="padding-left: 5px;" class="info-box-text">
				 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/ind_works_report_pdf.php" enctype='multipart/form-data'>
					<div class="form-container">
						<div class="side-by-side">
							<label for="from_date">From:</label>
							<input style="background-color: #f0dcf7;" type="date" id="from_date" name="from_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						<div class="side-by-side">
							<label for="to_date">To:</label>
							<input style="background-color: #f0dcf7;" type="date" id="to_date" name="to_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						<div class="side-by-side">
							 <select id="d_type" name="d_type" class="from-control" data-width="auto">
								<option value="Non-DLI">Non-DLI</option>
								<option value="DLI">DLI</option>
								<option value="Combined">Combined</option>
							</select>
						</div>
						
						<div class="full-width">
							<select name="account_id" class="from-control form-control-sm select2" data-width="100%">
								<option value="">Select Consulting Firm</option>
								<?php 
								$accounts = $conn->query("SELECT * FROM `payee` where payee_type = 5 order by `payee_name` asc ");
								while($row = $accounts->fetch_assoc()):
								?>
								<option value="<?= $row['id'] ?>"><?= $row['payee_name'] ?></option>
								<?php endwhile; ?>
							</select>
						</div>
						
						<div class="full-width">
							<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-success btn-flat btn-sm"><i class="fa fa-file-pdf"></i> Individual Contractor's Detailed Payment</button>
						</div>
					</div>
				</form>
			 </span>
			<!-- /.info-box-content -->
		</div>
	</div>
</div>

<!--- End of Works       -->


<!--- Start of Goods and Operating Costs       -->

<hr class="border-border bg-primary">
<div class="row">
	<div class="col-md-6">
        <div class="info-box bg-gradient-light shadow">
            <span style="width: 180px;" class="info-box-icon bg-gradient-success elevation-1"><i class="fas fa-receipt"></i></span>

            <span style="padding-left: 5px;" class="info-box-text">
				 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/goods_report_pdf.php" enctype='multipart/form-data'>
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
							<select name="account_id" class="from-control form-control-sm select2" data-width="100%">
								<option value="">Select Expenditure Group Name</option>
								<?php 
								$accounts = $conn->query("SELECT * FROM `exp_group` where id = 1 or id = 5 or id = 6 order by `id` asc");
								while($row = $accounts->fetch_assoc()):
								?>
								<option value="<?= $row['id'] ?>"><?= $row['exp_name'] ?></option>
								<?php endwhile; ?>
							</select>
						</div>
						
						<div class="full-width">
							<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-success btn-flat btn-sm"><i class="fa fa-file-pdf"></i> Goods Detailed Payment</button>
						</div>
					</div>
				</form>
			 </span>
            <!-- /.info-box-content -->
        </div>
    </div>
	<div class="col-md-6">
		<div class="info-box bg-gradient-light shadow">
			<span style="width: 180px;" class="info-box-icon bg-gradient-success elevation-1"><i class="fas fa-receipt"></i></span>

			<span style="padding-left: 5px;" class="info-box-text">
				 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/ind_con_firm_report_pdf.php" enctype='multipart/form-data'>
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
							<select name="account_id" class="from-control form-control-sm select2" data-width="100%">
								<option value="">Select Consulting Firm</option>
								<?php 
								$accounts = $conn->query("SELECT * FROM `payee` where payee_type = 3 order by `payee_name` asc ");
								while($row = $accounts->fetch_assoc()):
								?>
								<option value="<?= $row['id'] ?>"><?= $row['payee_name'] ?></option>
								<?php endwhile; ?>
							</select>
						</div>
						
						<div class="full-width">
							<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-success btn-flat btn-sm"><i class="fa fa-file-pdf"></i> Individual Consulting Firm's Detailed Payment</button>
						</div>
					</div>
				</form>
			 </span>
			<!-- /.info-box-content -->
		</div>
	</div>
</div>


<!--- GoB Ledger Start -->

<hr class="border-border bg-primary">
<div class="row">
	<div class="col-md-6">
        <div class="info-box bg-gradient-light shadow">
            <span style="width: 180px;" class="info-box-icon bg-gradient-success elevation-1"><i class="fas fa-receipt"></i></span>

            <span style="padding-left: 5px;" class="info-box-text">
				 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/ledger_gob_pdf.php" enctype='multipart/form-data'>
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
							<select name="account_id" class="from-control form-control-sm select2" data-width="100%">
								<option value="">Select Economic Code</option>
								<?php 
								$accounts = $conn->query("SELECT * FROM `account_list` where delete_flag = 0 and `status` = 1 order by `acc_code` asc ");
								while($row = $accounts->fetch_assoc()):
								?>
								<option value="<?= $row['id'] ?>"><?= $row['acc_code']. '--' .$row['name'] ?></option>
								<?php endwhile; ?>
							</select>
						</div>
						
						<div class="full-width">
							<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-success btn-flat btn-sm"><i class="fa fa-file-pdf"></i> View Ledger (GoB)</button>
						</div>
					</div>
				</form>
			 </span>
            <!-- /.info-box-content -->
        </div>
    </div>
</div>

<!--- GoB Ledger End -->


<!--
    
<hr class="border-border bg-primary">
<div class="row">
	<div class="col-12 col-sm-12 col-md-6 col-lg-4">
        <div class="info-box bg-gradient-light shadow">  
             <span style="width: 100px;" class="info-box-icon bg-gradient-danger elevation-1"><i class="fas fa-coins"></i></span>
			 <span style="padding-left: 5px;" class="info-box-text">
				 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/imed_pdf.php" enctype='multipart/form-data'>
					<div class="form-container">
						<div class="side-by-side">
							<input style="background-color: #f0dcf7;" type="date" id="from_date" name="from_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						<div class="side-by-side">
							<input style="background-color: #f0dcf7;" type="date" id="to_date" name="to_date" class="form-control form-control-sm form-control-border rounded-0" value="">
						</div>
						
						<div class="full-width">
							<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-danger btn-flat btn-sm"><i class="fa fa-file-pdf"></i> IMED Report</button>
						</div>
					</div>
				</form>
			 </span>
        </div>
    </div>

	<div class="col-12 col-sm-12 col-md-6 col-lg-4">
			<div class="info-box bg-gradient-light shadow">
				 <span style="width: 100px;" class="info-box-icon bg-gradient-info elevation-1"><i class="fa fa-balance-scale"></i></span>
				 <span style="padding-left: 5px;" class="info-box-text">
					 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/journalsDebitVoucher/pkg_pdf.php" enctype='multipart/form-data'>
						<div class="form-container">
							<div class="side-by-side">
								<input style="background-color: #f0dcf7;" type="date" id="from_date" name="from_date" class="form-control form-control-sm form-control-border rounded-0" value="">
							</div>
							<div class="side-by-side">
								<input style="background-color: #f0dcf7;" type="date" id="to_date" name="to_date" class="form-control form-control-sm form-control-border rounded-0" value="">
							</div>
							<div class="full-width">
								<select name="package_id" class="from-control form-control-sm select2" data-width="100%">
									<option value="">Select Package Number</option>
									<?php 
									$accounts = $conn->query("SELECT * FROM `pkg` order by `pack_name` asc ");
									while($row = $accounts->fetch_assoc()):
									?>
									<option value="<?= $row['id'] ?>"><?= $row['pack_name']. '--' .$row['pack_details'] ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							
							
							<div class="full-width">
								<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-info btn-flat btn-sm"><i class="fa fa-file-pdf"></i> View Package Report</button>
							</div>
						</div>
					</form>
				 </span>
			</div>
	</div>
	
	
</div>
-->
<hr class="border-border bg-primary">


<!--
<div class="row">
    <div class="col-md-12">
        <img src="<?= validate_image($_settings->info('cover')) ?>" alt="Website Page" id="banner-img" class="w-100">
    </div>
</div> -->