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
  font-size: 15px;
}
		
</style>
<h2>Summary Report</h2>

<div class="row">
  <div class="col-md-6">
	<form  method="post" target="_blank" action="<?php echo base_url ?>admin/summary_pdf.php" enctype='multipart/form-data'>
		<div class="form-container">
			<div class="full-width">
				<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-secondary btn-flat btn-sm"><i class="fa fa-file-pdf"></i> Print Advance and Expenditure Report</button>
			</div>
		</div>
	</form>
  </div>
  <div class="col-md-6">
	<form  method="post" target="_blank" action="<?php echo base_url ?>admin/summary_pdf_detailed.php" enctype='multipart/form-data'>
		<div class="form-container">
			<div class="full-width">
				<button type="submit" style="margin-top: -12px; width: 100%" class="btn btn-secondary btn-flat btn-sm"><i class="fa fa-file-pdf"></i> Print Detailed Report</button>
			</div>
		</div>
	</form>
  </div>
</div>

<!--- ////Outstanding Advances to be Accounted  -->

<?php
$journals = $conn->query("SELECT (case when sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) is null then 0 else sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE (account_id = 51) and journal_type = 'cv' and dli_type = 'Non-DLI';");

$nondli_received = 0;

if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$nondli_received = $nondli_received + $row['Crt'];
	   }
	}
}

//$journals = $conn->query("SELECT (case when sum(CASE WHEN group_id = 4 and account_id <> 100  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 and account_id <> 100 THEN jt.amount END) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE (account_id = 51 and new <> 1) and dli_type = 'Non-DLI';");


//$journals = $conn->query("SELECT (case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE (account_id <> 51 and account_id <> 52 and account_id <> 100 and new <> 1) and dli_type = 'Non-DLI' and journal_type = 'dv';");


$nondli_expenses = 0;
$item_list = $conn->query("SELECT * from exp_group where id <= 8;"); 
while($row_item = $item_list->fetch_assoc()){
	$item_code = $row_item['id'];
	$journals = $conn->query("SELECT ((case when sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE eg.id = '$item_code' and dli_type = 'Non-DLI' group by eg.id;");
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$nondli_expenses = $nondli_expenses + $row['Crt'];
			}
		}
	}
}


/*
$nondli_expenses = 0;

if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$nondli_expenses = $nondli_expenses + $row['Crt'];
	   }
	}
}
*/

$nondli_bal = 0;

$nondli_bal = $nondli_received - $nondli_expenses;

////////////D L I

$journals = $conn->query("SELECT (case when sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) is null then 0 else sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE (account_id = 51) and journal_type = 'cv' and dli_type = 'DLI';");

$dli_received = 0;

if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$dli_received = $dli_received + $row['Crt'];
	   }
	}
}

//$journals = $conn->query("SELECT (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE (account_id = 51 and new <> 1) and dli_type = 'DLI';");

//$journals = $conn->query("SELECT (case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE (account_id <> 51 and account_id <> 52 and account_id <> 100 and new <> 1) and dli_type = 'DLI' and journal_type = 'dv';");






$dli_expenses = 0;
$item_list = $conn->query("SELECT * from exp_group where id <= 8;"); 
while($row_item = $item_list->fetch_assoc()){
	$item_code = $row_item['id'];
	$journals = $conn->query("SELECT ((case when sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE eg.id = '$item_code' and dli_type = 'DLI' group by eg.id;");
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$dli_expenses = $dli_expenses + $row['Crt'];
		   }
		}
	}
}


$dli_bal = 0;

$dli_bal = $dli_received - $dli_expenses;


$nondli_dli_recived = 0;
$nondli_dli_expenses = 0;


$grand_total = 0;

$nondli_dli_recived = $dli_received + $nondli_received;
$nondli_dli_expenses = $dli_expenses + $nondli_expenses;

$grand_total = $nondli_dli_recived - $nondli_dli_expenses;

?>

<hr class="border-border bg-primary">
<div class="row">
	<div class="col-md-6">
	<p style="text-align: center; margin-bottom: -25px; font-size:22px;"><b>Outstanding Advances to be Accounted</b></p>
	<p style="text-align: right; margin-bottom: 0px; font-size: 16px;"><i>(Amount in BDT)</i></p>
        <table>
			<thead>
				<tr>
				  <th class="cl1" style="background-color: #d48104; color: white;"><b>Fund Type</b></th>
				  <th class="cl2" style="background-color: #d48104; color: white;"><b>Cumulative Advances</b></th>
				  <th class="cl2" style="background-color: #d48104; color: white;"><b>Cumulative Expenditures</b></th>
				  <th class="cl2" style="background-color: #d48104; color: white;"><b>Total (Outstanding Advances)</b></th>
				</tr>
			</thead>		  
			<tbody>
				<tr>
				  <td class="cl1" style="background-color: #faf5fc;"><b>Non-DLI</b></td>
				  <td class="cl2" style="background-color: #faf5fc;"><b><?php echo number_format($nondli_received, 2) ?></b></td>
				  <td class="cl2" style="background-color: #faf5fc;"><b><?php echo number_format($nondli_expenses, 2) ?></b></td>  
				  <td class="cl2" style="background-color: #faf5fc;"><b><?php echo number_format($nondli_bal, 2) ?></b></td>
				</tr>
				<tr>
				  <td class="cl1" style="background-color: #f2ebf5;"><b>DLI</b></td>
				  <td class="cl2" style="background-color: #f2ebf5;"><b><?php echo number_format($dli_received, 2) ?></b></td>
				  <td class="cl2" style="background-color: #f2ebf5;"><b><?php echo number_format($dli_expenses, 2) ?></b></td>
				  <td class="cl2" style="background-color: #f2ebf5;"><b><?php echo number_format($dli_bal, 2) ?></b></td>
				</tr>
				<tr>
				  <td class="cl1" style="background-color: #ffe6e6;"><b>Total</b></td>
				  <td class="cl2" style="background-color: #ffe6e6;"><b><?php echo number_format($nondli_dli_recived, 2) ?></b></td>
				  <td class="cl2" style="background-color: #ffe6e6;"><b><?php echo number_format($nondli_dli_expenses, 2) ?></b></td>
				  <td class="cl2" style="background-color: #ffe6e6;"><b><?php echo number_format($grand_total, 2) ?></b></td>
				</tr>
			</tbody>
	   </table>
    </div>





<!--- ////FY-wise Fund Received  -->

<?php
$journals = $conn->query("SELECT (case when sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) is null then 0 else sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 2 and (account_id = 51) and journal_type = 'cv' and dli_type = 'Non-DLI';");

$nondli_2 = 0;
$nondli_total = 0;

if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$nondli_2 = $nondli_2 + $row['Crt'];
	   }
	}
}

$journals = $conn->query("SELECT (case when sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) is null then 0 else sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 3 and (account_id = 51) and journal_type = 'cv' and dli_type = 'Non-DLI';");

$nondli_3 = 0;
if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$nondli_3 = $nondli_3 + $row['Crt'];
	   }
	}
}

$journals = $conn->query("SELECT (case when sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) is null then 0 else sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 4 and (account_id = 51) and journal_type = 'cv' and dli_type = 'Non-DLI';");

$nondli_4 = 0;
if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$nondli_4 = $nondli_4 + $row['Crt'];
	   }
	}
}

$nondli_total = $nondli_2 + $nondli_3 + $nondli_4;


////////////D L I

$journals = $conn->query("SELECT (case when sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) is null then 0 else sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 2 and (account_id = 51) and journal_type = 'cv' and dli_type = 'DLI';");

$dli_2 = 0;
$dli_total = 0;

if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$dli_2 = $dli_2 + $row['Crt'];
	   }
	}
}

$journals = $conn->query("SELECT (case when sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) is null then 0 else sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 3 and (account_id = 51) and journal_type = 'cv' and dli_type = 'DLI';");

