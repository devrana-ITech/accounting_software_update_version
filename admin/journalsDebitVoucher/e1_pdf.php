<?php	

require_once('./../../config.php');

require('../../vendor/autoload.php');

function format_num($number){
	$decimals = 0;
	$num_ex = explode('.',$number);
	$decimals = isset($num_ex[1]) ? strlen($num_ex[1]) : 2 ;
	return number_format($number,$decimals);
}



$fy_id = $_POST['year_id'];
$qtr_id = $_POST['qtr_id'];
$d_type = $_POST['d_type'];

if (($fy_id == 2) && ($qtr_id == 1)){
	$start = '2021-07-01';
	$end = '2021-09-30';
	$qtr_name = 'July-Sep, 2021';
	
	$f_c1_q_name = 'Oct-Dec, 2021';
	$f_c2_q_name = 'Jan-Mar, 2022';
	$f_combined_q_name = 'Oct 2021 to Mar 2022';
}
if (($fy_id == 2) && ($qtr_id == 2)){
	$start = '2021-10-01';
	$end = '2021-12-31';
	$qtr_name = 'Oct-Dec, 2021';
	
	$f_c1_q_name = 'Jan-Mar, 2022';
	$f_c2_q_name = 'Apr-Jun, 2022';
	$f_combined_q_name = 'Jan 2022 to Jun 2022';
}
if (($fy_id == 2) && ($qtr_id == 3)){
	$start = '2022-01-01';
	$end = '2022-03-31';
	$qtr_name = 'Jan-Mar, 2022';
	
	$f_c1_q_name = 'Apr-Jun, 2022';
	$f_c2_q_name = 'Jul-Sep, 2022';
	$f_combined_q_name = 'Apr 2022 to Sep 2022';
}
if (($fy_id == 2) && ($qtr_id == 4)){
	$start = '2022-04-01';
	$end = '2022-06-30';
	$qtr_name = 'Apr-Jun, 2022';
	
	$f_c1_q_name = 'Jul-Sep, 2022';
	$f_c2_q_name = 'Oct-Dec, 2022';
	$f_combined_q_name = 'Jul 2022 to Dec 2022';
}

if (($fy_id == 3) && ($qtr_id == 1)){
	$start = '2022-07-01';
	$end = '2022-09-30';
	$qtr_name = 'July-Sep, 2022';
	
	$f_c1_q_name = 'Oct-Dec, 2022';
	$f_c2_q_name = 'Jan-Mar, 2023';
	$f_combined_q_name = 'Oct 2022 to Mar 2023';
}

if (($fy_id == 3) && ($qtr_id == 2)){
	$start = '2022-10-01';
	$end = '2022-12-31';
	$qtr_name = 'Oct-Dec, 2022';
	
	$f_c1_q_name = 'Jan-Mar, 2023';
	$f_c2_q_name = 'Apr-Jun, 2023';
	$f_combined_q_name = 'Jan 2023 to Jun 2023';
}
if (($fy_id == 3) && ($qtr_id == 3)){
	$start = '2023-01-01';
	$end = '2023-03-31';
	$qtr_name = 'Jan-Mar, 2023';
	
	$f_c1_q_name = 'Apr-Jun, 2023';
	$f_c2_q_name = 'Jul-Sep, 2023';
	$f_combined_q_name = 'Apr 2023 to Sep 2023';
}
if (($fy_id == 3) && ($qtr_id == 4)){
	$start = '2023-04-01';
	$end = '2023-06-30';
	$qtr_name = 'Apr-Jun, 2023';
	
	$f_c1_q_name = 'Jul-Sep, 2023';
	$f_c2_q_name = 'Oct-Dec, 2023';
	$f_combined_q_name = 'Jul 2023 to Dec 2023';
}

if (($fy_id == 4) && ($qtr_id == 1)){
	$start = '2023-07-01';
	$end = '2023-09-30';
	$qtr_name = 'July-Sep, 2023';
	
	$f_c1_q_name = 'Oct-Dec, 2023';
	$f_c2_q_name = 'Jan-Mar, 2024';
	$f_combined_q_name = 'Oct 2023 to Mar 2024';	
}

