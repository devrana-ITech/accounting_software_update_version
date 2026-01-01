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
}
if (($fy_id == 2) && ($qtr_id == 2)){
	$start = '2021-10-01';
	$end = '2021-12-31';
	$qtr_name = 'Oct-Dec, 2021';
}
if (($fy_id == 2) && ($qtr_id == 3)){
	$start = '2022-01-01';
	$end = '2022-03-31';
	$qtr_name = 'Jan-Mar, 2022';
}
if (($fy_id == 2) && ($qtr_id == 4)){
	$start = '2022-04-01';
	$end = '2022-06-30';
	$qtr_name = 'Apr-Jun, 2022';
}

if (($fy_id == 3) && ($qtr_id == 1)){
	$start = '2022-07-01';
	$end = '2022-09-30';
	$qtr_name = 'July-Sep, 2022';
}
if (($fy_id == 3) && ($qtr_id == 2)){
	$start = '2022-10-01';
	$end = '2022-12-31';
	$qtr_name = 'Oct-Dec, 2022';
}
if (($fy_id == 3) && ($qtr_id == 3)){
	$start = '2023-01-01';
	$end = '2023-03-31';
	$qtr_name = 'Jan-Mar, 2023';
}
if (($fy_id == 3) && ($qtr_id == 4)){
	$start = '2023-04-01';
	$end = '2023-06-30';
	$qtr_name = 'Apr-Jun, 2023';
}

if (($fy_id == 4) && ($qtr_id == 1)){
	$start = '2023-07-01';
	$end = '2023-09-30';
	$qtr_name = 'July-Sep, 2023';
}
if (($fy_id == 4) && ($qtr_id == 2)){
	$start = '2023-10-01';
	$end = '2023-12-31';
	$qtr_name = 'Oct-Dec, 2023';
}
if (($fy_id == 4) && ($qtr_id == 3)){
	$start = '2024-01-01';
	$end = '2024-03-31';
	$qtr_name = 'Jan-Mar, 2024';
}
if (($fy_id == 4) && ($qtr_id == 4)){
	$start = '2024-04-01';
	$end = '2024-06-30';
	$qtr_name = 'Apr-Jun, 2024';
}

if (($fy_id == 5) && ($qtr_id == 1)){
	$start = '2024-07-01';
	$end = '2024-09-30';
	$qtr_name = 'July-Sep, 2024';
}
if (($fy_id == 5) && ($qtr_id == 2)){
	$start = '2024-10-01';
	$end = '2024-12-31';
	$qtr_name = 'Oct-Dec, 2024';
}
if (($fy_id == 5) && ($qtr_id == 3)){
	$start = '2025-01-01';
	$end = '2025-03-31';
	$qtr_name = 'Jan-Mar, 2025';
}
if (($fy_id == 5) && ($qtr_id == 4)){
	$start = '2025-04-01';
	$end = '2025-06-30';
	$qtr_name = 'Apr-Jun, 2025';
}

if (($fy_id == 6) && ($qtr_id == 1)){
	$start = '2025-07-01';
	$end = '2025-09-30';
	$qtr_name = 'July-Sep, 2025';
}
if (($fy_id == 6) && ($qtr_id == 2)){
	$start = '2025-10-01';
	$end = '2025-12-31';
	$qtr_name = 'Oct-Dec, 2025';
}
if (($fy_id == 6) && ($qtr_id == 3)){
	$start = '2026-01-01';
	$end = '2026-03-31';
	$qtr_name = 'Jan-Mar, 2026';
}
if (($fy_id == 6) && ($qtr_id == 4)){
	$start = '2026-04-01';
	$end = '2026-06-30';
	$qtr_name = 'Apr-Jun, 2026';
}

if ($fy_id == 6)
	$start_ytd = '2025-07-01';

if ($fy_id == 5)
	$start_ytd = '2024-07-01';

if ($fy_id == 2)
	$start_ytd = '2021-07-01';
if ($fy_id == 3)
	$start_ytd = '2022-07-01';
if ($fy_id == 4)
	$start_ytd = '2023-07-01';


$total_qtr_actual_current = 0;
$total_qtr_planned_current = 0;
$total_qtr_var_current = 0;

$total_qtr_actual_ytd = 0;
$total_qtr_planned_ytd = 0;
$total_qtr_var_ytd = 0;

$total_qtr_actual_cum = 0;
$total_qtr_planned_cum = 0;
$total_qtr_var_cum = 0;

$var_current = 0;
$var_ytd = 0;
$var_cum = 0;

