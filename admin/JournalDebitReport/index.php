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
		<p style="text-align: center; margin-bottom: 5px; font-size:22px;"><b>JournalsDebit Monthly Reports</b></p>
	</div>
</div>
<hr class="border-border bg-primary">
<div class="row">
	<div class="col-12 col-sm-12 col-md-6 col-lg-4">
			<div class="info-box bg-gradient-light shadow">
				 <span style="width: 100px;" class="info-box-icon bg-gradient-info elevation-1"><i class="fa fa-balance-scale"></i></span>
				 <span style="padding-left: 5px;" class="info-box-text">
					 <form  method="post" target="_blank" action="<?php echo base_url ?>admin/JournalMonthlyReport/journal_monthly_report.php" enctype='multipart/form-data'>
						<div class="form-container">
							<div class="side-by-side">
								<select name="year_id" id="year_id" class="from-control" data-width="auto">
									<?php 
										$year = $conn->query("SELECT * FROM `fy` ORDER BY `id` DESC");
										while($row = $year->fetch_assoc()):
									?>
									<option value="<?= $row['id'] ?>"><?= $row['fy'] ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="side-by-side">
								<select name="qtr_id" id="qtr_id" class="from-control" data-width="auto">
									<option value="6">JUNE</option>
									<option value="7">JULY</option>
									<option value="8">AUGUST</option>
									<option value="9">SEPTEMBER</option>
									<option value="10">OCTOBER</option>
									<option value="11">NOVEMBER</option>
									<option value="12">DECEMBER</option>
									<option value="1">JANUARY</option>
									<option value="2">FEBRUARY</option>
									<option value="3">MARCH</option>
									<option value="4">APRIL</option>
									<option value="5">MAY</option>
								</select>
							</div>
							<div class="side-by-side">
								<select name="d_type" id="d_type" class="from-control" data-width="auto">
									<option value="Non-DLI">Non-DLI</option>
									<option value="DLI">DLI</option>
								</select>
							</div>
							
							<div class="full-width">
								<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-info btn-flat btn-sm"><i class="fa fa-file-pdf"></i> JournalsDebit Monthly Reports</button>
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