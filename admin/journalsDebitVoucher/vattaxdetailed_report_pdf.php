<?php	
//echo '<script>alert("Welcome to Geeks for Geeks")</script>';	
require_once('./../../config.php');

//require('../mpdf/mpdf.php');

require('../../vendor/autoload.php');

$start=date("Y/m/d",strtotime($_POST['from_date']));
$end=date("Y/m/d",strtotime($_POST['to_date']));
//$d_type = $_POST['d_type'];

If (empty($_POST['from_date']))
{
	$start=date("Y/m/d",strtotime($_settings->userdata('from_date')));
}

If (empty($_POST['to_date']))
{
	$end=date("Y/m/d",strtotime($_settings->userdata('to_date')));
}

if (!empty($_POST['account_id']))
	$account_id = $_POST['account_id'];


$pdf = new \Mpdf\Mpdf([
				'mode' => 'utf-8',
				'format' => 'A4',
				'orientation' => 'P',
				'margin_header' => '10',
				'margin_top' => '32',
				'margin_bottom' => '12',
				'margin_footer' => '8',
				'deafult_font_size' => 8,
				'default_font' => 'nikosh'
			]);
//$pdf = new mPDF('', 'A4-L',8,'');

//inner join `account_list` on account_list.id = journal_items.account_id
//$dat .= '<h3><b>Advertisements details from ' .date("M d, Y",strtotime($start)).' to '.date("M d, Y",strtotime($end)).'</b></h3>';

$data = "";

$title = "";

$t_row = $conn->query("SELECT payee_name, designation, contract_date FROM `payee` where id = '$account_id'");

while($t_row_d = $t_row->fetch_assoc()){
	$title .= 'Firm/Contractor/Consultant\'s Name: <b>'.$t_row_d['payee_name'].'</b>';  
	//-::-  Contract Date: <b>'.date("d-m-Y", strtotime($t_row_d['contract_date'])).'</b>';
}

if (($account_id == 89) || ($account_id == 96))
	$details = $conn->query("SELECT journal_date, gross_amt, vat_deduction, it_deduction, (gross_amt- vat_deduction - it_deduction) as netamount, (vat_deduction+it_deduction) as linetotal FROM `journal_entries` where (payee_name = 89 or payee_name = 96) and journal_date >= '$start' and journal_date <= '$end' order by journal_date ASC");
else
	$details = $conn->query("SELECT journal_date, gross_amt, vat_deduction, it_deduction, (gross_amt- vat_deduction - it_deduction) as netamount, (vat_deduction+it_deduction) as linetotal FROM `journal_entries` where payee_name = '$account_id' and journal_date >= '$start' and journal_date <= '$end' order by journal_date ASC");

//ind_con_firm_report_pdf.php  p.designation, contract_date

	
$sn = 0;
$net_amt = 0;
$vat = 0;
$it = 0;

$grs_amt = 0;
$netamt = 0;


while($row_d = $details->fetch_assoc()){
	$sn++;
	
	$net_amt = $net_amt + $row_d['linetotal'];
	$vat = $vat + $row_d['vat_deduction'];
	$it = $it + $row_d['it_deduction'];
	
	$grs_amt = $grs_amt + $row_d['gross_amt'];
	$netamt = $netamt + $row_d['netamount'];
		
	$data .= '<tr>'
	.'<td style="text-align: center; width: 10%; font-size: 12px;">'.$sn.'</td>'
	.'<td style="text-align: center; width: 15%; font-size: 12px;">'.date("d-m-Y", strtotime($row_d['journal_date'])).'</td>'
	.'<td style="text-align: right; width: 15%; font-size: 12px;">'.number_format($row_d['gross_amt'], 2).'</td>'
	.'<td style="text-align: right; width: 15%; font-size: 12px;">'.number_format($row_d['vat_deduction'], 2).'</td>'
	.'<td style="text-align: right; width: 15%; font-size: 12px;">'.number_format($row_d['it_deduction'], 2).'</td>'
	.'<td style="text-align: right; width: 15%; font-size: 12px;">'.number_format($row_d['netamount'], 2).'</td>'
	.'<td style="text-align: right; width: 15%; font-size: 12px;">'.number_format($row_d['linetotal'], 2).'</td></tr>';
	
}
$data .= '<tr><td style="text-align: left; font-size: 12px; width: 25%;" colspan="2"><b>Total</b></td>
<td style="text-align: right; font-size: 12px; width: 15%;"><b>'.number_format($grs_amt, 2).'</b></td><td style="text-align: right; font-size: 12px; width: 15%;"><b>'.number_format($vat, 2).'</b></td><td style="text-align: right; font-size: 12px; width: 15%;"><b>'.number_format($it, 2).'</b></td><td style="text-align: right; font-size: 12px; width: 15%;"><b>'.number_format($netamt, 2).'</b></td><td style="text-align: right; font-size: 12px; width: 15%;"><b>'.number_format($net_amt,2).'</b></td></tr>';