$dli_3 = 0;
if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$dli_3 = $dli_3 + $row['Crt'];
	   }
	}
}

$journals = $conn->query("SELECT (case when sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) is null then 0 else sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 4 and (account_id = 51) and journal_type = 'cv' and dli_type = 'DLI';");

$dli_4 = 0;
if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$dli_4 = $dli_4 + $row['Crt'];
	   }
	}
}

$dli_total = $dli_2 + $dli_3 + $dli_4;

$nondli_dli2 = 0;
$nondli_dli3 = 0;
$nondli_dli4 = 0;

$grand_total = 0;

$nondli_dli2 = $nondli_2 + $dli_2;
$nondli_dli3 = $nondli_3 + $dli_3;
$nondli_dli4 = $nondli_4 + $dli_4;

$grand_total = $nondli_dli2 + $nondli_dli3 + $nondli_dli4;

?>

	<div class="col-md-6">
	<p style="text-align: center; margin-bottom: -25px; font-size:22px;"><b>Financial Year-wise Fund Advances</b></p>
	<p style="text-align: right; margin-bottom: 0px; font-size: 16px;"><i>(Amount in BDT)</i></p>
        <table>
			<thead>
				<tr>
				  <th class="cl1" style="background-color: #d48104; color: white;"><b>Fund Type</b></th>
				  <th class="cl2" style="background-color: #d48104; color: white;"><b>2021-22</b></th>
				  <th class="cl2" style="background-color: #d48104; color: white;"><b>2022-23</b></th>
				  <th class="cl2" style="background-color: #d48104; color: white;"><b>2023-24</b></th>
				  <th class="cl2" style="background-color: #d48104; color: white;"><b>Total</b></th>
				</tr>
			</thead>		  
			<tbody>
				<tr>
				  <td class="cl1" style="background-color: #faf5fc;"><b>Non-DLI</b></td>
				  <td class="cl2" style="background-color: #faf5fc;"><a href="advances.php?year_id=2&dli_type=Non-DLI" target="_blank"><b><?php if ($nondli_2 <= 0) echo  "--"; else echo number_format($nondli_2, 2); ?></b></a></td>
				  <td class="cl2" style="background-color: #faf5fc;"><a href="advances.php?year_id=3&dli_type=Non-DLI" target="_blank"><b><?php if ($nondli_3 <= 0) echo  "--"; else echo number_format($nondli_3, 2); ?></b></a></td>
				  <td class="cl2" style="background-color: #faf5fc;"><a href="advances.php?year_id=4&dli_type=Non-DLI" target="_blank"><b><?php if ($nondli_4 <= 0) echo  "--"; else echo number_format($nondli_4, 2); ?></b></a></td>
				  <td class="cl2" style="background-color: #faf5fc;"><a href="advances.php?year_id=0&dli_type=Non-DLI" target="_blank"><b><?php if ($nondli_total <= 0) echo  "--"; else echo number_format($nondli_total, 2); ?></b></a></td>
				  
				</tr>
				<tr>
				  <td class="cl1" style="background-color: #f2ebf5;"><b>DLI</b></td>
				  <td class="cl2" style="background-color: #f2ebf5;"><a href="advances.php?year_id=2&dli_type=DLI" target="_blank"><b><?php if ($dli_2 <= 0) echo  "--"; else echo number_format($dli_2, 2); ?></b></a></td>
				  <td class="cl2" style="background-color: #f2ebf5;"><a href="advances.php?year_id=3&dli_type=DLI" target="_blank"><b><?php if ($dli_3 <= 0) echo  "--"; else echo number_format($dli_3, 2); ?></b></a></td>
				  <td class="cl2" style="background-color: #f2ebf5;"><a href="advances.php?year_id=4&dli_type=DLI" target="_blank"><b><?php if ($dli_4 <= 0) echo  "--"; else echo number_format($dli_4, 2); ?></b></a></td>
				  <td class="cl2" style="background-color: #f2ebf5;"><a href="advances.php?year_id=0&dli_type=DLI" target="_blank"><b><?php if ($dli_total <= 0) echo  "--"; else echo number_format($dli_total, 2); ?></b></a></td>
				</tr>
				<tr>
				  <td class="cl1" style="background-color: #ffe6e6;"><b>Total</b></td>
				  <td class="cl2" style="background-color: #ffe6e6;"><a href="advances.php?year_id=2&dli_type=0" target="_blank"><b><?php echo number_format($nondli_dli2, 2) ?></b></a></td>
				  <td class="cl2" style="background-color: #ffe6e6;"><a href="advances.php?year_id=3&dli_type=0" target="_blank"><b><?php echo number_format($nondli_dli3, 2) ?></b></a></td>
				  <td class="cl2" style="background-color: #ffe6e6;"><a href="advances.php?year_id=4&dli_type=0" target="_blank"><b><?php if ($nondli_dli4 <= 0) echo  "--"; else echo number_format($nondli_dli4, 2); ?></b></a></td>
				  <td class="cl2" style="background-color: #ffe6e6;"><a href="advances.php?year_id=0&dli_type=0" target="_blank"><b><?php echo number_format($grand_total, 2) ?></b></a></td>
				</tr>
			</tbody>
	   </table>
    </div>
</div>	
	







<!-- ///// FY-wise Expenditure -->
<?php
//$journals = $conn->query("SELECT (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 2 and (account_id = 51 and new <> 1) and dli_type = 'Non-DLI';");

//$journals = $conn->query("SELECT (case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 2 and (account_id <> 51 and account_id <> 52 and account_id <> 100 and new <> 1) and dli_type = 'Non-DLI' and journal_type = 'dv';");

$nondli_2 = 0;
$nondli_total = 0;


$item_list = $conn->query("SELECT * from exp_group where id <= 8;"); 
while($row_item = $item_list->fetch_assoc()){
	$item_code = $row_item['id'];
	$journals = $conn->query("SELECT ((case when sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = '$item_code' and dli_type = 'Non-DLI' group by eg.id;");
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$nondli_2 = $nondli_2 + $row['Crt'];
		   }
		}
	}
}


//$journals = $conn->query("SELECT (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 3 and (account_id = 51 and new <> 1) and dli_type = 'Non-DLI';");

//$journals = $conn->query("SELECT (case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 3 and (account_id <> 51 and account_id <> 52 and account_id <> 100 and new <> 1) and dli_type = 'Non-DLI' and journal_type = 'dv';");

$nondli_3 = 0;

$item_list = $conn->query("SELECT * from exp_group where id <= 8;"); 
while($row_item = $item_list->fetch_assoc()){
	$item_code = $row_item['id'];
	$journals = $conn->query("SELECT ((case when sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = '$item_code' and dli_type = 'Non-DLI' group by eg.id;");
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$nondli_3 = $nondli_3 + $row['Crt'];
		   }
		}
	}
}


//$journals = $conn->query("SELECT (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 4 and (account_id = 51 and new <> 1) and dli_type = 'Non-DLI';");

//$journals = $conn->query("SELECT (case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 4 and (account_id <> 51 and account_id <> 52 and account_id <> 100 and new <> 1) and dli_type = 'Non-DLI' and journal_type = 'dv';");

$nondli_4 = 0;
$item_list = $conn->query("SELECT * from exp_group where id <= 8;"); 
while($row_item = $item_list->fetch_assoc()){
	$item_code = $row_item['id'];
	$journals = $conn->query("SELECT ((case when sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = '$item_code' and dli_type = 'Non-DLI' group by eg.id;");
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$nondli_4 = $nondli_4 + $row['Crt'];
		   }
		}
	}
}

$nondli_total = $nondli_2 + $nondli_3 + $nondli_4;


////////////D L I

