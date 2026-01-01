<?php	

require_once('./../../config.php');

require('../../vendor/autoload.php');

function format_num($number){
	$decimals = 0;
	$num_ex = explode('.',$number);
	$decimals = isset($num_ex[1]) ? strlen($num_ex[1]) : 2 ;
	return number_format($number,$decimals);
}


/*
$fy_id = $_POST['year_id'];
//$qtr_id = $_POST['qtr_id'];
//$d_type = $_POST['d_type'];

if ($fy_id == 2){
	$start = '2021-07-01';
	$end = '2022-06-30';
	$f_year = '2021-22';
}

if ($fy_id == 3){
	$start = '2022-07-01';
	$end = '2023-06-30';
	$f_year = '2022-23';
}

if ($fy_id == 4){
	$start = '2023-07-01';
	$end = '2024-06-30';
	$f_year = '2023-24';
}

if ($fy_id == 5){
	$start = '2024-07-01';
	$end = '2025-06-30';
	$f_year = '2024-25';
}

*/

$pdf = new \Mpdf\Mpdf([
				'mode' => 'utf-8',
				'format' => 'A3',
				'orientation' => 'L',
				'margin_header' => '6',
				'margin_top' => '32',
				'margin_bottom' => '12',
				'margin_footer' => '8',
				'deafult_font_size' => 8,
				'default_font' => 'nikosh'
			]);

$data = "";

$journals = $conn->query("SELECT acc_code, dpp_code, name, gob, rpa, dpp_amount from dpp d inner join account_list al on al.id = d.account_id WHERE year_id = 3 and (p_flag is NULL or p_flag=2) order by acc_code;");

$gov_total = 0;
$aid_total = 0;

$gov_exp_total = 0;
$aid_exp_total = 0;

$r_gov_exp = 0;
$r_aid_exp = 0;
$total_exp = 0;

$r_aid_total = 0;
$r_gov_total = 0;

$r_aid_exp_23 = 0;
$r_gov_exp_23 = 0;
$gov_exp_total_24_cum = 0;
$aid_exp_total_24_cum = 0;
$r_aid_exp_24 = 0;
$r_gov_exp_24 = 0;

$gov_exp_total_24 = 0;
$aid_exp_total_24 = 0;

$gov_exp_total_23 = 0;
$aid_exp_total_23 = 0;

$bal_gov_total = 0;
$bal_aid_total = 0;


//$data .= '<tr><td class="cl1" style="padding: 3px;" colspan="7"><b>Opening Balances</b></td></tr>';

//$data .= '<tr><td style="padding: 3px;" colspan="11"><b>A. Recurrent Expenditure</b></td></tr>';

if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
		$r_aid_total = 0;
		$r_gov_total = 0;
	   if ($row['dpp_amount'] != 0) {
			$data .= '<tr><td class="cl1" style="padding: 3px;">'.$row['acc_code'].'</td>';
			$data .= '<td class="cl4" style="padding: 3px;">'.$row['name'].'</td>';
			if ($row['gob'] != 0){
				$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['gob']/100000, 2).'</td>';
				$gov_total = $gov_total + $row['gob'];
				$r_gov_total = $row['gob'];
			} else{
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			}
			
			if ($row['rpa'] != 0){
				$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['rpa']/100000, 2).'</td>';
				$aid_total = $aid_total + $row['rpa'];
				$r_aid_total = $row['rpa'];
			} else{
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			}
			
			$data .= '<td class="cl3" style="padding: 3px;">'.number_format($row['dpp_amount']/100000, 2).'</td>';
			
			$acc_code = $row['dpp_code'];
			
			$journals_gov_exp = $conn->query("SELECT ((case when sum(case when group_id = 1 then jt.amount end) is null then 0 else sum(case when group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 3 and dpp_code = '$acc_code' and source_fund = 'GoB';");
			
			//journal_date >= '$start' and journal_date <='$end'
			while($row_gov_exp = $journals_gov_exp->fetch_assoc()){
				$r_gov_exp = 0;
				if ($row_gov_exp['Crt'] != 0) {
					$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row_gov_exp['Crt']/100000, 2).'</td>';
					$gov_exp_total = $gov_exp_total + $row_gov_exp['Crt'];
					$r_gov_exp = $row_gov_exp['Crt'];
				}else {
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				}
			}
			$journals_aid_exp = $conn->query("SELECT ((case when sum(case when group_id = 1 then jt.amount end) is null then 0 else sum(case when group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 3 and dpp_code = '$acc_code' and source_fund = 'wb';");
			
			while($row_aid_exp = $journals_aid_exp->fetch_assoc()){
				$r_aid_exp = 0;
				if ($row_aid_exp['Crt'] != 0) {
					$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row_aid_exp['Crt']/100000, 2).'</td>';					
					$aid_exp_total = $aid_exp_total + $row_aid_exp['Crt'];
					$r_aid_exp = $row_aid_exp['Crt'];
				}else {
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				}
			}
			if (($r_gov_exp + $r_aid_exp) != 0){
					$data .= '<td class="cl2" style="padding: 3px;">'.number_format(($r_gov_exp + $r_aid_exp)/100000, 2).'</td>';
				}else{
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				}

