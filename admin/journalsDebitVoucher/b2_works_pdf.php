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
	$start = '2020-07-01';
	$end = '2024-09-30';
	$qtr_name = 'Upto Sep, 2024';
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
				'orientation' => 'L',
				'margin_header' => '8',
				'margin_top' => '32',
				'margin_bottom' => '18',
				'margin_footer' => '8',
				'deafult_font_size' => 8,
				'default_font' => 'nikosh'
			]);

$data = "";

$heading = "";
if (($exp_group >= 1) && ($exp_group <= 2)){
$heading = '<th class="cl8" style="background-color: #f3e3fa;"><b>Invoice No. & Date</b></th><th class="cl9" style="background-color: #f3e3fa;"><b>Amount Invoiced</b></th><th class="cl10" style="background-color: #f3e3fa;"><b>% Financed by Bank of the Category</b></th><th class="cl11" style="background-color: #f3e3fa;"><b>Amount Paid from DA (BDT)</b></th>';
} else {
	$heading = '<th class="cl8" style="background-color: #f3e3fa;"><b>Amount Invoiced</b></th><th class="cl9" style="background-color: #f3e3fa;"><b>Invoice No. & Date</b></th><th class="cl10" style="background-color: #f3e3fa;"><b>Amount Paid from DA</b></th><th class="cl11" style="background-color: #f3e3fa;"><b>Contract Balance Carried Forward</b></th>';
}

$journals = $conn->query("SELECT p.payee_name as p_name, p.contract_value, p.contract_value_us, pk.pack_name, p.contract_date, p.selection_method, p.contract_currency, je.component_number, p.percent_bank, p.id as pid, sum(je.gross_amt) as Crt from `journal_entries` je inner join payee p on p.id = je.payee_name inner join pkg pk on pk.id = p.pack_number WHERE je.adjusted is null and journal_date >= '$start' and journal_date <= '$end' and dli_type = '$d_type' and je.exp_group = '$exp_group' and je.exp_type=2 group by p.payee_name, p.contract_value, p.contract_value_us, pk.pack_name, p.contract_date, p.selection_method, p.contract_currency, je.component_number, p.percent_bank, p.id;");

$contract_total = 0;
$invoiced_total = 0;
$balance_total = 0;
$balance_row = 0;
 