//$journals = $conn->query("SELECT (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 2 and (account_id = 51 and new <> 1) and dli_type = 'DLI';");

//$journals = $conn->query("SELECT (case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 2 and (account_id <> 51 and account_id <> 52 and account_id <> 100 and new <> 1) and dli_type = 'DLI' and journal_type = 'dv';");

$dli_2 = 0;
$dli_total = 0;

$item_list = $conn->query("SELECT * from exp_group where id <= 8;"); 
while($row_item = $item_list->fetch_assoc()){
	$item_code = $row_item['id'];
	$journals = $conn->query("SELECT ((case when sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = '$item_code' and dli_type = 'DLI' group by eg.id;");
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$dli_2 = $dli_2 + $row['Crt'];
		   }
		}
	}
}


//$journals = $conn->query("SELECT (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 3 and (account_id = 51 and new <> 1) and dli_type = 'DLI';");

$journals = $conn->query("SELECT (case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 3 and (account_id <> 51 and account_id <> 52 and account_id <> 100 and new <> 1) and dli_type = 'DLI' and journal_type = 'dv';");


$dli_3 = 0;
$item_list = $conn->query("SELECT * from exp_group where id <= 8;"); 
while($row_item = $item_list->fetch_assoc()){
	$item_code = $row_item['id'];
	$journals = $conn->query("SELECT ((case when sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = '$item_code' and dli_type = 'DLI' group by eg.id;");
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$dli_3 = $dli_3 + $row['Crt'];
		   }
		}
	}
}


//$journals = $conn->query("SELECT (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 4 and (account_id = 51 and new <> 1) and dli_type = 'DLI';");

//$journals = $conn->query("SELECT (case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 4 and (account_id <> 51 and account_id <> 52 and account_id <> 100 and new <> 1) and dli_type = 'DLI' and journal_type = 'dv';");

$dli_4 = 0;

$item_list = $conn->query("SELECT * from exp_group where id <= 8;"); 
while($row_item = $item_list->fetch_assoc()){
	$item_code = $row_item['id'];
	$journals = $conn->query("SELECT ((case when sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = '$item_code' and dli_type = 'DLI' group by eg.id;");
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$dli_4 = $dli_4 + $row['Crt'];
		   }
		}
	}
}


$dli_total = $dli_2 + $dli_3 + $dli_4;

$nondli_dli2 = 0;
$nondli_dli3 = 0;
$nondli_dli4 = 0;

$grand_total = 0;

$nondli_dli2 = $nondli_2 + $dli_2;
$nondli_dli3 = $nondli_3 + $dli_3;
$nondli_dli4 = $nondli_4 + $dli_4;

$grand_total = $nondli_dli2 + $nondli_dli3 + $nondli_dli4;

?>

<hr class="border-border bg-primary">
<div class="row">
	<div class="col-md-6">
	<p style="text-align: center; margin-bottom: -25px; font-size:22px;"><b>Financial Year-wise Expenditures</b></p>
	<p style="text-align: right; margin-bottom: 0px; font-size: 16px;"><i>(Amount in BDT)</i></p>
        <table>
			<thead>
				<tr>
				  <th class="cl1" style="background-color: #d48104; color: white;"><b>Fund Type</b></th>
				  <th class="cl2" style="background-color: #d48104; color: white;"><b>2021-22</b></th>
				  <th class="cl2" style="background-color: #d48104; color: white;"><b>2022-23</b></th>
				  <th class="cl2" style="background-color: #d48104; color: white;"><b>2023-24</b></th>
				  <th class="cl2" style="background-color: #d48104; color: white;"><b>Total</b></th>
				</tr>
			</thead>		  
			<tbody>
				<tr>
				  <td class="cl1" style="background-color: #faf5fc;"><b>Non-DLI</b></td>
				  <td class="cl2" style="background-color: #faf5fc;"><b><?php echo number_format($nondli_2, 2) ?></b></td>
				  <td class="cl2" style="background-color: #faf5fc;"><b><?php echo number_format($nondli_3, 2) ?></b></td>
				  <td class="cl2" style="background-color: #faf5fc;"><b><?php echo number_format($nondli_4, 2) ?></b></td>	  
				  <td class="cl2" style="background-color: #faf5fc;"><b><?php echo number_format($nondli_total, 2) ?></b></td>
				</tr>
				<tr>
				  <td class="cl1" style="background-color: #f2ebf5;"><b>DLI</b></td>
				  <td class="cl2" style="background-color: #f2ebf5;"><b><?php if ($dli_2 <= 0) echo  "--"; else echo number_format($dli_2, 2); ?></b></td>
				  <td class="cl2" style="background-color: #f2ebf5;"><b><?php if ($dli_3 <= 0) echo  "--"; else echo number_format($dli_3, 2); ?></b></td>
				  <td class="cl2" style="background-color: #f2ebf5;"><b><?php if ($dli_4 <= 0) echo  "--"; else echo number_format($dli_4, 2); ?></b></td>
				  <td class="cl2" style="background-color: #f2ebf5;"><b><?php if ($dli_total <= 0) echo  "--"; else echo number_format($dli_total, 2); ?></b></td>
				</tr>
				<tr>
				  <td class="cl1" style="background-color: #ffe6e6;"><b>Total</b></td>
				  <td class="cl2" style="background-color: #ffe6e6;"><b><?php echo number_format($nondli_dli2, 2) ?></b></td>
				  <td class="cl2" style="background-color: #ffe6e6;"><b><?php echo number_format($nondli_dli3, 2) ?></b></td>
				  <td class="cl2" style="background-color: #ffe6e6;"><b><?php echo number_format($nondli_dli4, 2) ?></b></td>
				  <td class="cl2" style="background-color: #ffe6e6;"><b><?php echo number_format($grand_total, 2) ?></b></td>
				</tr>
			</tbody>
	   </table>
    </div>
</div>
<?php 
$item_1_2 = 0;
$item_2_2 = 0;
$item_3_2 = 0;
$item_4_2 = 0;
$item_5_2 = 0;
$item_6_2 = 0;
$item_7_2 = 0;
$item_8_2 = 0;

$item_1_3 = 0;
$item_2_3 = 0;
$item_3_3 = 0;
$item_4_3 = 0;
$item_5_3 = 0;
$item_6_3 = 0;
$item_7_3 = 0;
$item_8_3 = 0;

$item_1_4 = 0;
$item_2_4 = 0;
$item_3_4 = 0;
$item_4_4 = 0;
$item_5_4 = 0;
$item_6_4 = 0;
$item_7_4 = 0;
$item_8_4 = 0;

$item_1_total = 0;
$item_2_total = 0;
$item_3_total = 0;
$item_4_total = 0;
$item_5_total = 0;
$item_6_total = 0;
$item_7_total = 0;
$item_8_total = 0;

$col_1_total = 0;
$col_2_total = 0;
$col_3_total = 0;
$col_4_total = 0;


// for FY2021-22


//((case when sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end))

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 1 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_1_2 = $row['Crt'];
			} else{
				$item_1_2 = 0;
			}
		}
	}else $item_1_2 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 2 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_2_2 = $row['Crt'];
			} else{
				$item_2_2 = 0;
			}
		} 
	}else $item_2_2 = 0;


$journals = $conn->query("SELECT ((case when sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 3 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_3_2 = $row['Crt'];
			} else{
				$item_3_2 = 0;
			}
		}
	} else $item_3_2 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 4 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_4_2 = $row['Crt'];
			} else{
				$item_4_2 = 0;
			}
		}
	} else $item_4_2 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 5 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_5_2 = $row['Crt'];
			} else{
				$item_5_2 = 0;
			}
		}
	} else $item_5_2 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 6 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_6_2 = $row['Crt'];
			} else{
				$item_6_2 = 0;
			}
		}
	} else $item_6_2 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 7 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_7_2 = $row['Crt'];
			} else{
				$item_7_2 = 0;
			}			
		}
	} else $item_7_2 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 8 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_8_2 = $row['Crt'];
			} else{
				$item_8_2 = 0;
			}
		}
	} else $item_8_2 = 0;