if (($fy_id == 4) && ($qtr_id == 2)){
	$start = '2023-10-01';
	$end = '2023-12-31';
	$qtr_name = 'Oct-Dec, 2023';
	
	$f_c1_q_name = 'Jan-Mar, 2024';
	$f_c2_q_name = 'Apr-Jun, 2024';
	$f_combined_q_name = 'Jan 2024 to Jun 2024';
}
if (($fy_id == 4) && ($qtr_id == 3)){
	$start = '2024-01-01';
	$end = '2024-03-31';
	$qtr_name = 'Jan-Mar, 2024';
	
	$f_c1_q_name = 'Apr-Jun, 2024';
	$f_c2_q_name = 'Jul-Sep, 2024';
	$f_combined_q_name = 'Apr 2024 to Sep 2024';
}
if (($fy_id == 4) && ($qtr_id == 4)){
	$start = '2024-04-01';
	$end = '2024-06-30';
	$qtr_name = 'Apr-Jun, 2024';
	
	$f_c1_q_name = 'Jul-Sep, 2024';
	$f_c2_q_name = 'Oct-Dec, 2024';
	$f_combined_q_name = 'Jul 2024 to Dec 2024';
}

//////
if (($fy_id == 5) && ($qtr_id == 1)){
	$start = '2024-07-01';
	$end = '2024-09-30';
	$qtr_name = 'July-Sep, 2024';
	
	$f_c1_q_name = 'Oct-Dec, 2024';
	$f_c2_q_name = 'Jan-Mar, 2025';
	$f_combined_q_name = 'Oct 2023 to Mar 2024';	
}

if (($fy_id == 5) && ($qtr_id == 2)){
	$start = '2024-10-01';
	$end = '2024-12-31';
	$qtr_name = 'Oct-Dec, 2024';
	
	$f_c1_q_name = 'Jan-Mar, 2025';
	$f_c2_q_name = 'Apr-Jun, 2025';
	$f_combined_q_name = 'Jan 2025 to Jun 2025';
}
if (($fy_id == 5) && ($qtr_id == 3)){
	$start = '2025-01-01';
	$end = '2025-03-31';
	$qtr_name = 'Jan-Mar, 2025';
	
	$f_c1_q_name = 'Apr-Jun, 2025';
	$f_c2_q_name = 'Jul-Sep, 2025';
	$f_combined_q_name = 'Apr 2025 to Sep 2025';
}
if (($fy_id == 5) && ($qtr_id == 4)){
	$start = '2025-04-01';
	$end = '2025-06-30';
	$qtr_name = 'Apr-Jun, 2025';
	
	$f_c1_q_name = 'Jul-Sep, 2025';
	$f_c2_q_name = 'Oct-Dec, 2025';
	$f_combined_q_name = 'Jul 2025 to Dec 2025';
}


if (($fy_id == 6) && ($qtr_id == 1)){
	$start = '2025-07-01';
	$end = '2025-09-30';
	$qtr_name = 'July-Sep, 2025';
	
	$f_c1_q_name = 'Oct-Dec, 2025';
	$f_c2_q_name = 'Jan-Mar, 2026';
	$f_combined_q_name = 'Oct 2025 to Mar 2026';
}
if (($fy_id == 6) && ($qtr_id == 2)){
	$start = '2025-10-01';
	$end = '2025-12-31';
	$qtr_name = 'Oct-Dec, 2025';
	
	$f_c1_q_name = 'Jan-Mar, 2026';
	$f_c2_q_name = 'Apr-Jun, 2026';
	$f_combined_q_name = 'Jan 2026 to Jun 2026';
}
if (($fy_id == 6) && ($qtr_id == 3)){
	$start = '2026-01-01';
	$end = '2026-03-31';
	$qtr_name = 'Jan-Mar, 2026';
	
	$f_c1_q_name = 'Apr-Jun, 2026';
	$f_c2_q_name = 'Jul-Sep, 2026';
	$f_combined_q_name = 'Apr 2026 to Sep 2026';
}
if (($fy_id == 6) && ($qtr_id == 4)){
	$start = '2026-04-01';
	$end = '2026-06-30';
	$qtr_name = 'Apr-Jun, 2026';
	
	$f_c1_q_name = 'Jul-Sep, 2026';
	$f_c2_q_name = 'Oct-Dec, 2026';
	$f_combined_q_name = 'Jul 2026 to Dec 2026';
}




