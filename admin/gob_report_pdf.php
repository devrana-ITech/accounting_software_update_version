<?php	
//echo '<script>alert("Welcome to Geeks for Geeks")</script>';	
require_once('./../config.php');

//require('../mpdf/mpdf.php');

require('../vendor/autoload.php');


$year=$_REQUEST['year_id'];

If ($year == 0)
{
	$start = "2021-07-01";
	$end = "2022-06-30";
}

If ($year == 1)
{
	$start = "2022-07-01";
	$end = "2023-06-30";
}

If ($year == 2)
{
	$start = "2023-07-01";
	$end = "2024-06-30";
}

If ($year == 3)
{
	$start = "2024-07-01";
	$end = "2025-06-30";
}
If ($year == 5)
{
	$start = "2025-07-01";
	$end = "2026-06-30";
}

If ($year == 4)
{
	$start = "2021-07-01";
	$end = date("Y-m-d");
}

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

//$details = $conn->query("SELECT p.payee_name, p.designation, contract_date, sum(gross_amt) as grossamt FROM `journal_entries` je inner join payee p on p.id = je.payee_name where payee_type =1 and journal_date >= '$start' and journal_date <= '$end' group by je.payee_name order by grossamt desc");

$details = $conn->query("SELECT acc_code, name, sum(amount) as sumamount FROM `journal_entries` je inner join journal_items jt on jt.journal_id = je.id inner join account_list a on a.id = jt.account_id where dli_type = 'GoB' and group_id = 1 and journal_date >= '$start' and journal_date <= '$end' group by a.id order by acc_code ASC");

//ind_noncon_firm_report_pdf.php  p.designation, contract_date .date("d-m-Y", strtotime($row_d['journal_date']))

	
$sn = 0;
$net_amt = 0;


while($row_d = $details->fetch_assoc()){
	$sn++;
	
	$net_amt = $net_amt + $row_d['sumamount'];
				
	$data .= '<tr>'
	.'<td style="text-align: center; width: 10%; font-size: 12px;">'.$sn.'</td>'
	.'<td style="text-align: center; width: 20%; font-size: 12px;">'.$row_d['acc_code'].'</td>'
	.'<td style="text-align: left; width: 50%; font-size: 12px;">'.$row_d['name'].'</td>'
	.'<td style="text-align: right; width: 20%; font-size: 12px;">'.format_num($row_d['sumamount']).'</td></tr>';
	
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
		<h3 style="margin-bottom: 2px;">===GoB Expenditure Details===</h3>
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
    
  <p style="text-align: right; margin-bottom: 2px; font-size: 11px;"><i>Amount in BDT</i></p>
  <table>
	<thead>
	<tr>
	  <th style="background-color: #f3e3fa; text-align: center; width: 10%; font-size: 12px;"><b>SN</b></th>
	  <th style="background-color: #f3e3fa; text-align: center; width: 20%; font-size: 12px;"><b>Account Code</b></th>
	  <th style="background-color: #f3e3fa; text-align: left; width: 50%; font-size: 12px;"><b>Account Name</b></th>
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

    