// for FY2022-23

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 1 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_1_3 = $row['Crt'];
			} else{
				$item_1_3 = 0;
			}
		}
	}else $item_1_3 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 2 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_2_3 = $row['Crt'];
			} else{
				$item_2_3 = 0;
			}
		} 
	}else $item_2_3 = 0;


$journals = $conn->query("SELECT ((case when sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 3 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_3_3 = $row['Crt'];
			} else{
				$item_3_3 = 0;
			}
		}
	} else $item_3_3 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 4 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_4_3 = $row['Crt'];
			} else{
				$item_4_3 = 0;
			}
		}
	} else $item_4_3 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 5 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_5_3 = $row['Crt'];
			} else{
				$item_5_3 = 0;
			}
		}
	} else $item_5_3 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 6 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_6_3 = $row['Crt'];
			} else{
				$item_6_3 = 0;
			}
		}
	} else $item_6_3 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 7 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_7_3 = $row['Crt'];
			} else{
				$item_7_3 = 0;
			}			
		}
	} else $item_7_3 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 8 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_8_3 = $row['Crt'];
			} else{
				$item_8_3 = 0;
			}
		}
	} else $item_8_3 = 0;
	
	
// for FY2023-24

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 1 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_1_4 = $row['Crt'];
			} else{
				$item_1_4 = 0;
			}
		}
	}else $item_1_4 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 2 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_2_4 = $row['Crt'];
			} else{
				$item_2_4 = 0;
			}
		} 
	}else $item_2_4 = 0;


$journals = $conn->query("SELECT ((case when sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 3 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_3_4 = $row['Crt'];
			} else{
				$item_3_4 = 0;
			}
		}
	} else $item_3_4 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 4 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_4_4 = $row['Crt'];
			} else{
				$item_4_4 = 0;
			}
		}
	} else $item_4_4 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 5 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_5_4 = $row['Crt'];
			} else{
				$item_5_4 = 0;
			}
		}
	} else $item_5_4 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 6 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_6_4 = $row['Crt'];
			} else{
				$item_6_4 = 0;
			}
		}
	} else $item_6_4 = 0;


$item_6_4 = $item_6_4;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 7 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_7_4 = $row['Crt'];
			} else{
				$item_7_4 = 0;
			}			
		}
	} else $item_7_4 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 8 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_8_4 = $row['Crt'];
			} else{
				$item_8_4 = 0;
			}
		}
	} else $item_8_4 = 0;


$item_1_total = $item_1_2 + $item_1_3 + $item_1_4;
$item_2_total = $item_2_2 + $item_2_3 + $item_2_4;
$item_3_total = $item_3_2 + $item_3_3 + $item_3_4;
$item_4_total = $item_4_2 + $item_4_3 + $item_4_4;
$item_5_total = $item_5_2 + $item_5_3 + $item_5_4;
$item_6_total = $item_6_2 + $item_6_3 + $item_6_4;
$item_7_total = $item_7_2 + $item_7_3 + $item_7_4;
$item_8_total = $item_8_2 + $item_8_3 + $item_8_4;


$col_1_total = $item_1_2 + $item_2_2 + $item_3_2 + $item_4_2 + $item_5_2 + $item_6_2 + $item_7_2 + $item_8_2;
$col_2_total = $item_1_3 + $item_2_3 + $item_3_3 + $item_4_3 + $item_5_3 + $item_6_3 + $item_7_3 + $item_8_3;
$col_3_total = $item_1_4 + $item_2_4 + $item_3_4 + $item_4_4 + $item_5_4 + $item_6_4 + $item_7_4 + $item_8_4;

$col_4_total = $col_1_total + $col_2_total + $col_3_total;

?>

<hr class="border-border bg-primary">
<div class="row">
	<div class="col-md-6">
	<p style="text-align: center; margin-bottom: -25px; font-size:20px;"><b>FY-wise Expenditures by Main Groups (Non-DLI)</b></p>
	<p style="text-align: right; margin-bottom: 0px; font-size: 16px;"><i>(Amount in BDT)</i></p>
        <table>
			<thead>
				<tr>
				  <th class="cl3" style="background-color: #d48104; color: white;"><b>Group Name</b></th>
				  <th class="cl4" style="background-color: #d48104; color: white;"><b>2021-22</b></th>
				  <th class="cl4" style="background-color: #d48104; color: white;"><b>2022-23</b></th>
				  <th class="cl4" style="background-color: #d48104; color: white;"><b>2023-24</b></th>
				  <th class="cl4" style="background-color: #d48104; color: white;"><b>Total</b></th>
				</tr>
			</thead>		  
			<tbody>
				<tr>
				  <td class="cl3" style="background-color: #faf5fc;"><b>a) Goods</b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php echo number_format($item_1_2, 2) ?></b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php echo number_format($item_1_3, 2) ?></b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php echo number_format($item_1_4, 2) ?></b></td>	  
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php echo number_format($item_1_total, 2) ?></b></td>
				</tr>
				<tr>
				  <td class="cl3" style="background-color: #f2ebf5;"><b>b) Works</b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php if ($item_2_2 <= 0) echo  "--"; else echo number_format($item_2_2, 2); ?></b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php echo number_format($item_2_3, 2) ?></b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php if ($item_2_4 <= 0) echo  "--"; else echo number_format($item_2_4, 2); ?></b></td>	  
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php echo number_format($item_2_total, 2) ?></b></td>
				</tr>
				<tr>
				  <td class="cl3" style="background-color: #faf5fc;"><b>c) Consultants' Services</b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php echo number_format($item_3_2, 2) ?></b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php echo number_format($item_3_3, 2) ?></b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php echo number_format($item_3_4, 2) ?></b></td>	  
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php echo number_format($item_3_total, 2) ?></b></td>
				</tr>
				<tr>
				  <td class="cl3" style="background-color: #f2ebf5;"><b>d) Non-Consulting Services</b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php echo number_format($item_4_2, 2) ?></b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php echo number_format($item_4_3, 2) ?></b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php echo number_format($item_4_4, 2) ?></b></td>	  
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php echo number_format($item_4_total, 2) ?></b></td>
				</tr>
				<tr>
				  <td class="cl3" style="background-color: #faf5fc;"><b>e) Training, Workshop</b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php echo number_format($item_5_2, 2) ?></b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php echo number_format($item_5_3, 2) ?></b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php echo number_format($item_5_4, 2) ?></b></td>	  
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php echo number_format($item_5_total, 2) ?></b></td>
				</tr>
				<tr>
				  <td class="cl3" style="background-color: #f2ebf5;"><b>f) Operating Costs</b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php echo number_format($item_6_2, 2) ?></b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php echo number_format($item_6_3, 2) ?></b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php echo number_format($item_6_4, 2) ?></b></td>	  
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php echo number_format($item_6_total, 2) ?></b></td>
				</tr>
				<tr>
				  <td class="cl3" style="background-color: #f4f5f2;" colspan="5"><b>g) Grants under Part 3.2(b)</b></td>
				  
				</tr>
				<tr>
				  <td class="cl3" style="background-color: #faf5fc;"><b>&nbsp;&nbsp;&nbsp;&nbsp;(i) Voucher Program</b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php if ($item_7_2 <= 0) echo  "--"; else echo number_format($item_7_2, 2); ?></b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php if ($item_7_3 <= 0) echo  "--"; else echo number_format($item_7_3, 2); ?></b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php if ($item_7_4 <= 0) echo  "--"; else echo number_format($item_7_4, 2); ?></b></td>	  
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php if ($item_7_total <= 0) echo  "--"; else echo number_format($item_7_total, 2); ?></b></td>
				</tr>
				<tr>
				  <td class="cl3" style="background-color: #f2ebf5;"><b>&nbsp;&nbsp;&nbsp;(ii) Grant Program</b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php if ($item_8_2 <= 0) echo  "--"; else echo number_format($item_8_2, 2); ?></b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php if ($item_8_3 <= 0) echo  "--"; else echo number_format($item_8_3, 2); ?></b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php if ($item_8_4 <= 0) echo  "--"; else echo number_format($item_8_4, 2); ?></b></td>	  
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php if ($item_8_total <= 0) echo  "--"; else echo number_format($item_8_total, 2); ?></b></td>
				</tr>
				<tr>
				  <td class="cl3" style="background-color: #ffe6e6;"><b>Total</b></td>
				  <td class="cl4" style="background-color: #ffe6e6;"><b><?php echo number_format($col_1_total, 2) ?></b></td>
				  <td class="cl4" style="background-color: #ffe6e6;"><b><?php echo number_format($col_2_total, 2) ?></b></td>
				  <td class="cl4" style="background-color: #ffe6e6;"><b><?php echo number_format($col_3_total, 2) ?></b></td>
				  <td class="cl4" style="background-color: #ffe6e6;"><b><?php echo number_format($col_4_total, 2) ?></b></td>
				</tr>
			</tbody>
	   </table>
    </div>	




