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


$total_qtr = 0;
$total_cum = 0;


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

$comp_list = $conn->query("SELECT * from tbl_category where id <= 4;");
while($row_item = $comp_list->fetch_assoc()){
	$cat_name = $row_item['cat_name'];
	$cat_id = $row_item['id'];
	$cat_code = $row_item['cat_id'];
if ($cat_id != 2) {
	$journals = $conn->query("SELECT al.name, je.dli_type, (case when sum(case when tc.id = '$cat_id' and group_id = 1 then jt.amount end) is null then 0 else sum(case when tc.id = '$cat_id' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id inner join tbl_category tc on tc.id = je.category_number WHERE journal_date >= '$start' and journal_date <= '$end' and tc.id = '$cat_id' and dli_type = '$d_type' and journal_type = 'dv' group by tc.id;");

	if ($cat_id == 3) {
		$data .= '<tr><td class="cl1" style="padding: 3px;" rowspan="3">'.$cat_code.'</td>';
		$data .= '<td class="cl4" style="padding: 3px;">'.$cat_name.'</td><td class="cl2"></td><td class="cl2"></td><td class="cl2"></td><td class="cl2"></td><td class="cl2"></td><td class="cl2"></td></tr>';
		$journals_3 = $conn->query("SELECT al.name, je.dli_type, (case when sum(case when al.id = 33 and group_id = 1 then jt.amount end) is null then 0 else sum(case when al.id = 33 and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date >= '$start' and journal_date <= '$end' and al.id = 33 and dli_type = '$d_type' and al.iufr_flag = 1 and journal_type = 'dv' group by al.id;");
		
			if ($journals_3->num_rows > 0){
			while($row_3 = $journals_3->fetch_assoc()){
			   if ($row_3['Crt'] != 0) {
					$data .= '<tr><td class="cl4" style="padding: 3px;">(i) Voucher Program</td>';
					$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row_3['Crt']/1000000, 2).'</td>';
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
					$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row_3['Crt']/1000000, 2).'</td>';
					$total_qtr = $total_qtr + $row_3['Crt'];
			   }else{
					$data .= '<tr><td class="cl4" style="padding: 3px;">(i) Voucher Program</td>';
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				}
			}
			}else{
				$data .= '<tr><td class="cl4" style="padding: 3px;">(i) Voucher Program</td>';
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			}
		
		$journals_3 = $conn->query("SELECT al.name, je.dli_type, (case when sum(case when al.id = 33 and group_id = 1 then jt.amount end) is null then 0 else sum(case when al.id = 33 and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date <= '$end' and al.id = 33 and dli_type = '$d_type' and al.iufr_flag = 1 and journal_type = 'dv' group by al.id;");
		
		if ($journals_3->num_rows > 0){
			while($row_3 = $journals_3->fetch_assoc()){
			   if ($row_3['Crt'] != 0) {
					$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row_3['Crt']/1000000, 2).'</td>';
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
					$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row_3['Crt']/1000000, 2).'</td></tr>';
					$total_cum = $total_cum + $row_3['Crt'];
			   }else{
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
					$data .= '<td class="cl2" style="padding: 3px;">--</td></tr>';
				}
			}
			}else{
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$data .= '<td class="cl2" style="padding: 3px;">--</td></tr>';
			}
		
		
		
		$journals_3 = $conn->query("SELECT al.name, je.dli_type, (case when sum(case when al.id = 55 and group_id = 1 then jt.amount end) is null then 0 else sum(case when al.id = 55 and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date >= '$start' and journal_date <= '$end' and al.id = 55 and dli_type = '$d_type' and al.iufr_flag = 1 and journal_type = 'dv' group by al.id;");
		
			if ($journals_3->num_rows > 0){
			while($row_3 = $journals_3->fetch_assoc()){
			   if ($row_3['Crt'] != 0) {
					$data .= '<tr><td class="cl4" style="padding: 3px;">(ii) Grant Program</td>';
					$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row_3['Crt']/1000000, 2).'</td>';
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
					$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row_3['Crt']/1000000, 2).'</td>';
					$total_qtr = $total_qtr + $row_3['Crt'];
			   }else{
					$data .= '<tr><td class="cl4" style="padding: 3px;">(ii) Grant Program</td>';
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				}
			}
			}else{
				$data .= '<tr><td class="cl4" style="padding: 3px;">(ii) Grant Program</td>';
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			}
			
		$journals_3 = $conn->query("SELECT al.name, je.dli_type, (case when sum(case when al.id = 55 and group_id = 1 then jt.amount end) is null then 0 else sum(case when al.id = 55 and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date <= '$end' and al.id = 55 and dli_type = '$d_type' and journal_type = 'dv' group by al.id;");
		
		if ($journals_3->num_rows > 0){
			while($row_3 = $journals_3->fetch_assoc()){
			   if ($row_3['Crt'] != 0) {
					$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row_3['Crt']/1000000, 2).'</td>';
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
					$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row_3['Crt']/1000000, 2).'</td></tr>';
					$total_cum = $total_cum + $row_3['Crt'];
			   }else{
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
					$data .= '<td class="cl2" style="padding: 3px;">--</td>';
					$data .= '<td class="cl2" style="padding: 3px;">--</td></tr>';
				}
			}
			}else{
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$data .= '<td class="cl2" style="padding: 3px;">--</td></tr>';
			}
		
	} else {
	
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$data .= '<tr><td class="cl1" style="padding: 3px;">'.$cat_code.'</td>';
				$data .= '<td class="cl4" style="padding: 3px;">'.$cat_name.'</td>';
				$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td>';
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td>';
				$total_qtr = $total_qtr + $row['Crt'];
		   }else{
				$data .= '<tr><td class="cl1" style="padding: 3px;">'.$cat_code.'</td>';
				$data .= '<td class="cl4" style="padding: 3px;">'.$cat_name.'</td>';
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			}
		}
	}else{
		$data .= '<tr><td class="cl1" style="padding: 3px;">'.$cat_code.'</td>';
		$data .= '<td class="cl4" style="padding: 3px;">'.$cat_name.'</td>';
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	}
   
   
	$journals = $conn->query("SELECT al.name, je.dli_type, (case when sum(case when tc.id = '$cat_id' and group_id = 1 then jt.amount end) is null then 0 else sum(case when tc.id = '$cat_id' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id inner join tbl_category tc on tc.id = je.category_number WHERE journal_date <= '$end' and tc.id = '$cat_id' and dli_type = '$d_type' and journal_type = 'dv' group by tc.id;");
	
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td>';
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td></tr>';
				$total_cum = $total_cum + $row['Crt'];
		   }else{
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$data .= '<td class="cl2" style="padding: 3px;">--</td></tr>';
			}
		}
	}else{
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
		$data .= '<td class="cl2" style="padding: 3px;">--</td></tr>';
	}
  }
} //if ($cat_id != 2)
}

