<?php	

require_once('./../config.php');

require('../vendor/autoload.php');

function format_num($number){
	$decimals = 0;
	$num_ex = explode('.',$number);
	$decimals = isset($num_ex[1]) ? strlen($num_ex[1]) : 2 ;
	return number_format($number,$decimals);
}

$pdf = new \Mpdf\Mpdf([
				'mode' => 'utf-8',
				'format' => 'A4',
				'orientation' => 'P',
				'margin_header' => '15',
				'margin_top' => '35',
				'margin_bottom' => '12',
				'margin_footer' => '8',
				'deafult_font_size' => 12
			]);

$data = "";


$data .= '<p style="text-align: center; font-size:18px;"><b>Summary Report</b></p>';

////Outstanding Advances to be Accounted  -->

$journals = $conn->query("SELECT (case when sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) is null then 0 else sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE (account_id = 51) and journal_type = 'cv' and dli_type = 'Non-DLI';");

$nondli_received = 0;

if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$nondli_received = $nondli_received + $row['Crt'];
	   }
	}
}

$nondli_expenses = 0;
$item_list = $conn->query("SELECT * from exp_group where id <= 8;"); 
while($row_item = $item_list->fetch_assoc()){
	$item_code = $row_item['id'];
	$journals = $conn->query("SELECT ((case when sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE eg.id = '$item_code' and dli_type = 'Non-DLI' group by eg.id;");
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$nondli_expenses = $nondli_expenses + $row['Crt'];
			}
		}
	}
}


$nondli_bal = 0;

$nondli_bal = $nondli_received - $nondli_expenses;

////////////D L I

$journals = $conn->query("SELECT (case when sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) is null then 0 else sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE (account_id = 51) and journal_type = 'cv' and dli_type = 'DLI';");

$dli_received = 0;

if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$dli_received = $dli_received + $row['Crt'];
	   }
	}
}

$dli_expenses = 0;
$item_list = $conn->query("SELECT * from exp_group where id <= 8;"); 
while($row_item = $item_list->fetch_assoc()){
	$item_code = $row_item['id'];
	$journals = $conn->query("SELECT ((case when sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE eg.id = '$item_code' and dli_type = 'DLI' group by eg.id;");
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$dli_expenses = $dli_expenses + $row['Crt'];
		   }
		}
	}
}


$dli_bal = 0;

$dli_bal = $dli_received - $dli_expenses;


$nondli_dli_recived = 0;
$nondli_dli_expenses = 0;


$grand_total = 0;

$nondli_dli_recived = $dli_received + $nondli_received;
$nondli_dli_expenses = $dli_expenses + $nondli_expenses;

$grand_total = $nondli_dli_recived - $nondli_dli_expenses;


$data .= '
	<p style="text-align: center; margin-bottom: -100px; font-size:16px;"><b>Outstanding Advances to be Accounted</b></p>
	<p style="text-align: right; margin-bottom: 0px; font-size: 14px;"><i>(Amount in BDT)</i></p>
        <table>
			<thead>
				<tr>
				  <th style="background-color: #d48104; width: 16%; font-size: 12px; color: white; text-align: left;"><b>Fund Type</b></th>
				  <th style="background-color: #d48104; width: 28%; font-size: 12px; color: white; text-align: right;"><b>Cumulative Advances</b></th>
				  <th style="background-color: #d48104; width: 28%; font-size: 12px; color: white; text-align: right;"><b>Cumulative Expenditures</b></th>
				  <th style="background-color: #d48104; width: 28%; font-size: 12px; color: white; text-align: right;"><b>Total (Outstanding Advances)</b></th>
				</tr>
			</thead>		  
			<tbody>
				<tr>
				  <td style="background-color: #faf5fc; width: 16%; font-size: 12px; text-align: left;"><b>Non-DLI</b></td>
				  <td style="background-color: #faf5fc; width: 16%; font-size: 12px; text-align: right;"><b>'.number_format($nondli_received, 2).'</b></td>
				  <td style="background-color: #faf5fc; width: 16%; font-size: 12px; text-align: right;"><b>'.number_format($nondli_expenses, 2).'</b></td>  
				  <td style="background-color: #faf5fc; width: 16%; font-size: 12px; text-align: right;"><b>'.number_format($nondli_bal, 2).'</b></td>
				</tr>
				<tr>
				  <td style="background-color: #f2ebf5; width: 16%; font-size: 12px; text-align: left;"><b>DLI</b></td>
				  <td style="background-color: #f2ebf5; width: 16%; font-size: 12px; text-align: right;"><b>'.number_format($dli_received, 2).'</b></td>
				  <td style="background-color: #f2ebf5; width: 16%; font-size: 12px; text-align: right;"><b>'.number_format($dli_expenses, 2).'</b></td>
				  <td style="background-color: #f2ebf5; width: 16%; font-size: 12px; text-align: right;"><b>'.number_format($dli_bal, 2).'</b></td>
				</tr>
				<tr>
				  <td style="background-color: #ffe6e6; width: 16%; font-size: 12px; text-align: left;"><b>Total</b></td>
				  <td style="background-color: #ffe6e6; width: 16%; font-size: 12px; text-align: right;"><b>'.number_format($nondli_dli_recived, 2).'</b></td>
				  <td style="background-color: #ffe6e6; width: 16%; font-size: 12px; text-align: right;"><b>'.number_format($nondli_dli_expenses, 2).'</b></td>
				  <td style="background-color: #ffe6e6; width: 16%; font-size: 12px; text-align: right;"><b>'.number_format($grand_total, 2).'</b></td>
				</tr>
			</tbody>
	   </table>';

//////////////////////////////////////////////

////FY-wise Fund Received  -->