//////


$qtr1 = 0;
$qtr2 = 0;
$year = 0;
$year2 = 0;

If ($qtr_id == 1){
	$qtr1 = 2;
	$qtr2 = 3;
	$year = $fy_id;
}

If ($qtr_id == 2){
	$qtr1 = 3;
	$qtr2 = 4;
	$year = $fy_id;
}

If ($qtr_id == 3){
	$qtr1 = 4;
	$qtr2 = 1;
	$year = $fy_id;
	$year2 = $fy_id + 1;
}

If ($qtr_id == 4){
	$qtr1 = 1;
	$qtr2 = 2;
	$year = $fy_id + 1;
}


$pdf = new \Mpdf\Mpdf([
				'mode' => 'utf-8',
				'format' => 'A4',
				'orientation' => 'P',
				'margin_header' => '15',
				'margin_top' => '42',
				'margin_bottom' => '12',
				'margin_footer' => '8',
				'deafult_font_size' => 8,
				'default_font' => 'nikosh'
			]);

$data = "";

//$journals = $conn->query("SELECT *, tbl_category.id as id from cashforecast inner join tbl_category on tbl_category.id = cashforecast.category WHERE qtr = '$qtr1' and year = '$year' and fund_type = '$d_type' and tbl_category.id <= 3 order by tbl_category.id asc;");
//and tbl_category.id <= 3 if ($journals->num_rows > 0){


$c1_total = 0;
$c2_total = 0;
$c3_total = 0;
$r1_total = 0;
$c1 = 0;
$c2 = 0;

