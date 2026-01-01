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

$exp_group = $_POST['exp_group'];

if ($exp_group == 1){
	$title = "Goods";
}
if ($exp_group == 2){
	$title = "Works";
}
if ($exp_group == 3){
	$title = "Consultancy Services";
}
if ($exp_group == 4){
	$title = "Non-Consultancy Services";
}
if ($exp_group == 5){
	$title = "Training Expenses";
}
if ($exp_group == 6){
	$title = "Operating Expenses";
}

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

$pdf = new \Mpdf\Mpdf([
				'mode' => 'utf-8',
				'format' => 'A4',
				'orientation' => 'P',
				'margin_header' => '15',
				'margin_top' => '46',
				'margin_bottom' => '18',
				'margin_footer' => '8',
				'deafult_font_size' => 8,
				'default_font' => 'nikosh'
			]);

$data = "";
if (($exp_group >= 5) && ($exp_group <= 6)){
	$journals = $conn->query("SELECT al.id as acc_id, al.name, sum(je.gross_amt) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date >= '$start' and journal_date <= '$end' and dli_type = '$d_type' and je.exp_group = '$exp_group' and je.exp_type=2 and iufr_flag = 1 group by al.id;");
}else {
$journals = $conn->query("SELECT al.name, p.id as pid, p.payee_name as p_name, p.contract_value, pk.pack_name, sum(je.gross_amt) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id inner join payee p on p.id = je.payee_name inner join pkg pk on pk.id = je.pkg_number WHERE journal_date >= '$start' and journal_date <= '$end' and dli_type = '$d_type' and je.exp_group = '$exp_group' and je.exp_type=2 and group_id = 1 and p.iufr_flag = 2 group by al.name, p.payee_name, p.contract_value, pk.pack_name, p.id;");
}

$c1_total = 0;
$c2_total = 0;
$c3_total = 0;

if ($journals->num_rows > 0){
    if ($exp_group ==1) { //Goods
		$data .= '<tr><td style ="padding: 3px;" colspan="4"><b>(1) '.$title.'</b></td></tr>';
		while($row = $journals->fetch_assoc()){
		   $data .= '<tr><td class="cl1" style ="padding: 3px;">Purchase of '.$row['name'].' from '.$row['p_name'].' under contract package no <i>' .$row['pack_name']. '</i></td>';
		   $data .= '<td class="cl2" style="padding: 3px; text-align: right;">'.number_format($row['contract_value'], 2, '.', ',').'</td>';
		   $data .= '<td class="cl3" style="padding: 3px; text-align: right;">'.number_format($row['Crt'], 2, '.', ',').'</td>';
		   $data .= '<td class="cl4" style="padding: 3px; text-align: right;">'.number_format($row['Crt'], 2, '.', ',').'</td></tr>';
		   $c1_total =  $c1_total + $row['contract_value'];
		   $c2_total =  $c2_total + $row['Crt'];
		   $c3_total =  $c3_total + $row['Crt'];
		}
	}
	if ($exp_group ==3) {//Consultancy Services
		$data .= '<tr><td style ="padding: 3px;" colspan="4"><b>(1) '.$title.'</b></td></tr>';
		while($row = $journals->fetch_assoc()){
		   $data .= '<tr><td class="cl1" style ="padding: 3px;">Remuneration payment for the period of '.$qtr_name.' of '.$row['p_name'].' under contract package no <i>' .$row['pack_name']. '</i></td>';
		   $data .= '<td class="cl2" style="padding: 3px; text-align: right;">'.number_format($row['contract_value'], 2, '.', ',').'</td>';
		   $data .= '<td class="cl3" style="padding: 3px; text-align: right;">'.number_format($row['Crt'], 2, '.', ',').'</td>';
		   $data .= '<td class="cl4" style="padding: 3px; text-align: right;">'.number_format($row['Crt'], 2, '.', ',').'</td></tr>';
		   $c1_total =  $c1_total + $row['contract_value'];
		   $c2_total =  $c2_total + $row['Crt'];
		   $c3_total =  $c3_total + $row['Crt'];
		}
	}
	if ($exp_group ==4) { //Non-Consultancy Services
		$data .= '<tr><td style ="padding: 3px;" colspan="4"><b>(1) '.$title.'</b></td></tr>';
		while($row = $journals->fetch_assoc()){
			if ($row['pid'] == 1 )
				$data .= '<tr><td class="cl1" style ="padding: 3px;">Outsourcing staff salary for the period of '.$qtr_name.' under contract package no <i>' .$row['pack_name']. '</i></td>';
			if ($row['pid'] == 108 )
				$data .= '<tr><td class="cl1" style ="padding: 3px;">Vehicle rent for the period of '.$qtr_name.' under contract package no <i>' .$row['pack_name']. '</i></td>';
		   $data .= '<td class="cl2" style="padding: 3px; text-align: right;">'.number_format($row['contract_value'], 2, '.', ',').'</td>';
		   $data .= '<td class="cl3" style="padding: 3px; text-align: right;">'.number_format($row['Crt'], 2, '.', ',').'</td>';
		   $data .= '<td class="cl4" style="padding: 3px; text-align: right;">'.number_format($row['Crt'], 2, '.', ',').'</td></tr>';
		   $c1_total =  $c1_total + $row['contract_value'];
		   $c2_total =  $c2_total + $row['Crt'];
		   $c3_total =  $c3_total + $row['Crt'];
		}
	}
	if ($exp_group ==5) { //Training
		$data .= '<tr><td style ="padding: 3px;" colspan="4"><b>(1) '.$title.'</b></td></tr>';
		while($row = $journals->fetch_assoc()){
		   $data .= '<tr><td class="cl1" style ="padding: 3px;">'.$row['name'].' expenditures (except allowances) incurred for the period of '.$qtr_name.'</td>';
		   $data .= '<td class="cl2" style="padding: 3px; text-align: right;">'.number_format($row['Crt'], 2, '.', ',').'</td>';
		   $data .= '<td class="cl3" style="padding: 3px; text-align: right;">'.number_format($row['Crt'], 2, '.', ',').'</td>';
		   $data .= '<td class="cl4" style="padding: 3px; text-align: right;">'.number_format($row['Crt'], 2, '.', ',').'</td></tr>';
		   $c1_total =  $c1_total + $row['Crt'];
		   $c2_total =  $c2_total + $row['Crt'];
		   $c3_total =  $c3_total + $row['Crt'];
		}
	}
	if ($exp_group ==6) { //Operating Costs
		$data .= '<tr><td style ="padding: 3px;" colspan="4"><b>(1) '.$title.'</b></td></tr>';
		while($row = $journals->fetch_assoc()){
		   if ($row['acc_id'] == 23) //'.$row['name'].' (expenditures)
				$data .= '<tr><td class="cl1" style ="padding: 3px;">Communication Expenses (Advertisement for tender publication) incurred for the period of '.$qtr_name.'</td>';
		   if ($row['acc_id'] == 12) //'.$row['name'].' (expenditures)
				$data .= '<tr><td class="cl1" style ="padding: 3px;">Project related meeting expenditures incurred for the period of '.$qtr_name.'</td>';
		   if ($row['acc_id'] == 40) //'.$row['name'].' (expenditures)
				$data .= '<tr><td class="cl1" style ="padding: 3px;">Office supplies (Printing and Binding) expenditures incurred for the period of '.$qtr_name.'</td>';
		   $data .= '<td class="cl2" style="padding: 3px; text-align: right;">'.number_format($row['Crt'], 2, '.', ',').'</td>';
		   $data .= '<td class="cl3" style="padding: 3px; text-align: right;">'.number_format($row['Crt'], 2, '.', ',').'</td>';
		   $data .= '<td class="cl4" style="padding: 3px; text-align: right;">'.number_format($row['Crt'], 2, '.', ',').'</td></tr>';
		   $c1_total =  $c1_total + $row['Crt'];
		   $c2_total =  $c2_total + $row['Crt'];
		   $c3_total =  $c3_total + $row['Crt'];
		}
	}
}

