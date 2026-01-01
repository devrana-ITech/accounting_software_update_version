<?php	
//echo '<script>alert("Welcome to Geeks for Geeks")</script>';	
require_once('./../../config.php');

//require('../mpdf/mpdf.php');

require('../../vendor/autoload.php');

$start=date("Y/m/d",strtotime($_POST['from_date']));
$end=date("Y/m/d",strtotime($_POST['to_date']));

If (empty($_POST['from_date']))
{
	$start=date("Y/m/d",strtotime($_settings->userdata('from_date')));
}

If (empty($_POST['to_date']))
{
	$end=date("Y/m/d",strtotime($_settings->userdata('to_date')));
}



$pdf = new \Mpdf\Mpdf([
				'mode' => 'utf-8',
				'format' => 'A4',
				'orientation' => 'L',
				'margin_header' => '6',
				'margin_top' => '18',
				'margin_bottom' => '12',
				'margin_footer' => '8',
				'deafult_font_size' => 8,
				'default_font' => 'nikosh'
			]);
//$pdf = new mPDF('', 'A4-L',8,'');

//inner join `account_list` on account_list.id = journal_items.account_id
//$dat .= '<h3><b>Advertisements details from ' .date("M d, Y",strtotime($start)).' to '.date("M d, Y",strtotime($end)).'</b></h3>';

$data = "";

$details = $conn->query("SELECT * FROM `journal_entries` where journal_date >= '$start' and journal_date <= '$end' and journal_type='dv' and dli_type = 'Non-DLI' and exp_group=2 order by id asc");
	
$sn = 0;
while($row_d = $details->fetch_assoc()){
	$sn++;
	$net_amt = 0;
	if(($row_d['vat_deduction'] == "") || ($row_d['vat_deduction'] == 0))
		$vat_deduction = "--";
	else {
		$vat_deduction = format_num($row_d['vat_deduction']);
		$net_amt = $row_d['gross_amt'] - $row_d['vat_deduction'];
	  }
	if(($row_d['it_deduction'] == "") || ($row_d['it_deduction'] == 0))
		$it_deduction = "--";
	else {
		$it_deduction = format_num($row_d['it_deduction']);
		$net_amt = $net_amt - $row_d['it_deduction'];
	}
	if(($row_d['sc_deduction'] == "") || ($row_d['sc_deduction'] == 0))
		$sc_deduction = "--";
	else {
		$sc_deduction = format_num($row_d['sc_deduction']);
		$net_amt = $net_amt - $row_d['sc_deduction'];
	}
	if(($row_d['security_deduction'] == "") || ($row_d['security_deduction'] == 0))
		$security_deduction = "--";
	else {
		$security_deduction = format_num($row_d['security_deduction']);
		$net_amt = $net_amt - $row_d['security_deduction'];
	}

	$net_amt = 
	$data .= '<tr>'
	.'<td class="cl4">'.$sn.'</td>'
	.'<td class="cl1">'.$row_d['voucher_number'].' -- '.$row_d['description'].' -- '.$row_d['payee_name'].'</td>'
	.'<td style="text-align: right;" class="cl2">'.format_num($row_d['gross_amt']).'</td>'
	.'<td style="text-align: right;" class="cl2">'.$vat_deduction.'</td>'
	.'<td style="text-align: right;" class="cl2">'.$it_deduction.'</td>'
	.'<td style="text-align: right;" class="cl3">'.$sc_deduction.'</td>'
	.'<td style="text-align: right;" class="cl3">'.$security_deduction.'</td>'
	.'<td style="text-align: right;" class="cl2">'.$net_amt.'</td>'
	.'<td style="text-align: center;" class="cl3">'.$row_d['chq_number'].'</td></tr>';
}

function format_num($number){
	$decimals = 0;
	$num_ex = explode('.',$number);
	$decimals = isset($num_ex[1]) ? strlen($num_ex[1]) : 2 ;
	return number_format($number,$decimals);
}


$header = '<!--mpdf
<htmlpageheader name="letterheader">
		<div style="font-size: 10pt; text-align: center; padding-top: 1mm; font-family: nikosh; ">
		<h3 style="margin-bottom: 2px;">Debit Voucher Register for Non-DLI Fund</h3>
		<h5 style="margin-top: 2px;"><b>Reporting Period:  &nbsp;&nbsp;' .date("M d, Y",strtotime($start)).' -- '.date("M d, Y",strtotime($end)).'</b></h5>
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
	width: 200px;
	
}
.cl2 {
	width: 90px;
	text-align: right;
}
.cl3 {
	width: 80px;
	text-align: right;
}

.cl5 {
	width: 80px;
	text-align: center;
}

.cl4 {
	width: 30px;
	text-align: center;
}

table, th, td {
  border: 1px solid #d9d0f7;
  border-collapse: collapse;
  width: 100%;
  padding: 5px;
  font-size: 11px;
}

</style>
  </head><body>
  
  <p style="text-align: right; margin-bottom: 2px; font-size: 11px;"><i>Figures are in BDT</i></p>
  <table>
	<thead>
	<tr>
	  <th class="cl4" style="background-color: #f3e3fa;"><b>SN</b></th>
	  <th class="cl1" style="background-color: #f3e3fa;"><b>Name of the Payee</b></th>
	  <th class="cl2" style="background-color: #f3e3fa;"><b>Gross Amount</b></th>
	  <th class="cl2" style="background-color: #f3e3fa;"><b>VAT Deduction</b></th>
	  <th class="cl2" style="background-color: #f3e3fa;"><b>IT Deduction</b></th>
	  <th class="cl3" style="background-color: #f3e3fa;"><b>Service Charge Deduction</b></th>
	  <th class="cl3" style="background-color: #f3e3fa;"><b>Security Deposit Deduction</b></th>
	  <th class="cl2" style="background-color: #f3e3fa;"><b>Net Pay</b></th>
	  <th class="cl5" style="background-color: #f3e3fa;"><b>Cheque/Advice No</b></th>
	</tr>
	</thead>
					  
	<tbody>
	'.$data.'
	</tbody>
</table></body></html>';
//$mpdf->WriteHTML($header);
$pdf->WriteHTML($header);
$pdf->WriteHTML($html);
//$txt = "This tutorial is made by \n মধুসূদন সরকার ";

//$pdf->MultiCell(100,10,$txt,1,'L',0);
$pdf->Output('');
exit;
?>

    