if ($journals->num_rows > 0){
	//$data .= '<tr><td style ="padding: 3px;" colspan="11"><b>'.$title.'</b></td></tr>';
	while($row = $journals->fetch_assoc()){
		$payee_id = $row['pid'];
		$journals_details = $conn->query("SELECT je.journal_date, je.chq_number as chq_number from `journal_entries` je inner join payee p on p.id = je.payee_name WHERE je.adjusted is null and p.iufr_flag = 2 and journal_date >= '$start' and journal_date <= '$end' and dli_type = '$d_type' and je.exp_group = '$exp_group' and je.exp_type=2 and p.id = '$payee_id';");
			
		if ($journals_details->num_rows > 0){
			$inv_date = "";
			while($row_details = $journals_details->fetch_assoc()){
				$inv_date .=  $row_details['chq_number'].' '.date("d-m-Y", strtotime($row_details['journal_date'])).' ';
			}
			
			if ($exp_group > 2){
				$journals_total = $conn->query("SELECT sum(je.gross_amt) as Crt_total from `journal_entries` je inner join payee p on p.id = je.payee_name WHERE je.adjusted is null and journal_date <= '$end' and dli_type = '$d_type' and je.exp_group = '$exp_group' and je.exp_type=2 and p.iufr_flag = 2 and p.id = '$payee_id' group by p.id;");
				
				if ($journals_total->num_rows > 0){
					while($row_total = $journals_total->fetch_assoc()){
						$balance_row = $row['contract_value'] - $row_total['Crt_total'];
					}
				}
				$balance_total = $balance_total + $balance_row;
				$contract_total = $contract_total + $row['contract_value'];
				$invoiced_total = $invoiced_total + $row['Crt'];
				$data .= '<tr><td class="cl1" style ="padding: 3px;">'.$row['component_number'].'</td>';
				$data .= '<td class="cl2" style ="padding: 3px;">'.date("d-m-Y", strtotime($row['contract_date'])).'</td>';
				$data .= '<td class="cl3" style ="padding: 3px;">'.$row['pack_name'].'</td>';
				$data .= '<td class="cl4" style ="padding: 3px;">'.$row['selection_method'].'</td>';
				$data .= '<td class="cl5" style ="padding: 3px; text-align: left;">'.$row['p_name'].'</td>';
				$data .= '<td class="cl6" style ="padding: 3px;">'.$row['contract_currency'].'</td>';
				$data .= '<td class="cl7" style ="padding: 3px; text-align: right;">'.number_format($row['contract_value'], 2, '.', ',').'</td>';
				$data .= '<td class="cl8" style ="padding: 3px; text-align: right;">'.number_format($row['Crt'], 2, '.', ',').'</td>';
				$data .= '<td class="cl9" style ="padding: 3px;">'.$inv_date.'</td>';
				$data .= '<td class="cl10" style ="padding: 3px; text-align: right;">'.number_format($row['Crt'], 2, '.', ',').'</td>';
				$data .= '<td class="cl11" style ="padding: 3px; text-align: right;">'.number_format($balance_row, 2, '.', ',').'</td></tr>';
		  } else {
			  
			  if (($exp_group == 1) && ($d_type == 'Non-DLI')) {
				$invoiced_total = $invoiced_total + $row['Crt'];
				$balance_total = $balance_total + $row['Crt']*($row['percent_bank']/100);
			  }
			  if (($exp_group == 1) && ($d_type == 'DLI')) {
				$invoiced_total = $invoiced_total + $row['Crt'];
				$balance_total = $balance_total + $row['Crt']*0.3637;
			  }
				if (($exp_group == 2) && ($d_type == 'Non-DLI'))
					$data .= '<tr><td class="cl1" style ="padding: 3px;">2</td>';
				if (($exp_group == 2) && ($d_type == 'DLI'))
					$data .= '<tr><td class="cl1" style ="padding: 3px;">6 (i)</td>';
				if ($exp_group <> 2)
					$data .= '<tr><td class="cl1" style ="padding: 3px;">'.$row['component_number'].'</td>';
				$data .= '<td class="cl2" style ="padding: 3px;">'.date("d-m-Y", strtotime($row['contract_date'])).'</td>';
				$data .= '<td class="cl3" style ="padding: 3px;">'.$row['pack_name'].'</td>';
				$data .= '<td class="cl4" style ="padding: 3px;">'.$row['selection_method'].'</td>';
				$data .= '<td class="cl5" style ="padding: 3px;">'.$row['p_name'].'</td>';
				$data .= '<td class="cl6" style ="padding: 3px;">'.$row['contract_currency'].'</td>';
				$data .= '<td class="cl7" style ="padding: 3px; text-align: right;">BDT: '.number_format($row['contract_value'], 2, '.', ',').'<br>USD: '.number_format($row['contract_value_us'], 2, '.', ',').'</td>';
				$data .= '<td class="cl9" style ="padding: 3px;">'.$inv_date.'</td>';
				 if ($exp_group == 2){
					$journals_2 = $conn->query("SELECT sum(je.gross_amt) as Crt_2 from `journal_entries` je inner join payee p on p.id = je.payee_name WHERE adjusted is null and journal_date >= '$start' and journal_date <= '$end' and je.exp_group = '$exp_group' and je.exp_type=2 and p.iufr_flag = 2 and p.id = '$payee_id' group by p.id;");
					
					if ($journals_2->num_rows > 0){
						$total = 0;
					 while($row_2 = $journals_2->fetch_assoc()){
						$data .= '<td class="cl8" style ="padding: 3px; text-align: right;">'.number_format($row_2['Crt_2'], 2, '.', ',').'</td>';
						$total = $row_2['Crt_2'];
						$invoiced_total = $invoiced_total + $row_2['Crt_2'];
						if ($d_type == 'Non-DLI')
							$balance_total = $balance_total + $row_2['Crt_2']*($row['percent_bank']/100);
						else
							$balance_total = $balance_total + $row_2['Crt_2']*(0.3637);
						//$balance_total = $balance_total + $row_2['Crt_2']*($row['percent_bank']/100);
					}
				  }
				} else {					
					$data .= '<td class="cl8" style ="padding: 3px; text-align: right;">'.number_format($row['Crt'], 2, '.', ',').'</td>';
				} 
				//$data .= '<td class="cl8" style ="padding: 3px; text-align: right;">'.number_format($row['Crt'], 2, '.', ',').'</td>';
				if (($exp_group == 2) && ($d_type == 'Non-DLI')) {
					$data .= '<td class="cl10" style ="padding: 3px; text-align: center;">'.number_format($row['percent_bank'], 2, '.', ',').'%</td>';
					$data .= '<td class="cl11" style ="padding: 3px; text-align: right;">'.number_format($total*($row['percent_bank']/100), 2, '.', ',').'</td></tr>';
				}
				if (($exp_group == 2) && ($d_type == 'DLI')) {
					$data .= '<td class="cl10" style ="padding: 3px; text-align: center;">36.37%</td>';
					$data .= '<td class="cl11" style ="padding: 3px; text-align: right;">'.number_format($total*(0.3637), 2, '.', ',').'</td></tr>';
				}
		  }
		}
	} //Outer Whileif ($exp_group > 2){
	if ($exp_group > 2){
		$data .= '<tr><td style ="padding: 3px;" colspan="6"><b>Total</b></td>';
		$data .= '<td class="cl7" style ="padding: 3px; text-align: right;"><b>'.number_format($contract_total, 2, '.', ',').'</b></td>';
		$data .= '<td class="cl8" style ="padding: 3px; text-align: right;"><b>'.number_format($invoiced_total, 2, '.', ',').'</b></td>';
		$data .= '<td class="cl9" style ="padding: 3px;"></td>';
		$data .= '<td class="cl10" style ="padding: 3px; text-align: right;"><b>'.number_format($invoiced_total, 2, '.', ',').'</b></td>';
		$data .= '<td class="cl11" style ="padding: 3px; text-align: right;"><b>'.number_format($balance_total, 2, '.', ',').'</b></td></tr>';
	}else {
		$data .= '<tr><td style ="padding: 3px;" colspan="8"><b>Total</b></td>';
		$data .= '<td class="cl9" style ="padding: 3px; text-align: right;"><b>'.number_format($invoiced_total, 2, '.', ',').'</b></td>';
		$data .= '<td class="cl10" style ="padding: 3px;"></td>';
		$data .= '<td class="cl11" style ="padding: 3px; text-align: right;"><b>'.number_format($balance_total, 2, '.', ',').'</b></td></tr>';
	}
}