$data .= '<tr><td class="cl1" style="padding: 3px;"><b>Sub-Total of '.$title.'</b></td>';	
$data .= '<td class="cl2" style="padding: 3px; text-align: right;"><b>'.number_format($c1_total, 2, '.', ',').'</b></td>';
$data .= '<td class="cl3" style="padding: 3px; text-align: right;"><b>'.number_format($c2_total, 2, '.', ',').'</b></td>';
$data .= '<td class="cl4" style="padding: 3px; text-align: right;"><b>'.number_format($c3_total, 2, '.', ',').'</b></td></tr>';	


$header = '<!--mpdf
<htmlpageheader name="letterheader">
	<div style="font-size: 10pt; text-align: center; padding-top: 1mm; font-family: nikosh; ">
		<h3 style="margin-bottom: -1px;">National Special Economic Zone (NSEZ) Development Project</h3>
		<h4 style="margin-top: -5px; margin-bottom: -1px;">Credit No. IDA-6676 BD ('.$d_type.' Part)</h4>
		<h4 style="margin-top: -5px; margin-bottom: -1px;">2B. Designated Account Expenditure for Contracts/Not Subject to Prior Review (Summary)</h4>
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
	width: 52%;
	text-align: left;
}
.cl2 {
	width: 16%;
	text-align: center;
}
.cl3 {
	width: 16%;
	text-align: center;
}
.cl4 {
	width: 16%;
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
  <p style="text-align: right; margin-bottom: 2px; font-size: 11px;"><i>(Amount in BDT)</i></p>
	  <table>
		<thead>
			<tr>
			  <th class="cl1" style="background-color: #f3e3fa;"><b>Disbursement Category and Description</b></th>
			  <th class="cl2" style="background-color: #f3e3fa;"><b>Contract Value</b></th>
			  <th class="cl3" style="background-color: #f3e3fa;"><b>Amount Invoiced</b></th>
			  <th class="cl4" style="background-color: #f3e3fa;"><b>Amount Paid from DA</b></th>
			</tr>
		</thead>		  
		<tbody>
			'.$data.'
		</tbody>
	  </table>


<br><br><br><br>
<p style="margin-bottom: 2px; font-size: 11px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Financial Management Specialist&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Project Director</p>
</body>
</html>';
$pdf->WriteHTML($header);
$pdf->WriteHTML($html);
$pdf->Output('');
exit;
?>