$journals = $conn->query("SELECT * from tbl_category where id <= 3 order by id asc;");
while($cat_row = $journals->fetch_assoc()){
	$data .= '<tr><td class="cl1" style ="padding: 3px;">'.$cat_row['categoryname'].'</td>';
	$cat_id = $cat_row['id'];
	$r1_total = 0;
	$c1_total = 0;
	$c2_total = 0;
	if ((($qtr_id >= 1) && ($qtr_id <= 2)) || ($qtr_id == 4)) {
		$journals1 = $conn->query("SELECT * from cashforecast WHERE qtr = '$qtr1' and year = '$year' and fund_type = '$d_type' and category = '$cat_id';");
		if ($journals1->num_rows > 0){
			while($row1 = $journals1->fetch_assoc()){
				$data .= '<td class="cl2" style="padding: 3px; text-align: right;">'.number_format($row1['fund_amt'], 2, '.', ',').'</td>';
				$c1_total =  $c1_total + $row1['fund_amt'];
				$c1 = $c1 + $row1['fund_amt'];
			}
		} else $data .= '<td class="cl2" style="padding: 3px; text-align: right;">--</td>';
			
		$journals2 = $conn->query("SELECT * from cashforecast WHERE qtr = '$qtr2' and year = '$year' and fund_type = '$d_type' and category = '$cat_id';");
		if ($journals2->num_rows > 0){
			while($row2 = $journals2->fetch_assoc()){
				$data .= '<td class="cl2" style="padding: 3px; text-align: right;">'.number_format($row2['fund_amt'], 2, '.', ',').'</td>';
				$c2_total =  $c2_total + $row2['fund_amt'];
				$c2 = $c2 + $row2['fund_amt'];
			}
		} else $data .= '<td class="cl2" style="padding: 3px; text-align: right;">--</td>';	
		
		$r1_total =  $c1_total + $c2_total;
		$data .= '<td class="cl3" style="padding: 3px; text-align: right;">'.number_format($r1_total, 2, '.', ',').'</td></tr>';
		$c3_total =  $c3_total + $r1_total;
	} else if ($qtr_id == 3) {
		$journals1 = $conn->query("SELECT * from cashforecast WHERE qtr = '$qtr1' and year = '$year' and fund_type = '$d_type' and category = '$cat_id';");
		if ($journals1->num_rows > 0){
			while($row1 = $journals1->fetch_assoc()){
				$data .= '<td class="cl2" style="padding: 3px; text-align: right;">'.number_format($row1['fund_amt'], 2, '.', ',').'</td>';
				$c1_total =  $c1_total + $row1['fund_amt'];
				$c1 = $c1 + $row1['fund_amt'];
			}
		} else $data .= '<td class="cl2" style="padding: 3px; text-align: right;">--</td>';
			
		$journals2 = $conn->query("SELECT * from cashforecast WHERE qtr = '$qtr2' and year = '$year2' and fund_type = '$d_type' and category = '$cat_id';");
		if ($journals2->num_rows > 0){
			while($row2 = $journals2->fetch_assoc()){
				$data .= '<td class="cl2" style="padding: 3px; text-align: right;">'.number_format($row2['fund_amt'], 2, '.', ',').'</td>';
				$c2_total =  $c2_total + $row2['fund_amt'];
				$c2 = $c2 + $row2['fund_amt'];
			}
		} else $data .= '<td class="cl2" style="padding: 3px; text-align: right;">--</td>';	
		
		$r1_total =  $c1_total + $c2_total;
		$data .= '<td class="cl3" style="padding: 3px; text-align: right;">'.number_format($r1_total, 2, '.', ',').'</td></tr>';
		$c3_total =  $c3_total + $r1_total;
	} /* else if ($qtr_id == 4) {
		$journals1 = $conn->query("SELECT * from cashforecast WHERE qtr = '$qtr1' and year = '$year2' and fund_type = '$d_type' and category = '$cat_id';");
		if ($journals1->num_rows > 0){
			while($row1 = $journals1->fetch_assoc()){
				$data .= '<td class="cl2" style="padding: 3px; text-align: right;">'.number_format($row1['fund_amt'], 2, '.', ',').'</td>';
				$c1_total =  $c1_total + $row1['fund_amt'];
			}
		} else $data .= '<td class="cl2" style="padding: 3px; text-align: right;">--</td>';
			
		$journals2 = $conn->query("SELECT * from cashforecast WHERE qtr = '$qtr2' and year = '$year2' and fund_type = '$d_type' and category = '$cat_id';");
		if ($journals2->num_rows > 0){
			while($row2 = $journals2->fetch_assoc()){
				$data .= '<td class="cl2" style="padding: 3px; text-align: right;">'.number_format($row2['fund_amt'], 2, '.', ',').'</td>';
				$c2_total =  $c2_total + $row2['fund_amt'];
			}
		} else $data .= '<td class="cl2" style="padding: 3px; text-align: right;">--</td>';	
		
		$r1_total =  $c1_total + $c2_total;
		$data .= '<td class="cl3" style="padding: 3px; text-align: right;">'.number_format($r1_total, 2, '.', ',').'</td></tr>';
		$c3_total =  $c3_total + $r1_total;
	} */
}

$data .= '<tr><td class="cl1" style="padding: 3px;"><b>Total IDA</b></td>';	
$data .= '<td class="cl2" style="padding: 3px; text-align: right;"><b>'.number_format($c1, 2, '.', ',').'</b></td>';
$data .= '<td class="cl2" style="padding: 3px; text-align: right;"><b>'.number_format($c2, 2, '.', ',').'</b></td>';
$data .= '<td class="cl3" style="padding: 3px; text-align: right;"><b>'.number_format($c3_total, 2, '.', ',').'</b></td></tr>';	

//End of first table