$journals = $conn->query("SELECT (case when sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) is null then 0 else sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 2 and (account_id = 51) and journal_type = 'cv' and dli_type = 'Non-DLI';");

$nondli_2 = 0;
$nondli_total = 0;

if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$nondli_2 = $nondli_2 + $row['Crt'];
	   }
	}
}

$journals = $conn->query("SELECT (case when sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) is null then 0 else sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 3 and (account_id = 51) and journal_type = 'cv' and dli_type = 'Non-DLI';");

$nondli_3 = 0;
if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$nondli_3 = $nondli_3 + $row['Crt'];
	   }
	}
}

$journals = $conn->query("SELECT (case when sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) is null then 0 else sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 4 and (account_id = 51) and journal_type = 'cv' and dli_type = 'Non-DLI';");

$nondli_4 = 0;
if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$nondli_4 = $nondli_4 + $row['Crt'];
	   }
	}
}

$nondli_total = $nondli_2 + $nondli_3 + $nondli_4;


////////////D L I

$journals = $conn->query("SELECT (case when sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) is null then 0 else sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 2 and (account_id = 51) and journal_type = 'cv' and dli_type = 'DLI';");

$dli_2 = 0;
$dli_total = 0;

if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$dli_2 = $dli_2 + $row['Crt'];
	   }
	}
}

$journals = $conn->query("SELECT (case when sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) is null then 0 else sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 3 and (account_id = 51) and journal_type = 'cv' and dli_type = 'DLI';");

$dli_3 = 0;
if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$dli_3 = $dli_3 + $row['Crt'];
	   }
	}
}

$journals = $conn->query("SELECT (case when sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) is null then 0 else sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 4 and (account_id = 51) and journal_type = 'cv' and dli_type = 'DLI';");

$dli_4 = 0;
if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$dli_4 = $dli_4 + $row['Crt'];
	   }
	}
}

$dli_total = $dli_2 + $dli_3 + $dli_4;

$nondli_dli2 = 0;
$nondli_dli3 = 0;
$nondli_dli4 = 0;

$grand_total = 0;

$nondli_dli2 = $nondli_2 + $dli_2;
$nondli_dli3 = $nondli_3 + $dli_3;
$nondli_dli4 = $nondli_4 + $dli_4;

$grand_total = $nondli_dli2 + $nondli_dli3 + $nondli_dli4;

$data .= '
<p style="text-align: center; margin-top: 30px; font-size:16px;"><b>Financial Year-wise Fund Advances</b></p>
<p style="text-align: right; margin-top: -50px; margin-bottom: 0px; font-size: 14px;"><i>(Amount in BDT)</i></p>

	<table>
		<thead>
			<tr>
			  <th style="background-color: #d48104; width: 12%; color: white; font-size: 12px; text-align: left;"><b>Fund Type</b></th>
			  <th style="background-color: #d48104; width: 22%; color: white; font-size: 12px; text-align: right;"><b>2021-22</b></th>
			  <th style="background-color: #d48104; width: 22%; color: white; font-size: 12px; text-align: right;"><b>2022-23</b></th>
			  <th style="background-color: #d48104; width: 22%; color: white; font-size: 12px; text-align: right;"><b>2023-24</b></th>
			  <th style="background-color: #d48104; width: 22%; color: white; font-size: 12px; text-align: right;"><b>Total</b></th>
			</tr>
		</thead>
		<tbody>
				<tr>
				  <td style="background-color: #faf5fc; width: 12%; font-size: 12px; text-align: left;"><b>Non-DLI</b></td>
				  <td style="background-color: #faf5fc; width: 22%; font-size: 12px; text-align: right;"><b>';
				  if ($nondli_2 <= 0) $data .= '--'; else $data .= ''.number_format($nondli_2, 2).'</b></td>';
				  $data .= '<td style="background-color: #faf5fc; width: 22%; font-size: 12px; text-align: right;"><b>';
				  if ($nondli_3 <= 0) $data .= '--'; else $data .= ''.number_format($nondli_3, 2).'</b></td>';
				  $data .= '<td style="background-color: #faf5fc; width: 22%; font-size: 12px; text-align: right;"><b>';
				  if ($nondli_4 <= 0) $data .= '--'; else $data .= ''.number_format($nondli_4, 2).'</b></td>';
				  $data .= '<td style="background-color: #faf5fc; width: 22%; font-size: 12px; text-align: right;"><b>';
				  if ($nondli_total <= 0) $data .= '--'; else $data .= ''.number_format($nondli_total, 2).'</a></td>
				</tr>
		</tbody>
   </table>';







$header = '<!--mpdf
<htmlpageheader name="letterheader">
	<div style="font-size: 10pt; text-align: center; padding-top: 1mm;">
		<h3 style="margin-bottom: 5px;">Bangabandhu Sheikh Mujib Shilpa Nagar (BSMSN) Development Project</h3>
		<h4 style="margin-top: -5px; margin-bottom: -2px; border-bottom: 1px solid #000000;">Credit No. IDA-6676 BD </h4>
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
	width: 7%;
	text-align: center;
	font-size: 22px;
}
.cl2 {
	width: 55%;
	text-align: right;
	font-size: 22px;
}
.cl3 {
	width: 18%;
	text-align: right;
}

table, th, td {
  border: 1px solid #d9d0f7;
  border-collapse: collapse;
  width: 100%;
  padding: 5px;
  
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
	 '.$data.'
	<br><br><br>
	<p style="margin-bottom: 2px; font-size: 11px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Financial Management Specialist&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Project Director</p>
  </body>
</html>';
$pdf->WriteHTML($header);
$pdf->WriteHTML($html);
$pdf->Output('');
exit;
?>