$current_qtr = 0;
$current_ytd = 0;
$current_cum = 0;

$total_pad_amount = 0;

$pdf = new \Mpdf\Mpdf([
				'mode' => 'utf-8',
				'format' => 'A4',
				'orientation' => 'L',
				'margin_header' => '6',
				'margin_top' => '32',
				'margin_bottom' => '12',
				'margin_footer' => '8',
				'deafult_font_size' => 8,
				'default_font' => 'nikosh'
			]);

$data = "";

$comp_list = $conn->query("SELECT * from tbl_component where id <= 3;");
while($row_item = $comp_list->fetch_assoc()){
	$comp_name = $row_item['com_name'];
	$comp_code = $row_item['id'];
	
	//$journals = $conn->query("SELECT (case when sum(case when tc.id = '$comp_code' and group_id = 1 then jt.amount end) is null then 0 else sum(case when tc.id = '$comp_code' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id inner join tbl_component tc on tc.id = je.component_number WHERE journal_date >= '$start' and journal_date <= '$end' and je.component_number = '$comp_code' and dli_type = '$d_type' and journal_type = 'dv' group by je.component_number;");
	
	$journals = $conn->query("SELECT jt.amount as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id inner join tbl_component tc on tc.id = je.component_number WHERE journal_date >= '$start' and journal_date <= '$end' and je.component_number = '$comp_code' and dli_type = '$d_type' and journal_type = 'dv' and group_id = 1;");
	
	//SELECT al.name, jt.amount from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id inner join tbl_component tc on tc.id = je.component_number WHERE journal_date >= '2023-04-01' and journal_date <= '2023-06-30' and je.component_number = 1 and dli_type = 'Non-DLI' and journal_type = 'dv' and group_id = 1 and exp_group <> 2 and exp_group <> 9; and exp_group <> 2 and exp_group <> 9
	
	//$journals = $conn->query("SELECT (case when sum(case when je.exp_group = '$item_code' and group_id = 1 then jt.amount end) is null then 0 else sum(case when je.exp_group = '$item_code' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id WHERE journal_date >= '$start' and journal_date <= '$end' and je.exp_group = '$item_code' and dli_type = '$d_type' group by je.exp_group;");
	
	
	$amt = 0;
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   $amt = $amt + $row['Crt'];
		}
		   if ($amt != 0) {
				$data .= '<tr><td class="cl1" style="padding: 3px;">'.$comp_name.'</td>';
				$data .= '<td class="cl2" style="padding: 3px;">'.number_format($amt/1000000, 2).'</td>';
				$total_qtr_actual_current = $total_qtr_actual_current + $amt;
				$current_qtr = $amt/1000000;
		   }else{
				$data .= '<tr><td class="cl1" style="padding: 3px;">'.$comp_name.'</td>';
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$current_qtr = 0;
			}
	}else{
		$data .= '<tr><td class="cl1" style="padding: 3px;">'.$comp_name.'</td>';
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
		$current_qtr = 0;
	}
	$journals = $conn->query("SELECT al.name, je.dli_type, (case when sum(case when tc.id = '$comp_code' and group_id = 1 then jt.amount end) is null then 0 else sum(case when tc.id = '$comp_code' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id inner join tbl_component tc on tc.id = je.component_number WHERE journal_date >= '$start_ytd' and journal_date <= '$end' and tc.id = '$comp_code' and dli_type = '$d_type' and  journal_type = 'dv' group by tc.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td>';
				$total_qtr_actual_ytd = $total_qtr_actual_ytd + $row['Crt'];
				$current_ytd = $row['Crt']/1000000;
				
		   }else{
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$current_ytd = 0;
			}
		}
	}else{
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
		$current_ytd = 0;
	}
	$journals = $conn->query("SELECT al.name, je.dli_type, (case when sum(case when tc.id = '$comp_code' and group_id = 1 then jt.amount end) is null then 0 else sum(case when tc.id = '$comp_code' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id inner join tbl_component tc on tc.id = je.component_number WHERE journal_date <= '$end' and tc.id = '$comp_code' and dli_type = '$d_type' and journal_type = 'dv' group by tc.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td>';
				$total_qtr_actual_cum = $total_qtr_actual_cum + $row['Crt'];
				$current_cum = $row['Crt']/1000000;
		   }else{
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$current_cum = 0;
			}
		}
	}else{
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
		$current_cum = 0;
	}
	
	
	
	////////////////////Planned current Qtr 
	$journals = $conn->query("SELECT pl_amount from `pl_fund` WHERE fy_id = '$fy_id' and qtr_id = '$qtr_id' and comp_id = '$comp_code' and fund_type = '$d_type';");
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['pl_amount'] != 0) {
				$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['pl_amount'], 2).'</td>';
				$total_qtr_planned_current = $total_qtr_planned_current + $row['pl_amount'];
				$var_current = $row['pl_amount'] - $current_qtr;
				$total_qtr_var_current = $total_qtr_var_current + $var_current;
		   }else{
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$var_current = 0;
			}
		}
	}else{
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
		$var_current = 0;
	}
	
	////////////////////Planned YTD
	$journals = $conn->query("SELECT sum(pl_amount) as pl_fund from `pl_fund` WHERE fy_id = '$fy_id' and qtr_id <= '$qtr_id' and comp_id = '$comp_code' and fund_type = '$d_type' group by comp_id;");
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['pl_fund'] != 0) {
				$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['pl_fund'], 2).'</td>';
				$total_qtr_planned_ytd = $total_qtr_planned_ytd + $row['pl_fund'];
				$var_ytd = $row['pl_fund'] - $current_ytd;
				$total_qtr_var_ytd = $total_qtr_var_ytd + $var_ytd;
		   }else{
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$var_ytd = 0;
			}
		}
	}else{
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
		$var_ytd = 0;
	}
	
	////////////////////Planned Cumulative
	$journals = $conn->query("SELECT sum(pl_amount) as pl_fund from `pl_fund` WHERE (((fy_id < '$fy_id') or  (fy_id = '$fy_id' and qtr_id <= '$qtr_id')) and (comp_id = '$comp_code') and (fund_type = '$d_type')) group by comp_id;");
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['pl_fund'] != 0) {
				$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['pl_fund'], 2).'</td>';
				$total_qtr_planned_cum = $total_qtr_planned_cum + $row['pl_fund'];
				$var_cum = $row['pl_fund'] - $current_cum;
				$total_qtr_var_cum = $total_qtr_var_cum + $var_cum;
		   }else{
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$var_cum = 0;
			}
		}
	}else{
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
		$var_cum = 0;
	}
	
	///////////////Variance
	if ($var_current > 0){
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format($var_current, 2).'</td>';
	}else {
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	}
	
	if ($var_ytd > 0){
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format($var_ytd, 2).'</td>';
	}else {
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	}
	
	if ($var_cum > 0){
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format($var_cum, 2).'</td>';
	}else {
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	}
	
	
	
	///////////PAD
	$journals = $conn->query("SELECT pad_amount from `pad_fund` WHERE comp_id = '$comp_code';");
	while($row = $journals->fetch_assoc()){
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['pad_amount'], 2).'</td></tr>';
		$total_pad_amount = $total_pad_amount + $row['pad_amount'];
	}
}