$advances = 0;
$expenses = 0;
$da_bal = 0;
$req_amt = 0;

$journals = $conn->query("SELECT (case when sum(case when (account_id = 51 or account_id = 52) and new = 1 and group_id = 1 then jt.amount end) is null then 0 else sum(case when (account_id = 51 or account_id = 52) and new = 1 and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date <= '$end' and (account_id = 51 or account_id = 52) and new = 1 and dli_type = '$d_type';");

if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$advances = $advances + $row['Crt'];
	   }
	}
}


$journals = $conn->query("SELECT (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date <= '$end' and (account_id = 51 or account_id = 52) and dli_type = '$d_type';");

if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$expenses = $expenses + $row['Crt'];
	   }
	}
}

$da_bal = $advances - $expenses;
$req_amt = $c3_total*1000000 - $da_bal;

$req = number_format($req_amt/1000000, 2, '.', ',');
$data_bal = "";
if ($d_type == "DLI") {
	if ($req > 0){
		
		$data_bal .= '<tr><td style="width: 80%; text-align: left;">Direct payment to Contractor</td>
				<td style="width: 20%; text-align: right;">'.number_format($req_amt/1000000, 2, '.', ',').'</td>
				</tr>
				<tr><td style="width: 80%; text-align: left;">Required amount for next six months</td>
				<td style="width: 20%; text-align: right;">--</td>
				</tr>
				<tr><td style="width: 80%; text-align: left;"><b>Requested amount to be advanced to DA</b></td>
				<td style="width: 20%; text-align: right;">--</td>
				</tr>';
		
	} else {
		$data_bal .= '<tr><td style="width: 80%; text-align: left;">Required amount for next six months</td>
				<td style="width: 20%; text-align: right;">--</td>
				</tr>
				<tr><td style="width: 80%; text-align: left;"><b>Requested amount to be advanced to DA</b></td>
				<td style="width: 20%; text-align: right;">--</td>
				</tr>';
	}
}

if ($d_type == "Non-DLI") {
	if ($req > 0){
		
		$data_bal .= '<tr><td style="width: 80%; text-align: left;">Required amount for next six months</td>
				<td style="width: 20%; text-align: right;">'.number_format($req_amt/1000000, 2, '.', ',').'</td>
				</tr>
				<tr><td style="width: 80%; text-align: left;"><b>Requested amount to be advanced to DA</b></td>
				<td style="width: 20%; text-align: right;">'.number_format($req_amt/1000000, 2, '.', ',').'</td>
				</tr>';
		
	} else {
		$data_bal .= '<tr><td style="width: 80%; text-align: left;">Required amount for next six months</td>
				<td style="width: 20%; text-align: right;">--</td>
				</tr>
				<tr><td style="width: 80%; text-align: left;"><b>Requested amount to be advanced to DA</b></td>
				<td style="width: 20%; text-align: right;">--</td>
				</tr>';
	}
}	


$header = '<!--mpdf
<htmlpageheader name="letterheader">
	<div style="font-size: 10pt; text-align: center; padding-top: 1mm; font-family: nikosh; ">
		<h3 style="margin-bottom: -1px;">National Special Economic Zone (NSEZ) Development Project</h3>
		<h4 style="margin-top: -5px; margin-bottom: -1px;">Credit No. IDA-6676 BD ('.$d_type.' Part)</h4>
		<h4 style="margin-top: -5px; margin-bottom: -1px;">1E. Projected Cash Forecast Statements</h4>
		<h4 style="margin-top: -5px; margin-bottom: -1px; border-bottom: 1px solid #000000;">For the Reporting Quarter: '.$qtr_name.'</h4>
	</div>
</htmlpageheader>
<htmlpagefooter name="letterfooter2">
	<div class="container" style="border-top: 1px solid #000000; font-size: 9pt; font-style: italic; padding-top: 1mm; font-family: sans-serif; ">
		<div class="column" style="text-align: left">
			BSMSN Development Project
		</div>
		<div class="column" style="text-align: center">
			Page {PAGENO} of {nbpg}
		</div>
		
		<div class="column" style="text-align: right">
			{DATE j-m-Y}
		</div>
	</div>