function format_num($number){
	$decimals = 0;
	$num_ex = explode('.',$number);
	$decimals = isset($num_ex[1]) ? strlen($num_ex[1]) : 2 ;
	return number_format($number,$decimals);
}


$header = '<!--mpdf
<htmlpageheader name="letterheader">
		<div style="font-size: 10pt; text-align: center; padding-top: 1mm; font-family: nikosh; ">
		<h3 style="margin-bottom: 5px;">National Special Economic Zone Development Project</h3>
		<h4 style="margin-bottom: 2px;">Detailed Payment Statement</h4>
		<h5 style="margin-top: 2px; margin-bottom: 10px;"><b>Reporting Period:  &nbsp;&nbsp;' .date("M d, Y",strtotime($start)).' -- '.date("M d, Y",strtotime($end)).'</b></h5>
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

table, th, td {
  border: 1px solid #d9d0f7;
  border-collapse: collapse;
  width: 100%;
  padding: 5px;
}

</style>
  </head><body>
  <br>
  <p style="text-align: left; margin-bottom: 6px; margin-top: 16px; font-size: 13px;">'.$title.'</p>
  
  <p style="text-align: right; margin-bottom: 2px; font-size: 11px;"><i>Amount in BDT</i></p>
  <table>
	<thead>
	<tr>
	  <th style="background-color: #f3e3fa; text-align: center; width: 10%; font-size: 12px;"><b>SN</b></th>
	  <th style="background-color: #f3e3fa; text-align: center; width: 15%; font-size: 12px;"><b>Transaction Date</b></th>	  
	  <th style="background-color: #f3e3fa; text-align: right; width: 15%; font-size: 12px;"><b>Gross Amount</b></th>
	  <th style="background-color: #f3e3fa; text-align: right; width: 15%; font-size: 12px;"><b>VAT</b></th>
	  <th style="background-color: #f3e3fa; text-align: right; width: 15%; font-size: 12px;"><b>Income Tax</b></th>
	  
	  <th style="background-color: #f3e3fa; text-align: right; width: 15%; font-size: 12px;"><b>Net Amount</b></th>
	  <th style="background-color: #f3e3fa; text-align: right; width: 15%; font-size: 12px;"><b>Total</b></th>
	</tr>
	<tr>
	  <th style="background-color: #f3e3fa; text-align: center; width: 10%; font-size: 12px;"><b>(1)</b></th>
	  <th style="background-color: #f3e3fa; text-align: center; width: 15%; font-size: 12px;"><b>(2)</b></th>	  
	  <th style="background-color: #f3e3fa; text-align: center; width: 15%; font-size: 12px;"><b>(3)</b></th>
	  <th style="background-color: #f3e3fa; text-align: center; width: 15%; font-size: 12px;"><b>(4)</b></th>
	  <th style="background-color: #f3e3fa; text-align: center; width: 15%; font-size: 12px;"><b>5</b></th>
	  
	  <th style="background-color: #f3e3fa; text-align: center; width: 15%; font-size: 12px;"><b>(3-4-5)</b></th>
	  <th style="background-color: #f3e3fa; text-align: center; width: 15%; font-size: 12px;"><b>(4+5)</b></th>
	</tr>
	</thead>
					  
	<tbody>
	'.$data.'
	</tbody>
</table>
<br><br><br>
<p style="margin-bottom: 2px; font-size: 11px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Financial Management Specialist&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Project Director</p>

</body></html>';
//$mpdf->WriteHTML($header);
$pdf->WriteHTML($header);
$pdf->WriteHTML($html);
//$txt = "This tutorial is made by \n মধুসূদন সরকার ";

//$pdf->MultiCell(100,10,$txt,1,'L',0);
$pdf->Output('');
exit;
?>

    