$data .= '<tr><td class="cl1" style="padding: 3px;"><b>Total</b></td>';
if ($total_qtr_actual_current > 0){
	$data .= '<td class="cl2" style="padding: 3px;"><b>'.number_format($total_qtr_actual_current/1000000, 2).'</b></td>';
}else {
	$data .= '<td class="cl2" style="padding: 3px;"><b>--</b></td>';
}
if ($total_qtr_actual_ytd > 0){
	$data .= '<td class="cl2" style="padding: 3px;"><b>'.number_format($total_qtr_actual_ytd/1000000, 2).'</b></td>';
}else {
	$data .= '<td class="cl2" style="padding: 3px;"><b>--</b></td>';
}
if ($total_qtr_actual_cum > 0){
	$data .= '<td class="cl2" style="padding: 3px;"><b>'.number_format($total_qtr_actual_cum/1000000, 2).'</b></td>';
}else {
	$data .= '<td class="cl2" style="padding: 3px;"><b>--</b></td>';
}
if ($total_qtr_planned_current > 0){
	$data .= '<td class="cl2" style="padding: 3px;"><b>'.number_format($total_qtr_planned_current, 2).'</b></td>';
}else {
	$data .= '<td class="cl2" style="padding: 3px;"><b>--</b></td>';
}
if ($total_qtr_planned_ytd > 0){
	$data .= '<td class="cl2" style="padding: 3px;"><b>'.number_format($total_qtr_planned_ytd, 2).'</b></td>';
}else {
	$data .= '<td class="cl2" style="padding: 3px;"><b>--</b></td>';
}
if ($total_qtr_planned_cum > 0){
	$data .= '<td class="cl2" style="padding: 3px;"><b>'.number_format($total_qtr_planned_cum, 2).'</b></td>';
}else {
	$data .= '<td class="cl2" style="padding: 3px;"><b>--</b></td>';
}
if ($total_qtr_var_current > 0){
	$data .= '<td class="cl2" style="padding: 3px;"><b>'.number_format($total_qtr_var_current, 2).'</b></td>';
}else {
	$data .= '<td class="cl2" style="padding: 3px;"><b>--</b></td>';
}
if ($total_qtr_var_ytd > 0){
	$data .= '<td class="cl2" style="padding: 3px;"><b>'.number_format($total_qtr_var_ytd, 2).'</b></td>';
}else {
	$data .= '<td class="cl2" style="padding: 3px;"><b>--</b></td>';
}
if ($total_qtr_var_cum > 0){
	$data .= '<td class="cl2" style="padding: 3px;"><b>'.number_format($total_qtr_var_cum, 2).'</b></td>';
}else {
	$data .= '<td class="cl2" style="padding: 3px;"><b>--</b></td>';
}
if ($total_pad_amount > 0){
	$data .= '<td class="cl2" style="padding: 3px;"><b>'.number_format($total_pad_amount, 2).'</b></td></tr>';
}else {
	$data .= '<td class="cl2" style="padding: 3px;"><b>--</b></td></tr>';
}

