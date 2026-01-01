<?php	

require_once('./../../config.php');

require('../../vendor/autoload.php');

function format_num($number){
	$decimals = 0;
	$num_ex = explode('.',$number);
	$decimals = isset($num_ex[1]) ? strlen($num_ex[1]) : 2 ;
	return number_format($number,$decimals);
}



$start=date("Y/m/d",strtotime($_POST['from_date']));
$end=date("Y/m/d",strtotime($_POST['to_date']));

$stdate = $_POST['from_date'];
$stdateObj = new DateTime($stdate);

If (empty($_POST['from_date']))
{
	$start=date("Y/m/d",strtotime($_settings->userdata('from_date')));
	$stdateObj = new DateTime($_settings->userdata('from_date'));
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
				'margin_header' => '6',
				'margin_top' => '22',
				'margin_bottom' => '18',
				'margin_footer' => '8',
				'deafult_font_size' => 8,
				'default_font' => 'nikosh'
			]);
//$pdf = new mPDF('', 'A4-L',8,'');

//inner join `account_list` on account_list.id = journal_items.account_id
//$dat .= '<h3><b>Advertisements details from ' .date("M d, Y",strtotime($start)).' to '.date("M d, Y",strtotime($end)).'</b></h3>';
$numrows = 0;
$acc_name = "";
$hcount = 0;
$data = "";
$openningNumRows = 0;



