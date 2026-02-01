<?php

require_once('./../../../config.php');
require_once('../../../vendor/autoload.php');

$pdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A3',
                'orientation' => 'L',
                'margin_header' => 6,
                'margin_top' => 32,
                'margin_bottom' => 12,
                'margin_footer' => 8,
                'default_font_size' => 8,
                'default_font' => 'nikosh'
]);


$data = "";

$details = $conn->query("SELECT * FROM `services` order by id ASC");

while($row_d = $details->fetch_assoc()){
    $sn++;

$data .= '<tr>'
	.'<td rowspan="3" style="text-align: center;  font-size: 150px;">'.$sn.'</td>'
	.'<td rowspan="3" style="text-align: center;  font-size: 150px;">'.$row_d['package_no'].'</td>'
	.'<td rowspan="3" style="text-align: center;  font-size: 150px;">'.$row_d['package_descrip'].'</td>'
	.'<td rowspan="3" style="text-align: center;  font-size: 150px;">'.$row_d['unit'].'</td>'
	.'<td rowspan="3" style="text-align: center;  font-size: 150px;">'.$row_d['quantity'].'</td>'
	.'<td style="text-align: center;  font-size: 150px;">Plan</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['procuement_type1'].'</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['tender_approval1'].'</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['source_funds1'].'</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['cost_lac1'].'</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['invitation_prequalific1'].'</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['invitation_tender1'].'</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['signing_contract1'].'</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['conpletion_contract1'].'</td>'
	.'<td rowspan="3" style="text-align: center;  font-size: 150px;">'.$row_d['name_address'].'</td>'
	.'<td rowspan="3" style="text-align: center;  font-size: 150px;">'.$row_d['firm_focal'].'</td>'
	.'<td rowspan="3" style="text-align: center;  font-size: 150px;">'.$row_d['paid_date'].'</td>'
	.'<td rowspan="3" style="text-align: center;  font-size: 150px;">'.$row_d['financial_progress'].'</td></tr>'
	.'<td rowspan="3" style="text-align: center;  font-size: 150px;">'.$row_d['physical_progress'].'</td></tr>';

	$data .= '<tr>'
	.'<td style="text-align: center;  font-size: 150px;">Actual</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['procuement_type2'].'</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['tender_approval2'].'</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['source_funds2'].'</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['cost_lac2'].'</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['invitation_prequalific2'].'</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['invitation_tender2'].'</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['signing_contract2'].'</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['conpletion_contract2'].'</td></tr>';

	$data .= '<tr>'
	.'<td style="text-align: center;  font-size: 150px;">Deviation</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['procuement_type3'].'</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['tender_approval3'].'</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['source_funds3'].'</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['cost_lac3'].'</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['invitation_prequalific3'].'</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['invitation_tender3'].'</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['signing_contract3'].'</td>'
	.'<td style="text-align: center;  font-size: 150px;">'.$row_d['conpletion_contract3'].'</td></tr>';
	
}

$header = '<!--mpdf
<htmlpageheader name="letterheader">
		<div style="font-size: 10pt; text-align: center; padding-top: 1mm; font-family: nikosh; ">
		<h3 style="margin-bottom: -1px;">Total Procurement Plan for Development Project</h3>
		<h4 style="margin-top: -5px; margin-bottom: -1px;">Services Reports</h4>
		<h4 style="margin-bottom: 2px;"></h4>
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
  border: 1px solid #000000;
  border-collapse: collapse;
  width: 100%;
  padding: 5px;
}

</style>
  </head><body>
    
  <p style="text-align: right; margin-bottom: 2px; font-size: 11px;"><i></i></p>
  <table>
	<thead>
	<tr>
	  <th rowspan="2" style="background-color: #f3e3fa; text-align: center;  font-size: 150px;"><b>SN</b></th>
	  <th rowspan="2" style="background-color: #f3e3fa; text-align: center;  font-size: 150px;"><b>Package No.</b></th>
	  <th rowspan="2" style="background-color: #f3e3fa; text-align: center;  font-size: 150px;"><b>Description of Package<br>as Per RDPP (Service)</b></th>
	  <th rowspan="2" style="background-color: #f3e3fa; text-align: center;  font-size: 150px;"><b>Unit</b></th>
	  <th rowspan="2" style="background-color: #f3e3fa; text-align: center;  font-size: 150px;"><b>Quantity</b></th>
	  <th rowspan="2" style="background-color: #f3e3fa; text-align: center;  font-size: 150px;"><b></b></th>
	  <th rowspan="2" style="background-color: #f3e3fa; text-align: center;  font-size: 150px;"><b>Procurement<br>Method & Type</b></th>
	  <th rowspan="2" style="background-color: #f3e3fa; text-align: center;  font-size: 150px;"><b>Tender Approval<br>Authority</b></th>
	  <th rowspan="2" style="background-color: #f3e3fa; text-align: center;  font-size: 150px;"><b>Source of<br>Funds</b></th>
	  <th rowspan="2" style="background-color: #f3e3fa; text-align: center;  font-size: 150px;"><b>Est. Cost<br>(Lac Taka)</b></th>
	  <th colspan="4" style="background-color: #f3e3fa; text-align: center;  font-size: 150px;"><b>As per DPP/RDPP Plan and Actual Dates</b></th>
	  <th rowspan="2" style="background-color: #f3e3fa; text-align: center;  font-size: 150px;"><b>Name & Address of Firm awarded</b></th>
	  <th rowspan="2" style="background-color: #f3e3fa; text-align: center;  font-size: 150px;"><b>Firm Focal Person (Name and Mobile Number)</b></th>
	  <th rowspan="2" style="background-color: #f3e3fa; text-align: center;  font-size: 150px;"><b>Paid till date</b></th>
	  <th rowspan="2" style="background-color: #f3e3fa; text-align: center;  font-size: 150px;"><b>Financial Progress</b></th>
	  <th rowspan="2" style="background-color: #f3e3fa; text-align: center;  font-size: 150px;"><b>Physical Progress</b></th>
	</tr>
	<tr>
	  <th style="background-color: #f3e3fa; text-align: center;  font-size: 150px;"><b>Invitation Prequalification<br>(If Required)</b></th>
	  <th style="background-color: #f3e3fa; text-align: center;  font-size: 150px;"><b>Invitation of Tender</b></th>
	  <th style="background-color: #f3e3fa; text-align: center;  font-size: 150px;"><b>Signing of Contract</b></th>
	  <th style="background-color: #f3e3fa; text-align: center;  font-size: 150px;"><b>Completion of Contract</b></th>
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