$header = '<!--mpdf
<htmlpageheader name="letterheader">
	<div style="font-size: 10pt; text-align: center; padding-top: 1mm; font-family: nikosh; ">
		<h3 style="margin-bottom: -1px;">National Special Economic Zone (NSEZ) Development Project</h3>
		<h4 style="margin-top: -5px; margin-bottom: -1px;">Credit No. IDA-6676 BD ('.$d_type.' Part)</h4>
		<h4 style="margin-top: -5px; margin-bottom: -1px;">1B. Uses of Funds by Project Activity</h4>
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
	width: 20%;
	text-align: left;
}
.cl2 {
	width: 10%;
	text-align: right;
}
.cl3 {
	width: 10%;
	text-align: right;
}

.cl4 {
	width: 10%;
	text-align: right;
}

.cl5 {
	width: 10%;
	text-align: center;
}

table, th, td {
  border: 1px solid #d9d0f7;
  border-collapse: collapse;
  width: 100%;
  padding: 5px;
  font-size: 13px;
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
  <br>
  <p style="text-align: right; margin-bottom: 2px; font-size: 11px;"><i>(Amount in BDT Million)</i></p>
	  <table>
		<thead>
		<tr>
		  <th class="cl1" style="background-color: #f3e3fa;" rowspan="2"><b>Project Activities</b></th>
		  <th style="background-color: #f3e3fa;" colspan="3"><b>Actual</b></th>
		  <th style="background-color: #f3e3fa;" colspan="3"><b>Planned</b></th>
		  <th style="background-color: #f3e3fa;" colspan="3"><b>Varriance</b></th>
		  <th class="cl4" style="background-color: #f3e3fa; text-align: center;" rowspan="2"><b>PAD Fund of Project (Miilion USD)</b></th>
		</tr>
		<tr>
		  <th class="cl2" style="background-color: #f3e3fa; text-align: center;"><b>Current Quarter</b></th>
		  <th class="cl3" style="background-color: #f3e3fa; text-align: center;"><b>Year-To-Date</b></th>
		  <th class="cl2" style="background-color: #f3e3fa; text-align: center;"><b>Cumulative To-Date</b></th>
		  <th class="cl2" style="background-color: #f3e3fa; text-align: center;"><b>Current Quarter</b></th>
		  <th class="cl3" style="background-color: #f3e3fa; text-align: center;"><b>Year-To-Date</b></th>
		  <th class="cl2" style="background-color: #f3e3fa; text-align: center;"><b>Cumulative To-Date</b></th>
		  <th class="cl2" style="background-color: #f3e3fa; text-align: center;"><b>Current Quarter</b></th>
		  <th class="cl3" style="background-color: #f3e3fa; text-align: center;"><b>Year-To-Date</b></th>
		  <th class="cl2" style="background-color: #f3e3fa; text-align: center;"><b>Cumulative To-Date</b></th>
		</tr>
		</thead>		  
		<tbody>
		'.$data.'
		</tbody>
	   </table>

<br><br><br><br>
<p style="margin-bottom: 2px; font-size: 11px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Financial Management Specialist&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Project Director</p>
</body>
</html>';
$pdf->WriteHTML($header);
$pdf->WriteHTML($html);
$pdf->Output('');
exit;
?>

    