<!---         DLI Part by Main Group   --->

<?php 
$item_1_2_d = 0;
$item_2_2_d = 0;
$item_3_2_d = 0;
$item_4_2_d = 0;
$item_5_2_d = 0;
$item_6_2_d = 0;
$item_7_2_d = 0;
$item_8_2_d = 0;

$item_1_3_d = 0;
$item_2_3_d = 0;
$item_3_3_d = 0;
$item_4_3_d = 0;
$item_5_3_d = 0;
$item_6_3_d = 0;
$item_7_3_d = 0;
$item_8_3_d = 0;

$item_1_4_d = 0;
$item_2_4_d = 0;
$item_3_4_d = 0;
$item_4_4_d = 0;
$item_5_4_d = 0;
$item_6_4_d = 0;
$item_7_4_d = 0;
$item_8_4_d = 0;

$item_1_total_d = 0;
$item_2_total_d = 0;
$item_3_total_d = 0;
$item_4_total_d = 0;
$item_5_total_d = 0;
$item_6_total_d = 0;
$item_7_total_d = 0;
$item_8_total_d = 0;

$col_1_total_d = 0;
$col_2_total_d = 0;
$col_3_total_d = 0;
$col_4_total_d = 0;


// for FY2021-22 

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 1 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_1_2_d = $row['Crt'];
			} else{
				$item_1_2_d = 0;
			}
		}
	}else $item_1_2_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 2 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_2_2_d = $row['Crt'];
			} else{
				$item_2_2_d = 0;
			}
		} 
	}else $item_2_2_d = 0;


$journals = $conn->query("SELECT ((case when sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 3 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_3_2_d = $row['Crt'];
			} else{
				$item_3_2_d = 0;
			}
		}
	} else $item_3_2_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 4 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_4_2_d = $row['Crt'];
			} else{
				$item_4_2_d = 0;
			}
		}
	} else $item_4_2_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 5 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_5_2_d = $row['Crt'];
			} else{
				$item_5_2_d = 0;
			}
		}
	} else $item_5_2_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 6 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_6_2_d = $row['Crt'];
			} else{
				$item_6_2_d = 0;
			}
		}
	} else $item_6_2_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 7 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_7_2_d = $row['Crt'];
			} else{
				$item_7_2_d = 0;
			}			
		}
	} else $item_7_2_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 8 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_8_2_d = $row['Crt'];
			} else{
				$item_8_2_d = 0;
			}
		}
	} else $item_8_2_d = 0;



// for FY2022-23

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 1 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_1_3_d = $row['Crt'];
			} else{
				$item_1_3_d = 0;
			}
		}
	}else $item_1_3_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 2 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_2_3_d = $row['Crt'];
			} else{
				$item_2_3_d = 0;
			}
		} 
	}else $item_2_3_d = 0;


$journals = $conn->query("SELECT ((case when sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 3 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_3_3_d = $row['Crt'];
			} else{
				$item_3_3_d = 0;
			}
		}
	} else $item_3_3_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 4 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_4_3_d = $row['Crt'];
			} else{
				$item_4_3_d = 0;
			}
		}
	} else $item_4_3_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 5 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_5_3_d = $row['Crt'];
			} else{
				$item_5_3_d = 0;
			}
		}
	} else $item_5_3_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 6 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_6_3_d = $row['Crt'];
			} else{
				$item_6_3_d = 0;
			}
		}
	} else $item_6_3_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 7 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_7_3_d = $row['Crt'];
			} else{
				$item_7_3_d = 0;
			}			
		}
	} else $item_7_3_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 8 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_8_3_d = $row['Crt'];
			} else{
				$item_8_3_d = 0;
			}
		}
	} else $item_8_3_d = 0;
	
	
// for FY2023-24

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 1 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_1_4_d = $row['Crt'];
			} else{
				$item_1_4_d = 0;
			}
		}
	}else $item_1_4_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 2 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_2_4_d = $row['Crt'];
			} else{
				$item_2_4_d = 0;
			}
		} 
	}else $item_2_4_d = 0;


$journals = $conn->query("SELECT ((case when sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 3 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_3_4_d = $row['Crt'];
			} else{
				$item_3_4_d = 0;
			}
		}
	} else $item_3_4_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 4 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_4_4_d = $row['Crt'];
			} else{
				$item_4_4_d = 0;
			}
		}
	} else $item_4_4_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 5 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_5_4_d = $row['Crt'];
			} else{
				$item_5_4_d = 0;
			}
		}
	} else $item_5_4_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 6 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_6_4_d = $row['Crt'];
			} else{
				$item_6_4_d = 0;
			}
		}
	} else $item_6_4_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 7 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_7_4_d = $row['Crt'];
			} else{
				$item_7_4_d = 0;
			}			
		}
	} else $item_7_4_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 8 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_8_4_d = $row['Crt'];
			} else{
				$item_8_4_d = 0;
			}
		}
	} else $item_8_4_d = 0;


$item_1_total_d = $item_1_2_d + $item_1_3_d + $item_1_4_d;
$item_2_total_d = $item_2_2_d + $item_2_3_d + $item_2_4_d;
$item_3_total_d = $item_3_2_d + $item_3_3_d + $item_3_4_d;
$item_4_total_d = $item_4_2_d + $item_4_3_d + $item_4_4_d;
$item_5_total_d = $item_5_2_d + $item_5_3_d + $item_5_4_d;
$item_6_total_d = $item_6_2_d + $item_6_3_d + $item_6_4_d;
$item_7_total_d = $item_7_2_d + $item_7_3_d + $item_7_4_d;
$item_8_total_d = $item_8_2_d + $item_8_3_d + $item_8_4_d;


$col_1_total_d = $item_1_2_d + $item_2_2_d + $item_3_2_d + $item_4_2_d + $item_5_2_d + $item_6_2_d + $item_7_2_d + $item_8_2_d;
$col_2_total_d = $item_1_3_d + $item_2_3_d + $item_3_3_d + $item_4_3_d + $item_5_3_d + $item_6_3_d + $item_7_3_d + $item_8_3_d;
$col_3_total_d = $item_1_4_d + $item_2_4_d + $item_3_4_d + $item_4_4_d + $item_5_4_d + $item_6_4_d + $item_7_4_d + $item_8_4_d;

$col_4_total_d = $col_1_total_d + $col_2_total_d + $col_3_total_d;