if (empty($_POST['account_id'])) {  
	$acc_list = $conn->query("SELECT id, name from account_list");
	while($a_list = $acc_list->fetch_assoc()){
		$ini_amt = 0;
		$acc_name = $a_list['name']. ' A/C';
		$account_id = $a_list['id']; 
		
		$balance = 0;
		$debit = 0;
		$credit = 0;
		
		$openningBalance = $conn->query("select '$start', 'Opening Balance B/D' as txtNarrative, (case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end) - (case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end) as amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE dli_type = 'Non-DLI' and account_id = '$account_id' and journal_date < '$start' group by 'Opening Balance B/D' order by journal_date asc;");
		
		$openningNumRows = $conn->query("select '$start', 'Opening Balance B/D' as txtNarrative, (case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end) - (case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end) as amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE dli_type = 'Non-DLI' and account_id = '$account_id' and journal_date < '$start' group by 'Opening Balance B/D' order by journal_date asc;")->num_rows;
		
		if ($openningNumRows > 0) {	
			while($rows = $openningBalance->fetch_assoc()){
				if ($rows["amount"] > 0) {
				  $ini_amt = $rows["amount"];
				  $data .= '<tr><td class="cl4" style="padding: 3px;">'.date("d-m-Y", strtotime($start)).'</td>';
				  $data .= '<td class="cl1">'.$rows["txtNarrative"].'</td>';
				  $data .= '<td class="cl4"></td>';
				  $data .= '<td class="cl3">'.format_num($rows["amount"]).'</td>';
				  $data .= '<td class="cl3">--</td>';
				  $balance = $balance + $rows['amount'];
				  $data .= '<td class="cl5">'.number_format($balance, 2).'</td></tr>';
				  $debit = $debit + $rows['amount'];
				}
				if ($rows["amount"] < 0) {
				  $ini_amt = $rows["amount"]*-1;
				  $data .= '<tr><td class="cl4" style="padding: 3px;">'.date("d-m-Y", strtotime($start)).'</td>';
				  $data .= '<td class="cl1">'.$rows["txtNarrative"].'</td>';
				  $data .= '<td class="cl4"></td>';
				  $data .= '<td class="cl3">--</td>';
				  $data .= '<td class="cl3">'.number_format(($rows["amount"]*-1), 2).'</td>';
				  $balance = $balance + $rows['amount'];
				  $credit = $credit + $rows['amount'];
				  $data .= '<td class="cl5">'.number_format(($balance*-1), 2).'</td></tr>';
				}
			}
		}
		
		/*
		$journals = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE account_id = '$account_id' and journal_date >= '$start' and journal_date <= '$end' UNION ALL select '$start', '', '', 'Opening Balance B/D' as txtNarrative, '', '', (case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end) - (case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end) as amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE account_id = '$account_id' and journal_date < '$start' group by 'Opening Balance B/D' order by journal_date asc;");
		
		
		$numrows = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE account_id = '$account_id' and journal_date >= '$start' and journal_date <= '$end' UNION ALL select '$start', '', '', 'Opening Balance B/D' as txtNarrative, '', '', (case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end) - (case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end) as amount, jt.account_id, jt.group_id FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE account_id = '$account_id' and journal_date < '$start' group by 'Opening Balance B/D' order by journal_date asc;")->num_rows;
		*/
		$journals = $conn->query("SELECT je.journal_date, je.debit_count, je.credit_count, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE dli_type = 'Non-DLI' and account_id = '$account_id' and journal_date >= '$start' and journal_date <= '$end' order by journal_date asc;");
		
		$numrows = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE dli_type = 'Non-DLI' and account_id = '$account_id' and journal_date >= '$start' and journal_date <= '$end' order by journal_date asc;")->num_rows;

	  if ((($openningNumRows >= 1) && ($ini_amt > 0)) || ($numrows >= 1)) {	
		while($row = $journals->fetch_assoc()){
			$j_id = $row['journal_id'];
			if (($row['debit_count'] > 1) && ($row['credit_count'] == 1) && ($row['group_id'] == 1)) {
				$dbt_amt = $row['amount'];
				$journals_item = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE dli_type = 'Non-DLI' and journal_id = '$j_id' and journal_date >= '$start' and journal_date <= '$end' and group_id = 4 order by journal_date asc;");
				while($row_j = $journals_item->fetch_assoc()){
					  $data .= '<tr><td class="cl4" style="padding: 3px;">'.date("d-m-Y", strtotime($row_j["journal_date"])).'</td>';
					  $data .= '<td class="cl1">'.$row_j["name"].'</td>';
					  $data .= '<td class="cl4">'.$row_j["voucher_number"].'</td>';
					  $data .= '<td class="cl2">'.number_format($dbt_amt, 2).'</td>';
					  $data .= '<td class="cl3">--</td>';
					  $balance = $balance + $dbt_amt;
					  $data .= '<td class="cl5">'.number_format($balance, 2).'</td></tr>';
					  $debit = $debit + $dbt_amt;
					 }
				}
				
			if (($row['debit_count'] > 1) && ($row['credit_count'] == 1) && ($row['group_id'] == 4)) {
				//$dbt_amt = $row['amount'];
				$journals_item = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE dli_type = 'Non-DLI' and journal_id = '$j_id' and journal_date >= '$start' and journal_date <= '$end' and group_id = 1 order by journal_date asc;");
				while($row_j = $journals_item->fetch_assoc()){
					  $data .= '<tr><td class="cl4" style="padding: 3px;">'.date("d-m-Y", strtotime($row_j["journal_date"])).'</td>';
					  $data .= '<td class="cl1">'.$row_j["name"].'</td>';
					  $data .= '<td class="cl4">'.$row_j["voucher_number"].'</td>';
					  $data .= '<td class="cl3">--</td>';
					  $data .= '<td class="cl2">'.format_num($row_j["amount"]).'</td>';
					  $balance = $balance - $row_j['amount'];
					  $data .= '<td class="cl5">'.number_format($balance, 2).'</td></tr>';
					  $credit = $credit + $row_j['amount'];
					 }
				}
				
			if (($row['debit_count'] == 1) && ($row['credit_count'] > 1) && ($row['group_id'] == 1)) {
				//$dbt_amt = $row['amount'];
				$journals_item = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE dli_type = 'Non-DLI' and journal_id = '$j_id' and journal_date >= '$start' and journal_date <= '$end' and group_id = 4 order by journal_date asc;");
				while($row_j = $journals_item->fetch_assoc()){
					  $data .= '<tr><td class="cl4" style="padding: 3px;">'.date("d-m-Y", strtotime($row_j["journal_date"])).'</td>';
					  $data .= '<td class="cl1">'.$row_j["name"].'</td>';
					  $data .= '<td class="cl4">'.$row_j["voucher_number"].'</td>';
					  $data .= '<td class="cl2">'.format_num($row_j["amount"]).'</td>';
					  $data .= '<td class="cl3">--</td>';
					  $balance = $balance + $row_j["amount"];
					  $data .= '<td class="cl5">'.number_format($balance, 2).'</td></tr>';
					  $debit = $debit + $row_j["amount"];
					 }
				}
				
			if (($row['debit_count'] == 1) && ($row['credit_count'] > 1) && ($row['group_id'] == 4)) {
				$dbt_amt = $row['amount'];
				$journals_item = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE dli_type = 'Non-DLI' and journal_id = '$j_id' and journal_date >= '$start' and journal_date <= '$end' and group_id = 1 order by journal_date asc;");
				while($row_j = $journals_item->fetch_assoc()){
					  $data .= '<tr><td class="cl4" style="padding: 3px;">'.date("d-m-Y", strtotime($row_j["journal_date"])).'</td>';
					  $data .= '<td class="cl1">'.$row_j["name"].'</td>';
					  $data .= '<td class="cl4">'.$row_j["voucher_number"].'</td>';
					   $data .= '<td class="cl3">--</td>';
					  $data .= '<td class="cl2">'.number_format($dbt_amt, 2).'</td>';
					  $balance = $balance - $dbt_amt;
					  $data .= '<td class="cl5">'.number_format($balance, 2).'</td></tr>';
					  $credit = $credit + $dbt_amt;
					 }
				}
				
				
			if (($row['debit_count'] == 1) && ($row['credit_count'] == 1) && ($row['group_id'] == 1)) {
				$dbt_amt = $row['amount'];
				$journals_item = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE dli_type = 'Non-DLI' and journal_id = '$j_id' and journal_date >= '$start' and journal_date <= '$end' and group_id = 4 order by journal_date asc;");
				while($row_j = $journals_item->fetch_assoc()){
					  $data .= '<tr><td class="cl4" style="padding: 3px;">'.date("d-m-Y", strtotime($row_j["journal_date"])).'</td>';
					  $data .= '<td class="cl1">'.$row_j["name"].'</td>';
					  $data .= '<td class="cl4">'.$row_j["voucher_number"].'</td>';
					  $data .= '<td class="cl2">'.number_format($dbt_amt, 2).'</td>';
					  $data .= '<td class="cl3">--</td>';
					  $balance = $balance + $dbt_amt;
					  $data .= '<td class="cl5">'.number_format($balance, 2).'</td></tr>';
					  $debit = $debit + $dbt_amt;
					 }
				}	
				
			if (($row['debit_count'] == 1) && ($row['credit_count'] == 1) && ($row['group_id'] == 4)) {
				$dbt_amt = $row['amount'];
				$journals_item = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE dli_type = 'Non-DLI' and journal_id = '$j_id' and journal_date >= '$start' and journal_date <= '$end' and group_id = 1 order by journal_date asc;");
				while($row_j = $journals_item->fetch_assoc()){
					  $data .= '<tr><td class="cl4" style="padding: 3px;">'.date("d-m-Y", strtotime($row_j["journal_date"])).'</td>';
					  $data .= '<td class="cl1">'.$row_j["name"].'</td>';
					  $data .= '<td class="cl4">'.$row_j["voucher_number"].'</td>';
					  $data .= '<td class="cl3">--</td>';
					  $data .= '<td class="cl2">'.number_format($dbt_amt,2).'</td>';
					  $balance = $balance - $dbt_amt;
					  if ($account_id == 99)
						$data .= '<td class="cl5">'.number_format(($balance*-1), 2).'</td></tr>';
					  else
						$data .= '<td class="cl5">'.number_format($balance, 2).'</td></tr>';
					 }
					 $credit = $credit + $dbt_amt;
				}	
			}
			
			
		if ((($debit != 0) || ($credit != 0)) && ($account_id != 99)) {
				$data .= '<tr><td class="cl4" colspan="3" style="padding: 3px; background-color: #f3e3fa;"><b>Total</b></td>';
				if ($debit == 0)
					$data .= '<td class="cl3" style="background-color: #f3e3fa;"><b>--</b></td>';
				else
					$data .= '<td class="cl3" style="background-color: #f3e3fa;"><b>'.number_format($debit, 2).'</b></td>';
				if ($credit == 0)
					$data .= '<td class="cl3" style="background-color: #f3e3fa;"><b>--</b></td>';
				else
					$data .= '<td class="cl3" style="background-color: #f3e3fa;"><b>'.number_format($credit, 2).'</b></td>';
				
				$data .= '<td class="cl5" style="background-color: #f3e3fa;"><b>'.number_format($balance, 2).'</b></td></tr>';
			}
		

//trigger_error($data, E_USER_NOTICE);



$header = '<!--mpdf
<htmlpageheader name="letterheader">
		<div style="font-size: 10pt; text-align: center; padding-top: 1mm; font-family: nikosh; ">
		<h3 style="margin-bottom: 2px;">==== Ledger (Non-DLI)====</h3>
		<h5 style="margin-top: 2px; border-bottom: 1px solid #000000;"><b>Reporting Period:  &nbsp;&nbsp;' .date("M d, Y",strtotime($start)).' -- '.date("M d, Y",strtotime($end)).'</b></h5>
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
	width: 35%;
	text-align: left;
}
.cl2 {
	width: 15%;
	text-align: right;
}
.cl3 {
	width: 15%;
	text-align: right;
}

.cl5 {
	width: 15%;
	text-align: right;
}

.cl4 {
	width: 10%;
	text-align: center;
}

table, th, td {
  border: 1px solid #d9d0f7;
  border-collapse: collapse;
  width: 100%;
  padding: 5px;
  font-size: 11px;
}
/*  
.row {
  margin-left: 0px;
  margin-right: -20px;
}

.column {
  float: left;
  width: 49%;
  padding: 0px;
}

.row::after {
  content: "";
  clear: both;
  display: table;
}
*/
</style>
  </head>
  <body>
    <p style="text-align: left; padding-top: 10px; padding-bottom: -15px; font-size: 11px;"><i><b>'.$acc_name.'</b></i></p>
	<p style="text-align: right; margin-bottom: 2px; font-size: 11px;"><i>Amount in BDT</i></p>
	  <table>
		<thead>
		<tr>
		  <th class="cl4" style="background-color: #f3e3fa;"><b>Date</b></th>
		  <th class="cl1" style="background-color: #f3e3fa;"><b>Particulars</b></th>
		  <th class="cl4" style="background-color: #f3e3fa;"><b>V. No</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>Debit Amt</b></th>
		  <th class="cl3" style="background-color: #f3e3fa;"><b>Credit Amt</b></th>
		  <th class="cl5" style="background-color: #f3e3fa;"><b>Balance</b></th>
		</tr>
		</thead>		  
		<tbody>
		'.$data.'
		</tbody>
	   </table>
</body>
</html>';
//$mpdf->WriteHTML($header);
//if ($numrows != 0) {
	if ($hcount == 0) {
		$pdf->WriteHTML($header);
		$hcount = $hcount + 1;
	}
	$pdf->WriteHTML($html);
} // if ($numrows > 0)
$data = "";
} //while Line No 59 all accounts
$pdf->Output('');
exit;
} //account Id = null
else {
$account_id = $_POST['account_id'];


$pdf = new \Mpdf\Mpdf([
				'mode' => 'utf-8',
				'format' => 'A4',
				'orientation' => 'P',
				'margin_header' => '6',
				'margin_top' => '22',
				'margin_bottom' => '18',
				'margin_footer' => '8',
				'deafult_font_size' => 8,
				'default_font' => 'nikosh'
			]);
//$pdf = new mPDF('', 'A4-L',8,'');

//inner join `account_list` on account_list.id = journal_items.account_id
//$dat .= '<h3><b>Advertisements details from ' .date("M d, Y",strtotime($start)).' to '.date("M d, Y",strtotime($end)).'</b></h3>';
$numrows = 0;

$acc_name = "";
$acc_name_q = $conn->query("SELECT name from account_list where id = '$account_id '");
while($r = $acc_name_q->fetch_assoc()){
	$acc_name = $r['name']. ' A/C';
}


$data = "";
$balance = 0;
$debit = 0;
$credit = 0;


$openningBalance = $conn->query("select '$start', 'Opening Balance B/D' as txtNarrative, (case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end) - (case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end) as amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE dli_type = 'Non-DLI' and account_id = '$account_id' and journal_date < '$start' group by 'Opening Balance B/D' order by journal_date asc;");
		
$openningNumRows = $conn->query("select '$start', 'Opening Balance B/D' as txtNarrative, (case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end) - (case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end) as amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE dli_type = 'Non-DLI' and account_id = '$account_id' and journal_date < '$start' group by 'Opening Balance B/D' order by journal_date asc;")->num_rows;

	if ($openningNumRows > 0) {	
			while($rows = $openningBalance->fetch_assoc()){
				if ($rows["amount"] > 0) {
				  $data .= '<tr><td class="cl4" style="padding: 3px;">'.date("d-m-Y", strtotime($start)).'</td>';
				  $data .= '<td class="cl1">'.$rows["txtNarrative"].'</td>';
				  $data .= '<td class="cl4"></td>';
				  $data .= '<td class="cl3">'.format_num($rows["amount"]).'</td>';
				  $data .= '<td class="cl3">--</td>';
				  $balance = $balance + $rows['amount'];
				  $data .= '<td class="cl5">'.number_format($balance, 2).'</td></tr>';
				  $debit = $debit + $rows['amount'];
				}
				if ($rows["amount"] < 0) {
				  $data .= '<tr><td class="cl4" style="padding: 3px;">'.date("d-m-Y", strtotime($start)).'</td>';
				  $data .= '<td class="cl1">'.$rows["txtNarrative"].'</td>';
				  $data .= '<td class="cl4"></td>';
				  $data .= '<td class="cl3">--</td>';
				  $data .= '<td class="cl3">'.format_num($rows["amount"]*-1).'</td>';
				  $balance = $balance + $rows['amount'];
				  $credit = $credit + $rows['amount'];
				  $data .= '<td class="cl5">'.number_format(($balance*-1), 2).'</td></tr>';
				}
			}
		}


$journals = $conn->query("SELECT je.journal_date, je.debit_count, je.credit_count, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE dli_type = 'Non-DLI' and account_id = '$account_id' and journal_date >= '$start' and journal_date <= '$end' order by journal_date asc;");
		
$numrows = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE dli_type = 'Non-DLI' and account_id = '$account_id' and journal_date >= '$start' and journal_date <= '$end' order by journal_date asc;")->num_rows;

while($row = $journals->fetch_assoc()){
			$j_id = $row['journal_id'];
			if (($row['debit_count'] > 1) && ($row['credit_count'] == 1) && ($row['group_id'] == 1)) {
				$dbt_amt = $row['amount'];
				$journals_item = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE dli_type = 'Non-DLI' and journal_id = '$j_id' and journal_date >= '$start' and journal_date <= '$end' and group_id = 4 order by journal_date asc;");
				while($row_j = $journals_item->fetch_assoc()){
					  $data .= '<tr><td class="cl4" style="padding: 3px;">'.date("d-m-Y", strtotime($row_j["journal_date"])).'</td>';
					  $data .= '<td class="cl1">'.$row_j["name"].'</td>';
					  $data .= '<td class="cl4">'.$row_j["voucher_number"].'</td>';
					  $data .= '<td class="cl2">'.format_num($dbt_amt).'</td>';
					  $data .= '<td class="cl3">--</td>';
					  $balance = $balance + $dbt_amt;
					  $data .= '<td class="cl5">'.number_format($balance, 2).'</td></tr>';
					  $debit = $debit + $dbt_amt;
					 }
				}
				
			if (($row['debit_count'] > 1) && ($row['credit_count'] == 1) && ($row['group_id'] == 4)) {
				//$dbt_amt = $row['amount'];
				$journals_item = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE dli_type = 'Non-DLI' and journal_id = '$j_id' and journal_date >= '$start' and journal_date <= '$end' and group_id = 1 order by journal_date asc;");
				while($row_j = $journals_item->fetch_assoc()){
					  $data .= '<tr><td class="cl4" style="padding: 3px;">'.date("d-m-Y", strtotime($row_j["journal_date"])).'</td>';
					  $data .= '<td class="cl1">'.$row_j["name"].'</td>';
					  $data .= '<td class="cl4">'.$row_j["voucher_number"].'</td>';
					  $data .= '<td class="cl3">--</td>';
					  $data .= '<td class="cl2">'.format_num($row_j["amount"]).'</td>';
					  $balance = $balance - $row_j['amount'];
					  $data .= '<td class="cl5">'.number_format($balance, 2).'</td></tr>';
					  $credit = $credit + $row_j['amount'];
					 }
				}
				
			if (($row['debit_count'] == 1) && ($row['credit_count'] > 1) && ($row['group_id'] == 1)) {
				//$dbt_amt = $row['amount'];
				$journals_item = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE dli_type = 'Non-DLI' and journal_id = '$j_id' and journal_date >= '$start' and journal_date <= '$end' and group_id = 4 order by journal_date asc;");
				while($row_j = $journals_item->fetch_assoc()){
					  $data .= '<tr><td class="cl4" style="padding: 3px;">'.date("d-m-Y", strtotime($row_j["journal_date"])).'</td>';
					  $data .= '<td class="cl1">'.$row_j["name"].'</td>';
					  $data .= '<td class="cl4">'.$row_j["voucher_number"].'</td>';
					  $data .= '<td class="cl2">'.format_num($row_j["amount"]).'</td>';
					  $data .= '<td class="cl3">--</td>';
					  $balance = $balance + $row_j["amount"];
					  $data .= '<td class="cl5">'.number_format($balance, 2).'</td></tr>';
					  $debit = $debit + $row_j["amount"];
					 }
				}
				
			if (($row['debit_count'] == 1) && ($row['credit_count'] > 1) && ($row['group_id'] == 4)) {
				$dbt_amt = $row['amount'];
				$journals_item = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE dli_type = 'Non-DLI' and journal_id = '$j_id' and journal_date >= '$start' and journal_date <= '$end' and group_id = 1 order by journal_date asc;");
				while($row_j = $journals_item->fetch_assoc()){
					  $data .= '<tr><td class="cl4" style="padding: 3px;">'.date("d-m-Y", strtotime($row_j["journal_date"])).'</td>';
					  $data .= '<td class="cl1">'.$row_j["name"].'</td>';
					  $data .= '<td class="cl4">'.$row_j["voucher_number"].'</td>';
					   $data .= '<td class="cl3">--</td>';
					  $data .= '<td class="cl2">'.format_num($dbt_amt).'</td>';
					  $balance = $balance - $dbt_amt;
					  $data .= '<td class="cl5">'.number_format($balance, 2).'</td></tr>';
					  $credit = $credit + $dbt_amt;
					 }
				}
				
				
			if (($row['debit_count'] == 1) && ($row['credit_count'] == 1) && ($row['group_id'] == 1)) {
				$dbt_amt = $row['amount'];
				$journals_item = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE dli_type = 'Non-DLI' and journal_id = '$j_id' and journal_date >= '$start' and journal_date <= '$end' and group_id = 4 order by journal_date asc;");
				while($row_j = $journals_item->fetch_assoc()){
					  $data .= '<tr><td class="cl4" style="padding: 3px;">'.date("d-m-Y", strtotime($row_j["journal_date"])).'</td>';
					  $data .= '<td class="cl1">'.$row_j["name"].'</td>';
					  $data .= '<td class="cl4">'.$row_j["voucher_number"].'</td>';
					  $data .= '<td class="cl2">'.format_num($dbt_amt).'</td>';
					  $data .= '<td class="cl3">--</td>';
					  $balance = $balance + $dbt_amt;
					  $data .= '<td class="cl5">'.number_format($balance, 2).'</td></tr>';
					  $debit = $debit + $dbt_amt;
					 }
				}	
				
			if (($row['debit_count'] == 1) && ($row['credit_count'] == 1) && ($row['group_id'] == 4)) {
				$dbt_amt = $row['amount'];
				$journals_item = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE dli_type = 'Non-DLI' and journal_id = '$j_id' and journal_date >= '$start' and journal_date <= '$end' and group_id = 1 order by journal_date asc;");
				while($row_j = $journals_item->fetch_assoc()){
					  $data .= '<tr><td class="cl4" style="padding: 3px;">'.date("d-m-Y", strtotime($row_j["journal_date"])).'</td>';
					  $data .= '<td class="cl1">'.$row_j["name"].'</td>';
					  $data .= '<td class="cl4">'.$row_j["voucher_number"].'</td>';
					  $data .= '<td class="cl3">--</td>';
					  $data .= '<td class="cl2">'.format_num($dbt_amt).'</td>';
					  $balance = $balance - $dbt_amt;
					  if ($account_id == 99)
						$data .= '<td class="cl5">'.number_format(($balance*-1), 2).'</td></tr>';
					  else
						$data .= '<td class="cl5">'.number_format($balance, 2).'</td></tr>';
					 }
					 $credit = $credit + $dbt_amt;
				}	
			}


				
		if ((($debit != 0) || ($credit != 0)) && ($account_id != 99)) {
			$data .= '<tr><td class="cl4" colspan="3" style="padding: 3px; background-color: #f3e3fa;"><b>Total</b></td>';
			if ($debit == 0)
				$data .= '<td class="cl3" style="background-color: #f3e3fa;"><b>--</b></td>';
			else
				$data .= '<td class="cl3" style="background-color: #f3e3fa;"><b>'.number_format($debit, 2).'</b></td>';
			if ($credit == 0)
				$data .= '<td class="cl3" style="background-color: #f3e3fa;"><b>--</b></td>';
			else
				$data .= '<td class="cl3" style="background-color: #f3e3fa;"><b>'.number_format($credit, 2).'</b></td>';
			
			$data .= '<td class="cl5" style="background-color: #f3e3fa;"><b>'.number_format($balance, 2).'</b></td></tr>';
		}



$header = '<!--mpdf
<htmlpageheader name="letterheader">
		<div style="font-size: 10pt; text-align: center; padding-top: 1mm; font-family: nikosh; ">
		<h3 style="margin-bottom: 2px;">==== Ledger (Non-DLI) ====</h3>
		<h5 style="margin-top: 2px; border-bottom: 1px solid #000000;"><b>Reporting Period:  &nbsp;&nbsp;' .date("M d, Y",strtotime($start)).' -- '.date("M d, Y",strtotime($end)).'</b></h5>
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
	width: 35%;
	text-align: left;
}
.cl2 {
	width: 15%;
	text-align: right;
}
.cl3 {
	width: 15%;
	text-align: right;
}

