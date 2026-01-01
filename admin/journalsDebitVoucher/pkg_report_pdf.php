<?php	
//echo '<script>alert("Welcome to Geeks for Geeks")</script>';	


require_once('./../../config.php');

require('../../vendor/autoload.php');



/*
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
	$end = date("Y-m-d");
}

If ($year == 4)
{
	$start = "2021-07-01";
	$end = date("Y-m-d");
}
*/

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

$details = $conn->query("SELECT pack_name, pack_details, p.payee_name as pname, sum(gross_amt) as g_amount FROM `journal_entries` je inner join payee p on p.id = je.payee_name inner join pkg pg on pg.id = je.pkg_number where payee_flag = 1 and pkg_flag = 1 group by pkg_number, p.payee_name order by pack_name ASC;");

//ind_noncon_firm_report_pdf.php  p.designation, contract_date .date("d-m-Y", strtotime($row_d['journal_date']))

	
$sn = 0;
$net_amt = 0;//38,795,404.00


while($row_d = $details->fetch_assoc()){
	$sn++;
	
	$net_amt = $net_amt + $row_d['g_amount'];
				
	$data .= '<tr>'
	.'<td style="text-align: center; width: 8%; font-size: 12px;">'.$sn.'</td>'
	.'<td style="text-align: left; width: 15%; font-size: 12px;">'.$row_d['pack_name'].'</td>'
	.'<td style="text-align: left; width: 25%; font-size: 12px;">'.$row_d['pack_details'].'</td>'
	.'<td style="text-align: left; width: 30%; font-size: 12px;">'.$row_d['pname'].'</td>'
	.'<td style="text-align: right; width: 15%; font-size: 12px;">'.format_num($row_d['g_amount']).'</td></tr>';
	
}
$data .= '<tr><td style="text-align: left; font-size: 12px; width: 78%;" colspan="4"><b>Total</b></td>
<td style="text-align: right; font-size: 12px; width: 22%;"><b>'.format_num($net_amt).'</b></td></tr>';



function format_num($number){
	$decimals = 0;
	$num_ex = explode('.',$number);
	$decimals = isset($num_ex[1]) ? strlen($num_ex[1]) : 2 ;
	return number_format($number,$decimals);
}
//<h5 style="margin-top: 2px;"><b>Reporting Period:  &nbsp;&nbsp;' .date("M d, Y",strtotime($start)).' -- '.date("M d, Y",strtotime($end)).'</b></h5>

$header = '<!--mpdf
<htmlpageheader name="letterheader">
		<div style="font-size: 10pt; text-align: center; padding-top: 1mm; font-family: nikosh; ">
		<h3 style="margin-bottom: -1px;">National Special Economic Zone (NSEZ) Development Project</h3>
		<h4 style="margin-top: -5px; margin-bottom: -1px;">Credit No. IDA-6676 BD</h4>
		<h4 style="margin-bottom: 2px;">===Package-wise Expenditure===</h4>
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
	  <th style="background-color: #f3e3fa; text-align: center; width: 8%; font-size: 12px;"><b>SN</b></th>
	  <th style="background-color: #f3e3fa; text-align: center; width: 15%; font-size: 12px;"><b>Package Name</b></th>
	  <th style="background-color: #f3e3fa; text-align: left; width: 25%; font-size: 12px;"><b>Package Details</b></th>
	  <th style="background-color: #f3e3fa; text-align: left; width: 30%; font-size: 12px;"><b>Payee Name</b></th>
	  <th style="background-color: #f3e3fa; text-align: right; width: 22%; font-size: 12px;"><b>Amount</b></th>
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

    