?>


	<div class="col-md-6">
	<p style="text-align: center; margin-bottom: -25px; font-size:20px;"><b>FY-wise Expenditures by Main Groups (DLI)</b></p>
	<p style="text-align: right; margin-bottom: 0px; font-size: 16px;"><i>(Amount in BDT)</i></p>
        <table>
			<thead>
				<tr>
				  <th class="cl3" style="background-color: #d48104; color: white;"><b>Group Name</b></th>
				  <th class="cl4" style="background-color: #d48104; color: white;"><b>2021-22</b></th>
				  <th class="cl4" style="background-color: #d48104; color: white;"><b>2022-23</b></th>
				  <th class="cl4" style="background-color: #d48104; color: white;"><b>2023-24</b></th>
				  <th class="cl4" style="background-color: #d48104; color: white;"><b>Total</b></th>
				</tr>
			</thead>		  
			<tbody>
				<tr>
				  <td class="cl3" style="background-color: #faf5fc;"><b>a) Goods</b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php if ($item_1_2_d <= 0) echo  "--"; else echo number_format($item_1_2_d, 2); ?></b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php if ($item_1_3_d <= 0) echo  "--"; else echo number_format($item_1_3_d, 2); ?></b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php if ($item_1_4_d <= 0) echo  "--"; else echo number_format($item_1_4_d, 2); ?></b></td>	  
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php if ($item_1_total_d <= 0) echo  "--"; else echo number_format($item_1_total_d, 2); ?></b></td>
				</tr>
				<tr>
				  <td class="cl3" style="background-color: #f2ebf5;"><b>b) Works</b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php if ($item_2_2_d <= 0) echo  "--"; else echo number_format($item_2_2_d, 2); ?></b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php if ($item_2_3_d <= 0) echo  "--"; else echo number_format($item_2_3_d, 2); ?></b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php if ($item_2_4_d <= 0) echo  "--"; else echo number_format($item_2_4_d, 2); ?></b></td>	  
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php if ($item_2_total_d <= 0) echo  "--"; else echo number_format($item_2_total_d, 2); ?></b></td>
				</tr>
				<tr>
				  <td class="cl3" style="background-color: #faf5fc;"><b>c) Consultants' Services</b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php if ($item_3_2_d <= 0) echo  "--"; else echo number_format($item_3_2_d, 2); ?></b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php if ($item_3_3_d <= 0) echo  "--"; else echo number_format($item_3_3_d, 2); ?></b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php if ($item_3_4_d <= 0) echo  "--"; else echo number_format($item_3_4_d, 2); ?></b></td>	  
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php if ($item_3_total_d <= 0) echo  "--"; else echo number_format($item_3_total_d, 2); ?></b></td>
				</tr>
				<tr>
				  <td class="cl3" style="background-color: #f2ebf5;"><b>d) Non-Consulting Services</b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php if ($item_4_2_d <= 0) echo  "--"; else echo number_format($item_4_2_d, 2); ?></b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php if ($item_4_3_d <= 0) echo  "--"; else echo number_format($item_4_3_d, 2); ?></b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php if ($item_4_4_d <= 0) echo  "--"; else echo number_format($item_4_4_d, 2); ?></b></td>	  
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php if ($item_4_total_d <= 0) echo  "--"; else echo number_format($item_4_total_d, 2); ?></b></td>
				</tr>
				<tr>
				  <td class="cl3" style="background-color: #faf5fc;"><b>e) Training, Workshop</b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php if ($item_5_2_d <= 0) echo  "--"; else echo number_format($item_5_2_d, 2); ?></b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php if ($item_5_3_d <= 0) echo  "--"; else echo number_format($item_5_3_d, 2); ?></b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php if ($item_5_4_d <= 0) echo  "--"; else echo number_format($item_5_4_d, 2); ?></b></td>	  
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php  if ($item_5_total_d <= 0) echo  "--"; else echo number_format($item_5_total_d, 2); ?></b></td>
				</tr>
				<tr>
				  <td class="cl3" style="background-color: #f2ebf5;"><b>f) Operating Costs</b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php if ($item_6_2_d <= 0) echo  "--"; else echo number_format($item_6_2_d, 2); ?></b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php if ($item_6_3_d <= 0) echo  "--"; else echo number_format($item_6_3_d, 2); ?></b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php if ($item_6_4_d <= 0) echo  "--"; else echo number_format($item_6_4_d, 2); ?></b></td>	  
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php if ($item_6_total_d <= 0) echo  "--"; else echo number_format($item_6_total_d, 2); ?></b></td>
				</tr>
				<tr>
				  <td class="cl3" style="background-color: #f4f5f2;" colspan="5"><b>g) Grants under Part 3.2(b)</b></td>
				  
				</tr>
				<tr>
				  <td class="cl3" style="background-color: #faf5fc;"><b>&nbsp;&nbsp;&nbsp;&nbsp;(i) Voucher Program</b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php if ($item_7_2_d <= 0) echo  "--"; else echo number_format($item_7_2_d, 2); ?></b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php if ($item_7_3_d <= 0) echo  "--"; else echo number_format($item_7_3_d, 2); ?></b></td>
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php if ($item_7_4_d <= 0) echo  "--"; else echo number_format($item_7_4_d, 2); ?></b></td>	  
				  <td class="cl4" style="background-color: #faf5fc;"><b><?php if ($item_7_total_d <= 0) echo  "--"; else echo number_format($item_7_total_d, 2); ?></b></td>
				</tr>
				<tr>
				  <td class="cl3" style="background-color: #f2ebf5;"><b>&nbsp;&nbsp;&nbsp;(ii) Grant Program</b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php if ($item_8_2_d <= 0) echo  "--"; else echo number_format($item_8_2_d, 2); ?></b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php if ($item_8_3_d <= 0) echo  "--"; else echo number_format($item_8_3_d, 2); ?></b></td>
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php if ($item_8_4_d <= 0) echo  "--"; else echo number_format($item_8_4_d, 2); ?></b></td>	  
				  <td class="cl4" style="background-color: #f2ebf5;"><b><?php if ($item_8_total_d <= 0) echo  "--"; else echo number_format($item_8_total_d, 2); ?></b></td>
				</tr>
				<tr>
				  <td class="cl3" style="background-color: #ffe6e6;"><b>Total</b></td>
				  <td class="cl4" style="background-color: #ffe6e6;"><b><?php if ($col_1_total_d <= 0) echo  "--"; else echo number_format($col_1_total_d, 2); ?></b></td>
				  <td class="cl4" style="background-color: #ffe6e6;"><b><?php if ($col_2_total_d <= 0) echo  "--"; else echo number_format($col_2_total_d, 2); ?></b></td>
				  <td class="cl4" style="background-color: #ffe6e6;"><b><?php if ($col_3_total_d <= 0) echo  "--"; else echo number_format($col_3_total_d, 2); ?></b></td>
				  <td class="cl4" style="background-color: #ffe6e6;"><b><?php if ($col_4_total_d <= 0) echo  "--"; else echo number_format($col_4_total_d, 2); ?></b></td>
				</tr>
			</tbody>
	   </table>
    </div>
</div>	



<!---  Combined Dli and Non-DLI     -->
<?php

$item_1_total_d = $item_1_total_d + $item_1_total;
$item_2_total_d = $item_2_total_d + $item_2_total;
$item_3_total_d = $item_3_total_d + $item_3_total;
$item_4_total_d = $item_4_total_d + $item_4_total;
$item_5_total_d = $item_5_total_d + $item_5_total;
$item_6_total_d = $item_6_total_d + $item_6_total;
$item_7_total_d = $item_7_total_d + $item_7_total;
$item_8_total_d = $item_8_total_d + $item_8_total;