$header = '<!--mpdf
<htmlpageheader name="letterheader">
	<div style="font-size: 10pt; text-align: center; padding-top: 1mm; font-family: nikosh; ">
		<h3 style="margin-bottom: -1px;">National Special Economic Zone (NSEZ) Development Project</h3>
		<h4 style="margin-top: -5px; margin-bottom: -1px;">Credit No. IDA-6676 BD ('.$d_type.' Part)</h4>
		<h4 style="margin-top: -5px; margin-bottom: -1px;">2B. Designated Account Expenditure for Contracts/Subject to not Prior Review (Summary)</h4>
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
	width: 8%;
	text-align: center;
}
.cl2 {
	width: 8%;
	text-align: center;
}
.cl3 {
	width: 7%;
	text-align: center;
}
.cl4 {
	width: 7%;
	text-align: center;
}
.cl5 {
	width: 11%;
	text-align: center;
}
.cl6 {
	width: 7%;
	text-align: center;
}
.cl7 {
	width: 10%;
	text-align: center;
}
.cl8 {
	width: 10%;
	text-align: center;
}
.cl9 {
	width: 8%;
	text-align: center;
}
.cl10 {
	width: 10%;
	text-align: center;
}
.cl11 {
	width: 11%;
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
.column1 {
  float: left;
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
    <div class="row">
	  <div class="column1" style="text-align: left">
		<b>'.$title.'</b>
	  </div>
	  <div class="column1" style="text-align: right; font-size: 11px; margin-right: 20px;">
		<i>(Amount in BDT)</i>
	  </div>
    </div>
	  <table>
		<thead>
			<tr>
			  <th class="cl1" style="background-color: #f3e3fa;"><b>Disbursement Category</b></th>
			  <th class="cl2" style="background-color: #f3e3fa;"><b>Contract Date</b></th>
			  <th class="cl3" style="background-color: #f3e3fa;"><b>Package No</b></th>
			  <th class="cl4" style="background-color: #f3e3fa;"><b>Selection Method</b></th>
			  <th class="cl5" style="background-color: #f3e3fa;"><b>Contractor\'s Name</b></th>
			  <th class="cl6" style="background-color: #f3e3fa;"><b>Contract Currency</b></th>
			  <th class="cl7" style="background-color: #f3e3fa;"><b>Contract Value</b></th>
			  '.$heading.'
			</tr>
		</thead>		  
		<tbody>
			'.$data.'
		</tbody>
	  </table>


<br><br><br><br>
<p style="margin-bottom: 2px; font-size: 11px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Financial Management Specialist&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Project Director</p>
</body>
</html>';
$pdf->WriteHTML($header);
$pdf->WriteHTML($html);
$pdf->Output('');
exit;
?>