</htmlpagefooter>
mpdf-->

<style>
     @page {
				footer: html_letterfooter2;
			}
		  
			@page :first {
				text-align: center;
				header: html_letterheader;
				footer: _blank;
				resetpagenum: 1;
			}
			.column {
			  float: left;
			  width: 33.33%;
			 /* font-family: nikosh; */
			}
</style>';



$html = '
<html><head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body{font-family: nikosh}
.cl1 {
	width: 30%;
	text-align: left;
}
.cl2 {
	width: 20%;
	text-align: center;
}
.cl3 {
	width: 30%;
	text-align: center;
}

table, th, td {
  border: 1px solid #d9d0f7;
  border-collapse: collapse;
  width: 100%;
  padding: 5px;
  font-size: 11px;
}

.row {
  margin-left: 0px;
  margin-right: -20px;
}
/*  
.column {
  float: left;
  width: 49%;
  padding: 0px;
}
*/

/* Clearfix (clear floats) */
.row::after {
  content: "";
  clear: both;
  display: table;
}

</style>
  </head>
  <body>
  <p style="text-align: right; margin-bottom: 2px; font-size: 11px;"><i>(BDT in Million)</i></p>
	  <table>
		<thead>
		<tr>
		  <th class="cl1" style="background-color: #f3e3fa;" rowspan="4"><b>Disbursement Category</b></th>
		  <th style="background-color: #f3e3fa;" colspan="3"><b>In Taka</b></th>
		</tr>
		<tr>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>(a)</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>(b)</b></th>
		  <th class="cl3" style="background-color: #f3e3fa;"><b>(c)</b></th>
		</tr>
		<tr>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>Cash</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>Cash</b></th>
		  <th class="cl3" style="background-color: #f3e3fa;"><b>IDA eligible total cash</b></th>
		</tr>
		<tr>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>'.$f_c1_q_name.'</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>'.$f_c2_q_name.'</b></th>
		  <th class="cl3" style="background-color: #f3e3fa;"><b>'.$f_combined_q_name.'</b></th>
		</tr>
		</thead>		  
		<tbody>
		'.$data.'
		</tbody>
	   </table>

<br>
 <p style="text-align: center; margin-bottom: 2px; font-size: 12px;"><b>Statement of Required Advance to Special Account</b></p>
<br>
 <p style="text-align: right; margin-bottom: 2px; font-size: 11px;"><i>(BDT in Million)</i></p>
  <table>
		<thead>
		<tr>
		  <th style="background-color: #f3e3fa; width: 80%;"><b>Particulars</b></th>
		  <th style="background-color: #f3e3fa; width: 20%;"><b>BDT</b></th>
		</tr>
		</thead>
		<tbody>
			<tr><td style="width: 80%; text-align: left;">Projected IDA eligible expenditure for the next six months</td>
			<td style="width: 20%; text-align: right;">'.number_format($c3_total, 2, '.', ',').'</td>
			</tr>
			<tr><td style="width: 80%; text-align: left;">Less: Closing DA Balance</td>
			<td style="width: 20%; text-align: right;">'.number_format($da_bal/1000000, 2, '.', ',').'</td>
			</tr>
			<tr><td style="width: 80%; text-align: left;">Less: Advance Received to DA Account</td>
			<td style="width: 20%; text-align: right;">--</td>
			</tr>
			<tr><td style="width: 80%; text-align: left;">Less: Unadjusted Advance</td>
			<td style="width: 20%; text-align: right;">--</td>
			</tr>
			'.$data_bal.'
		</tbody>
  </table>
<br><br><br>
<p style="margin-bottom: 2px; font-size: 11px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Financial Management Specialist&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Project Director</p>
</body>
</html>';
$pdf->WriteHTML($header);
$pdf->WriteHTML($html);
$pdf->Output('');
exit;
?>