$col_1_total = $item_1_2 + $item_2_2 + $item_3_2 + $item_4_2 + $item_5_2 + $item_6_2 + $item_7_2 + $item_8_2;
$col_1_total_d = $item_1_2_d + $item_2_2_d + $item_3_2_d + $item_4_2_d + $item_5_2_d + $item_6_2_d + $item_7_2_d + $item_8_2_d;
$col2 = $col_1_total + $col_1_total_d;

$col_2_total = $item_1_3 + $item_2_3 + $item_3_3 + $item_4_3 + $item_5_3 + $item_6_3 + $item_7_3 + $item_8_3;
$col_2_total_d = $item_1_3_d + $item_2_3_d + $item_3_3_d + $item_4_3_d + $item_5_3_d + $item_6_3_d + $item_7_3_d + $item_8_3_d;
$col3 = $col_2_total + $col_2_total_d;

$col_3_total = $item_1_4 + $item_2_4 + $item_3_4 + $item_4_4 + $item_5_4 + $item_6_4 + $item_7_4 + $item_8_4;
$col_3_total_d = $item_1_4_d + $item_2_4_d + $item_3_4_d + $item_4_4_d + $item_5_4_d + $item_6_4_d + $item_7_4_d + $item_8_4_d;
$col4 = $col_3_total + $col_3_total_d;

$col_total = $col2 + $col3 + $col4;

?>

<style>

.c1 {
	width: 20%;
	text-align: left;
}
.c2 {
	width: 10%;
	text-align: right;
}

</style>

