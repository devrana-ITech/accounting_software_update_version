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
	$title .= 'Newspaper Name: <b>'.$t_row_d['payee_name'].'</b>';
}

//$details = $conn->query("SELECT p.payee_name, p.designation, contract_date, sum(gross_amt) as grossamt FROM `journal_entries` je inner join payee p on p.id = je.payee_name where payee_type =1 and journal_date >= '$start' and journal_date <= '$end' group by je.payee_name order by grossamt desc");

$details = $conn->query("SELECT journal_date, description, gross_amt FROM `journal_entries` je inner join payee p on p.id = je.payee_name where je.payee_name = '$account_id' and journal_date >= '$start' and journal_date <= '$end' order by journal_date ASC");

//ind_noncon_firm_report_pdf.php  p.designation, contract_date

	
$sn = 0;
$net_amt = 0;


while($row_d = $details->fetch_assoc()){
	$sn++;
	
	$net_amt = $net_amt + $row_d['gross_amt'];
	
	
		
	$data .= '<tr>'
	.'<td style="text-align: center; width: 10%; font-size: 12px;">'.$sn.'</td>'
	.'<td style="text-align: center; width: 20%; font-size: 12px;">'.date("d-m-Y", strtotime($row_d['journal_date'])).'</td>'
	.'<td style="text-align: left; width: 50%; font-size: 12px;">'.$row_d['description'].'</td>'
	.'<td style="text-align: right; width: 20%; font-size: 12px;">'.format_num($row_d['gross_amt']).'</td></tr>';
	
}
$data .= '<tr><td style="text-align: left; font-size: 12px; width: 80%;" colspan="3"><b>Total</b></td>
<td style="text-align: right; font-size: 12px; width: 20%;"><b>'.format_num($net_amt).'</b></td></tr>';



function format_num($number){
	$decimals = 0;
	$num_ex = explode('.',$number);
	$decimals = isset($num_ex[1]) ? strlen($num_ex[1]) : 2 ;
	return number_format($number,$decimals);
}


$header = '<!--mpdf
<htmlpageheader name="letterheader">
		<div style="font-size: 10pt; text-align: center; padding-top: 1mm; font-family: nikosh; ">
		<h3 style="margin-bottom: 2px;">===Payments Details===</h3>
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

table, th, td {
  border: 1px solid #d9d0f7;
  border-collapse: collapse;
  width: 100%;
  padding: 5px;
}

</style>
  </head><body>
  <p style="text-align: left; margin-bottom: 6px; font-size: 13px;">'.$title.'</p>
  
  <p style="text-align: right; margin-bottom: 2px; font-size: 11px;"><i>Amount in BDT</i></p>
  <table>
	<thead>
	<tr>
	  <th style="background-color: #f3e3fa; text-align: center; width: 10%; font-size: 12px;"><b>SN</b></th>
	  <th style="background-color: #f3e3fa; text-align: center; width: 20%; font-size: 12px;"><b>Transaction Date</b></th>
	  <th style="background-color: #f3e3fa; text-align: center; width: 50%; font-size: 12px;"><b>Narration</b></th>
	  <th style="background-color: #f3e3fa; text-align: right; width: 20%; font-size: 12px;"><b>Amount</b></th>
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

    