$data .= '<tr><td class="cl1" style="padding: 3px;" colspan="2"><b>Total</b></td>';
if (number_format($total_qtr/1000000, 2) == 0){
	$data .= '<td class="cl2" style="padding: 3px;"><b>--</b></td>';
}else{
	$data .= '<td class="cl2" style="padding: 3px;"><b>'.number_format($total_qtr/1000000, 2).'</b></td>';
}
$data .= '<td class="cl2" style="padding: 3px;">--</td>';
if (number_format($total_qtr/1000000, 2) == 0){
	$data .= '<td class="cl2" style="padding: 3px;"><b>--</b></td>';
}else{
	$data .= '<td class="cl2" style="padding: 3px;"><b>'.number_format($total_qtr/1000000, 2).'</b></td>';
}
$data .= '<td class="cl2" style="padding: 3px;"><b>'.number_format($total_cum/1000000, 2).'</b></td>';
$data .= '<td class="cl2" style="padding: 3px;">--</td>';
$data .= '<td class="cl2" style="padding: 3px;"><b>'.number_format($total_cum/1000000, 2).'</b></td></tr>';



$header = '<!--mpdf
<htmlpageheader name="letterheader">
	<div style="font-size: 10pt; text-align: center; padding-top: 1mm; font-family: nikosh; ">
		<h3 style="margin-bottom: -1px;">National Special Economic Zone (NSEZ) Development Project</h3>
		<h4 style="margin-top: -5px; margin-bottom: -1px;">Credit No. IDA-6676 BD ('.$d_type.' Part)</h4>
		<h4 style="margin-top: -5px; margin-bottom: -1px;">1C. Project Cash Withdrawals (Actual Disbursements)</h4>
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
	width: 5%;
	text-align: center;
}
.cl2 {
	width: 12%;
	text-align: right;
}
.cl3 {
	width: 12%;
	text-align: right;
}

.cl4 {
	width: 23%;
	text-align: left;
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
		  <th class="cl1" style="background-color: #f3e3fa;" rowspan="3"><b>Category Number</b></th>
		  <th class="cl4" style="background-color: #f3e3fa;" rowspan="3"><b>Category Description</b></th>
		  <th style="background-color: #f3e3fa;" colspan="6"><b>Eligible Expenditure</b></th>
		</tr>
		<tr>
		  <th style="background-color: #f3e3fa;" colspan="3"><b>For the Quarter</b></th>
		  <th style="background-color: #f3e3fa;" colspan="3"><b>Cumulative to Date</b></th>
		</tr>
		<tr>
		  <th class="cl2" style="background-color: #f3e3fa; text-align: center;"><b>Paid from DA</b></th>
		  <th class="cl3" style="background-color: #f3e3fa; text-align: center;"><b>Paid from DP/SC</b></th>
		  <th class="cl2" style="background-color: #f3e3fa; text-align: right;"><b>Total</b></th>
		  <th class="cl2" style="background-color: #f3e3fa; text-align: center;"><b>Paid from DA</b></th>
		  <th class="cl3" style="background-color: #f3e3fa; text-align: center;"><b>Paid from DP/SC</b></th>
		  <th class="cl2" style="background-color: #f3e3fa; text-align: right;"><b>Total</b></th>
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

    