//2023-24 July to November

			$journals_gov_exp = $conn->query("SELECT ((case when sum(case when group_id = 1 then jt.amount end) is null then 0 else sum(case when group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date >= '2023-07-01' and journal_date <='2024-06-30' and dpp_code = '$acc_code' and source_fund = 'GoB';");
			
			while($row_gov_exp = $journals_gov_exp->fetch_assoc()){
				$r_gov_exp_23 = 0;
				if ($row_gov_exp['Crt'] != 0) {
					$data .= '<td class="cl2" style="padding: 3px;">'.number_format((($row_gov_exp['Crt']/12)*5)/100000, 2).'</td>';
					$gov_exp_total_23 = $gov_exp_total_23 + (($row_gov_exp['Crt']/12)*5);
					$r_gov_exp_23 = (($row_gov_exp['Crt']/12)*5);
				}else {
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				}
			}
			$journals_aid_exp = $conn->query("SELECT ((case when sum(case when group_id = 1 then jt.amount end) is null then 0 else sum(case when group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date >= '2023-07-01' and journal_date <='2023-11-30' and dpp_code = '$acc_code' and source_fund = 'wb';");
			
			while($row_aid_exp = $journals_aid_exp->fetch_assoc()){
				$r_aid_exp_23 = 0;
				if ($row_aid_exp['Crt'] != 0) {
					$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row_aid_exp['Crt']/100000, 2).'</td>';					
					$aid_exp_total_23 = $aid_exp_total_23 + $row_aid_exp['Crt'];
					$r_aid_exp_23 = $row_aid_exp['Crt'];
				}else {
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				}
			}
			if (($r_gov_exp_23 + $r_aid_exp_23) != 0){
					$data .= '<td class="cl2" style="padding: 3px;">'.number_format(($r_gov_exp_23 + $r_aid_exp_23)/100000, 2).'</td>';
				}else{
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				}

//2024-25 July to November

			$journals_gov_exp = $conn->query("SELECT ((case when sum(case when group_id = 1 then jt.amount end) is null then 0 else sum(case when group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date >= '2024-07-01' and journal_date <='2024-11-30' and dpp_code = '$acc_code' and source_fund = 'GoB';");
			
			while($row_gov_exp = $journals_gov_exp->fetch_assoc()){
				$r_gov_exp_24 = 0;
				if ($row_gov_exp['Crt'] != 0) {
					$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row_gov_exp['Crt']/100000, 2).'</td>';
					$gov_exp_total_24 = $gov_exp_total_24 + $row_gov_exp['Crt'];
					$r_gov_exp_24 = $row_gov_exp['Crt'];
				}else {
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				}
			}
			$journals_aid_exp = $conn->query("SELECT ((case when sum(case when group_id = 1 then jt.amount end) is null then 0 else sum(case when group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date >= '2024-07-01' and journal_date <='2024-11-30' and dpp_code = '$acc_code' and source_fund = 'wb';");
			
			while($row_aid_exp = $journals_aid_exp->fetch_assoc()){
				$r_aid_exp_24 = 0;
				if ($row_aid_exp['Crt'] != 0) {
					$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row_aid_exp['Crt']/100000, 2).'</td>';					
					$aid_exp_total_24 = $aid_exp_total_24 + $row_aid_exp['Crt'];
					$r_aid_exp_24 = $row_aid_exp['Crt'];
				}else {
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				}
			}
			if (($r_gov_exp_24 + $r_aid_exp_24) != 0){
					$data .= '<td class="cl2" style="padding: 3px;">'.number_format(($r_gov_exp_24 + $r_aid_exp_24)/100000, 2).'</td>';
				}else{
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				}
			