<hr class="border-border bg-primary">
<div class="row">
	<div class="col-md-12">
	<p style="text-align: center; margin-bottom: -25px; font-size:22px;"><b>Financial Year-wise Expenditures by Main Groups</b></p>
	<p style="text-align: right; margin-bottom: 0px; font-size: 16px;"><i>(Amount in BDT)</i></p>
        <table>
			<thead>
				<tr>
				  <th class="c1" rowspan="2" style="background-color: #d48104; color: white; font-size: 16px;"><b>Group Name</b></th>
				  <th colspan="3" style="background-color: #d48104; color: white; font-size: 16px; text-align: center;"><b>2021-22</b></th>
				  <th colspan="3" style="background-color: #d48104; color: white; font-size: 16px; text-align: center;"><b>2022-23</b></th>
				  <th colspan="3" style="background-color: #d48104; color: white; font-size: 16px; text-align: center;"><b>2023-24</b></th>
				  <th class="c2" rowspan="2" style="background-color: #d48104; color: white; font-size: 16px; text-align: right;"><b>Total</b></th>
				</tr>
				<tr>
				  <th class="c2" style="background-color: #d48104; color: white; font-size: 16px; text-align: right;"><b>Non-DLI</b></th>
				  <th class="c2" style="background-color: #d48104; color: white; font-size: 16px; text-align: right;"><b>DLI</b></th>
				  <th class="c2" style="background-color: #d48104; color: white; font-size: 16px; text-align: right;"><b>Total</b></th>
				  
				  <th class="c2" style="background-color: #d48104; color: white; font-size: 16px; text-align: right;"><b>Non-DLI</b></th>			  
				  <th class="c2" style="background-color: #d48104; color: white; font-size: 16px; text-align: right;"><b>DLI</b></th>
				  <th class="c2" style="background-color: #d48104; color: white; font-size: 16px; text-align: right;"><b>Total</b></th>
				  
				  <th class="c2" style="background-color: #d48104; color: white; font-size: 16px; text-align: right;"><b>Non-DLI</b></th>
				  <th class="c2" style="background-color: #d48104; color: white; font-size: 16px; text-align: right;"><b>DLI</b></th>
				  <th class="c2" style="background-color: #d48104; color: white; font-size: 16px; text-align: right;"><b>Total</b></th>
				</tr>
			</thead>		  
			<tbody>
				<tr>
				  <td class="c1" style="background-color: #faf5fc; font-size: 16px;"><b>a) Goods</b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php echo number_format($item_1_2, 2) ?></b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php if ($item_1_2_d <= 0) echo  "--"; else echo number_format($item_1_2_d, 2); ?></b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php echo number_format(($item_1_2 + $item_1_2_d), 2) ?></b></td>
				  
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php echo number_format($item_1_3, 2) ?></b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php if ($item_1_3_d <= 0) echo  "--"; else echo number_format($item_1_3_d, 2); ?></b></td>
				  
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php if (($item_1_3 + $item_1_3_d) <= 0) echo  "--"; else echo number_format(($item_1_3 + $item_1_3_d), 2); ?></b></td>
				  
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php echo number_format($item_1_4, 2) ?></b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php if ($item_1_4_d <= 0) echo  "--"; else echo number_format($item_1_4_d, 2); ?></b></td>	  
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php if (($item_1_4 + $item_1_4_d) <= 0) echo  "--"; else echo number_format(($item_1_4 + $item_1_4_d), 2); ?></b></td>
				  
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php if ($item_1_total_d <= 0) echo  "--"; else echo number_format($item_1_total_d, 2); ?></b></td>
				</tr>
				<tr>
				  <td class="c1" style="background-color: #f2ebf5; font-size: 16px;"><b>b) Works</b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php if ($item_2_2 <= 0) echo  "--"; else echo number_format($item_2_2, 2); ?></b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php if ($item_2_2_d <= 0) echo  "--"; else echo number_format($item_2_2_d, 2); ?></b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php if (($item_2_2 +  $item_2_2_d) <= 0) echo  "--"; else echo number_format(($item_2_2 +  $item_2_2_d), 2); ?></b></td>
				  
				  <td class="c2" class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php echo number_format($item_2_3, 2) ?></b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php echo number_format($item_2_3_d, 2) ?></b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php echo number_format(($item_2_3 + $item_2_3_d), 2) ?></b></td>
				  
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php if ($item_2_4 <= 0) echo  "--"; else echo number_format($item_2_4, 2); ?></b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php if ($item_2_4_d <= 0) echo  "--"; else echo number_format($item_2_4_d, 2); ?></b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php if (($item_2_4 + $item_2_4_d) <= 0) echo  "--"; else echo number_format(($item_2_4 + $item_2_4_d), 2);  ?></b></td>
				  
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php echo number_format($item_2_total_d, 2) ?></b></td>
				</tr>
				<tr>
				  <td class="c1" style="background-color: #faf5fc; font-size: 16px;"><b>c) Consultants' Services</b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php echo number_format($item_3_2, 2) ?></b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php if ($item_3_2_d <= 0) echo  "--"; else echo number_format($item_3_2_d, 2); ?></b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php echo number_format(($item_3_2 + $item_3_2_d), 2) ?></b></td>
				  
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php echo number_format($item_3_3, 2) ?></b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php if ($item_3_3_d <= 0) echo  "--"; else echo number_format($item_3_3_d, 2); ?></b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php echo number_format(($item_3_3 + $item_3_3_d), 2) ?></b></td>
				  
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php echo number_format($item_3_4, 2) ?></b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php if ($item_3_4_d <= 0) echo  "--"; else echo number_format($item_3_4_d, 2); ?></b></td>
				  <td class="c2" style="background-color: #faf5fc;font-size: 16px;"><b><?php echo number_format(($item_3_4 + $item_3_4_d), 2) ?></b></td>
				  
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php echo number_format($item_3_total_d, 2) ?></b></td>
				</tr>
				<tr>
				  <td class="c1" style="background-color: #f2ebf5; font-size: 16px;"><b>d) Non-Consulting Services</b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php echo number_format($item_4_2, 2) ?></b></td>
				  <td class="c2" style="background-color: #f2ebf5;font-size: 16px;"><b><?php if ($item_4_2_d <= 0) echo  "--"; else echo number_format($item_4_2_d, 2); ?></b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php echo number_format(($item_4_2 + $item_4_2_d), 2) ?></b></td>
				  
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php echo number_format($item_4_3, 2) ?></b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php if ($item_4_4_d <= 0) echo  "--"; else echo number_format($item_4_4_d, 2); ?></b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php echo number_format(($item_4_3 + $item_4_3_d), 2) ?></b></td>
				  
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php echo number_format($item_4_4, 2) ?></b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php if ($item_4_4_d <= 0) echo  "--"; else echo number_format($item_4_4_d, 2); ?></b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php echo number_format(($item_4_4 + $item_4_4_d), 2) ?></b></td>
				  
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php echo number_format($item_4_total_d, 2) ?></b></td>
				</tr>
				<tr>
				  <td class="c1" style="background-color: #faf5fc; font-size: 16px;"><b>e) Training, Workshop</b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php echo number_format($item_5_2, 2) ?></b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php if ($item_5_2_d <= 0) echo  "--"; else echo number_format($item_5_2_d, 2); ?></b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php echo number_format(($item_5_2 + $item_5_2_d), 2) ?></b></td>
				  
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php echo number_format($item_5_3, 2) ?></b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php if ($item_5_3_d <= 0) echo  "--"; else echo number_format($item_5_3_d, 2); ?></b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php echo number_format(($item_5_3 + $item_5_3_d), 2) ?></b></td>
				  
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php echo number_format($item_5_4, 2) ?></b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php if ($item_5_4_d <= 0) echo  "--"; else echo number_format($item_5_4_d, 2); ?></b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php echo number_format(($item_5_4 + $item_5_4_d), 2) ?></b></td>
				  
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php echo number_format($item_5_total_d, 2) ?></b></td>
				</tr>
				<tr>
				  <td class="c1" style="background-color: #f2ebf5; font-size: 16px;"><b>f) Operating Costs</b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php echo number_format($item_6_2, 2) ?></b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php if ($item_6_2_d <= 0) echo  "--"; else echo number_format($item_6_2_d, 2); ?></b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php echo number_format(($item_6_2 + $item_6_2_d), 2) ?></b></td>
				  
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php echo number_format($item_6_3, 2) ?></b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php if ($item_6_3_d <= 0) echo  "--"; else echo number_format($item_6_3_d, 2); ?></b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php echo number_format(($item_6_3 + $item_6_3_d), 2) ?></b></td>
				  
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php echo number_format($item_6_4, 2) ?></b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php if ($item_6_4_d <= 0) echo  "--"; else echo number_format($item_6_4_d, 2); ?></b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php echo number_format(($item_6_4 + $item_6_4_d), 2) ?></b></td>
				  
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php echo number_format($item_6_total_d, 2) ?></b></td>
				</tr>
				<tr>
				  <td style="background-color: #f4f5f2; font-size: 16px;" colspan="11"><b>g) Grants under Part 3.2(b)</b></td>
				  
				</tr>
				<tr>
				  <td class="c1" style="background-color: #faf5fc; font-size: 16px;"><b>&nbsp;&nbsp;&nbsp;&nbsp;(i) Voucher Program</b></td>
				  
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php if ($item_7_2 <= 0) echo  "--"; else echo number_format($item_7_2, 2); ?></b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php if ($item_7_2_d <= 0) echo  "--"; else echo number_format($item_7_2_d, 2); ?></b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php if (($item_7_2 + $item_7_2_d) <= 0) echo  "--"; else echo number_format(($item_7_2 + $item_7_2_d), 2); ?></b></td>
				  
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php if ($item_7_3 <= 0) echo  "--"; else echo number_format($item_7_3, 2); ?></b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php if ($item_7_3_d <= 0) echo  "--"; else echo number_format($item_7_3_d, 2); ?></b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php if (($item_7_3 + $item_7_3_d) <= 0) echo  "--"; else echo number_format(($item_7_3 + $item_7_3_d), 2); ?></b></td>
				  
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php if ($item_7_4 <= 0) echo  "--"; else echo number_format($item_7_4, 2); ?></b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php if ($item_7_4_d <= 0) echo  "--"; else echo number_format($item_7_4_d, 2); ?></b></td>
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php if (($item_7_4 + $item_7_4_d) <= 0) echo  "--"; else echo number_format(($item_7_4 + $item_7_4_d), 2); ?></b></td>
				  
				  <td class="c2" style="background-color: #faf5fc; font-size: 16px;"><b><?php if ($item_7_total_d <= 0) echo  "--"; else echo number_format($item_7_total_d, 2); ?></b></td>
				</tr>
				<tr>
				  <td class="c1" style="background-color: #f2ebf5; font-size: 16px;"><b>&nbsp;&nbsp;&nbsp;(ii) Grant Program</b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php if ($item_8_2 <= 0) echo  "--"; else echo number_format($item_8_2, 2);?></b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php if ($item_8_2_d <= 0) echo  "--"; else echo number_format($item_8_2_d, 2); ?></b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php if (($item_8_2 + $item_8_2_d) <= 0) echo  "--"; else echo number_format(($item_8_2 + $item_8_2_d), 2);?></b></td>
				  
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php if ($item_8_3 <= 0) echo  "--"; else echo number_format($item_8_3, 2); ?></b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php if ($item_8_3_d <= 0) echo  "--"; else echo number_format($item_8_3_d, 2); ?></b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php if (($item_8_3 + $item_8_3_d) <= 0) echo  "--"; else echo number_format(($item_8_3 + $item_8_3_d), 2); ?></b></td>
				  
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php if ($item_8_4 <= 0) echo  "--"; else echo number_format($item_8_4, 2); ?></b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php if ($item_8_4_d <= 0) echo  "--"; else echo number_format($item_8_4_d, 2); ?></b></td>
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php if (($item_8_4 + $item_8_4_d) <= 0) echo  "--"; else echo number_format(($item_8_4 + $item_8_4_d), 2); ?></b></td>
				  
				  <td class="c2" style="background-color: #f2ebf5; font-size: 16px;"><b><?php if ($item_8_total_d <= 0) echo  "--"; else echo number_format($item_8_total_d, 2); ?></b></td>
				</tr>
				<tr>
				  <td class="c1" style="background-color: #ffe6e6; font-size: 16px;"><b>Total</b></td>
				  <td class="c2" style="background-color: #ffe6e6; font-size: 16px;"><b><?php echo number_format($col_1_total, 2) ?></b></td>
				  <td class="c2" style="background-color: #ffe6e6; font-size: 16px;"><b><?php if ($col_1_total_d <= 0) echo  "--"; else echo number_format($col_1_total_d, 2); ?></b></td>
				  <td class="c2" style="background-color: #ffe6e6; font-size: 16px;"><b><?php echo number_format($col2, 2) ?></b></td>
				  
				  <td class="c2" style="background-color: #ffe6e6; font-size: 16px;"><b><?php echo number_format($col_2_total, 2) ?></b></td>
				  <td class="c2" style="background-color: #ffe6e6; font-size: 16px;"><b><?php echo number_format($col_2_total_d, 2) ?></b></td>
				  <td class="c2" style="background-color: #ffe6e6; font-size: 16px;"><b><?php echo number_format($col3, 2) ?></b></td>
				  
				  <td class="c2" style="background-color: #ffe6e6; font-size: 16px;"><b><?php echo number_format($col_3_total, 2) ?></b></td>
				  <td class="c2" style="background-color: #ffe6e6; font-size: 16px;"><b><?php if ($col_3_total_d <= 0) echo  "--"; else echo number_format($col_3_total_d, 2); ?></b></td>
				  <td class="c2" style="background-color: #ffe6e6; font-size: 16px;"><b><?php echo number_format($col4, 2) ?></b></td>
				  <td class="c2" style="background-color: #ffe6e6; font-size: 16px;"><b><?php echo number_format($col_total, 2) ?></b></td>
				</tr>
			</tbody>
	   </table>
    </div>
</div>

<hr class="border-border bg-primary">