.cl5 {
	width: 15%;
	text-align: right;
}

.cl4 {
	width: 10%;
	text-align: center;
}

table, th, td {
  border: 1px solid #d9d0f7;
  border-collapse: collapse;
  width: 100%;
  padding: 5px;
  font-size: 11px;
}
/*
.row {
  margin-left: 0px;
  margin-right: -20px;
}
  
.column {
  float: left;
  width: 49%;
  padding: 0px;
}

.row::after {
  content: "";
  clear: both;
  display: table;
}
*/

</style>
  </head>
  <body>
    <p style="text-align: left; padding-top: 10px; padding-bottom: -15px; font-size: 11px;"><i><b>'.$acc_name.'</b></i></p>
	<p style="text-align: right; margin-bottom: 2px; font-size: 11px;"><i>Amount in BDT</i></p>
	  <table>
		<thead>
		<tr>
		  <th class="cl4" style="background-color: #f3e3fa;"><b>Date</b></th>
		  <th class="cl1" style="background-color: #f3e3fa;"><b>Particulars</b></th>
		  <th class="cl4" style="background-color: #f3e3fa;"><b>V. No</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>Debit Amt</b></th>
		  <th class="cl3" style="background-color: #f3e3fa;"><b>Credit Amt</b></th>
		  <th class="cl5" style="background-color: #f3e3fa;"><b>Balance</b></th>
		</tr>
		</thead>		  
		<tbody>
		'.$data.'
		</tbody>
	   </table>
</body>
</html>';
if ($numrows != 0) {
	$pdf->WriteHTML($header);
	$pdf->WriteHTML($html);
}
$pdf->Output('');
exit;	
}

?>

    