//2024-25 Cumulative upto November

			$journals_gov_exp = $conn->query("SELECT ((case when sum(case when group_id = 1 then jt.amount end) is null then 0 else sum(case when group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date <='2024-11-30' and dpp_code = '$acc_code' and source_fund = 'GoB';");
			
			while($row_gov_exp = $journals_gov_exp->fetch_assoc()){
				$r_gov_exp_24_cum = 0;
				if ($row_gov_exp['Crt'] != 0) {
					$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row_gov_exp['Crt']/100000, 2).'</td>';
					$gov_exp_total_24_cum = $gov_exp_total_24_cum + $row_gov_exp['Crt'];
					$r_gov_exp_24_cum = $row_gov_exp['Crt'];
				}else {
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				}
			}
			$journals_aid_exp = $conn->query("SELECT ((case when sum(case when group_id = 1 then jt.amount end) is null then 0 else sum(case when group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date <='2024-11-30' and dpp_code = '$acc_code' and source_fund = 'wb';");
			
			while($row_aid_exp = $journals_aid_exp->fetch_assoc()){
				$r_aid_exp_24_cum = 0;
				if ($row_aid_exp['Crt'] != 0) {
					$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row_aid_exp['Crt']/100000, 2).'</td>';					
					$aid_exp_total_24_cum = $aid_exp_total_24_cum + $row_aid_exp['Crt'];
					$r_aid_exp_24_cum = $row_aid_exp['Crt'];
				}else {
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				}
			}
			if (($r_gov_exp_24_cum + $r_aid_exp_24_cum) != 0){
					$data .= '<td class="cl2" style="padding: 3px;">'.number_format(($r_gov_exp_24_cum + $r_aid_exp_24_cum)/100000, 2).'</td>';
				}else{
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				}


			if (($row['gob']-$r_gov_exp_24_cum) == 0){
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			}else{
				$data .= '<td class="cl2" style="padding: 3px;">'.number_format(($row['gob']-$r_gov_exp_24_cum)/100000, 2).'</td>';
				$bal_gov_total = $bal_gov_total + ($row['gob']-$r_gov_exp_24_cum)/100000;
			}
			if (($row['rpa']-$r_aid_exp_24_cum) == 0){
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			}else{
				$data .= '<td class="cl2" style="padding: 3px;">'.number_format(($row['rpa']-$r_aid_exp_24_cum)/100000, 2).'</td>';
				$bal_aid_total = $bal_aid_total + ($row['rpa']-$r_aid_exp_24_cum)/100000;
			}
			if (($row['dpp_amount'] - ($r_gov_exp_24_cum + $r_aid_exp_24_cum)) != 0){
				$data .= '<td class="cl2" style="padding: 3px;">'.number_format(($row['dpp_amount'] - ($r_gov_exp_24_cum + $r_aid_exp_24_cum))/100000, 2).'</td></tr>';
			}else {
				$data .= '<td class="cl2" style="padding: 3px;">--</td></tr>';
			}
			$total_exp = $total_exp + $r_aid_exp + $r_gov_exp;
	   }
	}
}


$data .= '<tr><td style="padding: 3px;" colspan="2"><b>Total</b></td><td class="cl2">'.number_format($gov_total/100000, 2).'</td><td class="cl2">'.number_format($aid_total/100000, 2).'</td><td class="cl3">'.number_format(($gov_total + $aid_total)/100000, 2).'</td><td class="cl2">'.number_format($gov_exp_total/100000, 2).'</td><td class="cl2">'.number_format($aid_exp_total/100000, 2).'</td><td class="cl2">'.number_format($total_exp/100000, 2).'</td>

<td class="cl2">'.number_format($gov_exp_total_23/100000, 2).'</td><td class="cl2">'.number_format($aid_exp_total_23/100000, 2).'</td><td class="cl2">'.number_format(($gov_exp_total_23 + $aid_exp_total_23)/100000, 2).'</td>

<td class="cl2">'.number_format($gov_exp_total_24/100000, 2).'</td><td class="cl2">'.number_format($aid_exp_total_24/100000, 2).'</td><td class="cl2">'.number_format(($gov_exp_total_24 + $aid_exp_total_24)/100000, 2).'</td>

<td class="cl2">'.number_format($gov_exp_total_24_cum/100000, 2).'</td><td class="cl2">'.number_format($aid_exp_total_24_cum/100000, 2).'</td><td class="cl2">'.number_format(($gov_exp_total_24_cum + $aid_exp_total_24_cum)/100000, 2).'</td>

<td class="cl2">'.number_format($bal_gov_total, 2).'</td><td class="cl2">'.number_format($bal_aid_total, 2).'</td><td class="cl2">'.number_format(($bal_aid_total + $bal_gov_total), 2).'</td></tr>';


$header = '<!--mpdf
<htmlpageheader name="letterheader">
	<div style="font-size: 10pt; text-align: center; padding-top: 1mm; font-family: nikosh; ">
		<h3 style="margin-bottom: -1px;">National Special Economic Zone (NSEZ) Development Project</h3>
		<h4 style="margin-top: -5px; margin-bottom: -1px;">Credit No. IDA-6676 BD</h4>
		<h4 style="margin-top: -5px; margin-bottom: -1px;">Fund Allocation and Expenditure-Custom</h4>
	</div>
</htmlpageheader>
<htmlpagefooter name="letterfooter2">
	<div class="container" style="border-top: 1px solid #000000; font-size: 9pt; font-style: italic; padding-top: 1mm; font-family: sans-serif; ">
		<div class="column" style="text-align: left">
			NSEZ Development Project
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
	width: 5%;
	text-align: left;
}
.cl4 {
	width: 15%;
	text-align: left;
}
.cl2 {
	width: 5%;
	text-align: right;
}
.cl3 {
	width: 5%;
	text-align: right;
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
  <p style="text-align: right; margin-bottom: 2px; font-size: 11px;"><i>(Amount in BDT Lakh)</i></p>
	  <table>
		<thead>
		<tr>
		  <th class="cl1" style="background-color: #f3e3fa;" rowspan="2"><b>Account Code</b></th>
		  <th class="cl4" style="background-color: #f3e3fa;" rowspan="2"><b>Account Name</b></th>
		  <th style="background-color: #f3e3fa;" colspan="3"><b>Fund Allocation</b></th>
		  <th style="background-color: #f3e3fa;" colspan="3"><b>Expenditure (2022-23)</b></th>
		  <th style="background-color: #f3e3fa;" colspan="3"><b>Expenditure (2023-24 : Jul-Nov)</b></th>
		  <th style="background-color: #f3e3fa;" colspan="3"><b>Expenditure (2024-25 : Jul-Nov)</b></th>
		   <th style="background-color: #f3e3fa;" colspan="3"><b>Cumulative Expenditure-Nov 2024</b></th>
		  <th style="background-color: #f3e3fa;" colspan="3"><b>Balance</b></th>
		</tr>
		<tr>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>GoB</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>RPA</b></th>
		  <th class="cl3" style="background-color: #f3e3fa;"><b>Total</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>GoB</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>RPA</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>Total</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>GoB</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>RPA</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>Total</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>GoB</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>RPA</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>Total</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>GoB</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>RPA</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>Total</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>GoB</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>RPA</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>Total</b></th>
		</tr>
		</thead>		  
		<tbody>
		'.$data.'
		</tbody>
	   </table>

<br><br>
<p style="margin-bottom: 2px; font-size: 11px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Financial Management Specialist&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Project Director</p>
</body>
</html>';
$pdf->WriteHTML($header);
$pdf->WriteHTML($html);
$pdf->Output('');
exit;
?>

    