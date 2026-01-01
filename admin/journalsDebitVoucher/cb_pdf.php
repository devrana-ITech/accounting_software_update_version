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

$pdf = new \Mpdf\Mpdf([
				'mode' => 'utf-8',
				'format' => 'A4',
				'orientation' => 'L',
				'margin_header' => '6',
				'margin_top' => '22',
				'margin_bottom' => '12',
				'margin_footer' => '8',
				'deafult_font_size' => 8,
				'default_font' => 'nikosh'
			]);
//$pdf = new mPDF('', 'A4-L',8,'');

//inner join `account_list` on account_list.id = journal_items.account_id
//$dat .= '<h3><b>Advertisements details from ' .date("M d, Y",strtotime($start)).' to '.date("M d, Y",strtotime($end)).'</b></h3>';
$data_dr = "";
$data_cr = "";

$bank_dli = 0;
$bank_nondli = 0;
$cash_dli = 0;
$cash_nondli = 0;
$total_bank = 0;
$total_cash = 0;


$counter_bank_dli = 0;
$counter_bank_nondli = 0;
$counter_cash_dli = 0;
$counter_cash_nondli = 0;



$counter_row_debit = 0;
$counter_row_credit = 0;

$bank_total_dr = 0;
$cash_total_dr = 0;

$bank_total_cr = 0;
$cash_total_cr = 0;


$amt_total = 0;

$data = "";

$journals = $conn->query("SELECT al.name, je.journal_date, je.dli_type, ((case when sum(case when account_id >= 51 and account_id <= 52 and group_id = 1 then jt.amount end) is null then 0 else sum(case when account_id >= 51 and account_id <= 52 and group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date < '$start' and account_id >=51 and account_id <= 52 group by account_id order by account_id asc;");

while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$data .= '<tr><td class="cl4" style="padding: 3px; height: 25px;">'.date("d-m-Y", strtotime($start)).'</td>';
			$data .= '<td colspan="2" style="padding: 3px;">Opening Balance B/D </td>';
			if ($row['name'] == 'Bank') {
				$data .= '<td class="cl4" style="padding: 3px; text-align: right;">'.format_num($row['Crt']).'</td>';
				$data .= '<td class="cl4" style="padding: 3px; text-align: right">--</td></tr>';
				$bank_total_dr += $row['Crt'];
			}
			if ($row['name'] == 'Cash') {
				$data .= '<td class="cl4" style="padding: 3px; text-align: right;">--</td>';
				$data .= '<td class="cl4" style="padding: 3px; text-align: right;">'.format_num($row['Crt']).'</td></tr>';
				$cash_total_dr += $row['Crt'];
			}
			$counter_row_debit = $counter_row_debit + 1;
	   }
	}

$journals = $conn->query("SELECT je.journal_date, je.debit_count, je.credit_count, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE account_id >= 51 and account_id <= 52 and journal_date >= '$start' and journal_date <= '$end' order by journal_date asc;");
		
$numrows = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE account_id >= 51 and account_id <= 52 and journal_date >= '$start' and journal_date <= '$end' order by journal_date asc;")->num_rows;

if ($numrows > 0){	
	while($row = $journals->fetch_assoc()){
			$j_id = $row['journal_id'];
			if (($row['debit_count'] > 1) && ($row['credit_count'] == 1) && ($row['group_id'] == 1)) {
				$dbt_amt = $row['amount'];
				$journals_item = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_id = '$j_id' and journal_date >= '$start' and journal_date <= '$end' and group_id = 4 order by journal_date asc;");
				while($row_j = $journals_item->fetch_assoc()){
					  $data .= '<tr><td class="cl4" style="padding: 3px; height: 24px;">'.date("d-m-Y", strtotime($row_j["journal_date"])).'</td>';
					  $data .= '<td class="cl1">'.$row_j["name"].'</td>';
					  $data .= '<td class="cl2">'.$row_j["voucher_number"].'</td>';
					  if ($row['name'] == 'Bank') {
							$data .= '<td class="cl4" style="padding: 3px; text-align: right;">'.format_num($dbt_amt).'</td>';
							$data .= '<td class="cl4" style="padding: 3px; text-align: right;">--</td></tr>';
							$bank_total_dr += $dbt_amt;
						}
					  if ($row['name'] == 'Cash') {
							$data .= '<td class="cl4" style="padding: 3px; text-align: right;">--</td>';
							$data .= '<td class="cl4" style="padding: 3px; text-align: right;">'.format_num($dbt_amt).'</td></tr>';
							$cash_total_dr += $dbt_amt;
					  }
					  $counter_row_debit = $counter_row_debit + 1;
					 }
				}

			if (($row['debit_count'] == 1) && ($row['credit_count'] > 1) && ($row['group_id'] == 1)) {
				//$dbt_amt = $row['amount'];
				$journals_item = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_id = '$j_id' and journal_date >= '$start' and journal_date <= '$end' and group_id = 4 order by journal_date asc;");
				while($row_j = $journals_item->fetch_assoc()){
					  $data .= '<tr><td class="cl4" style="padding: 3px;">'.date("d-m-Y", strtotime($row_j["journal_date"])).'</td>';
					  $data .= '<td class="cl1">'.$row_j["name"].'</td>';
					  $data .= '<td class="cl2">'.$row_j["voucher_number"].'</td>';
					  if ($row['name'] == 'Bank') {
							$data .= '<td class="cl4" style="padding: 3px; text-align: right;">'.format_num($row_j['amount']).'</td>';
							$data .= '<td class="cl4" style="padding: 3px; text-align: right;">--</td></tr>';
							$bank_total_dr += $row_j['amount'];
						}
					  if ($row['name'] == 'Cash') {
							$data .= '<td class="cl4" style="padding: 3px; text-align: right;">--</td>';
							$data .= '<td class="cl4" style="padding: 3px; text-align: right;">'.format_num($row_j['amount']).'</td></tr>';
							$cash_total_dr += $row_j['amount'];
					  }
					  $counter_row_debit = $counter_row_debit + 1;
					 }
				}
				
			if (($row['debit_count'] == 1) && ($row['credit_count'] == 1) && ($row['group_id'] == 1)) {
				$dbt_amt = $row['amount'];
				$journals_item = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_id = '$j_id' and journal_date >= '$start' and journal_date <= '$end' and group_id = 4 order by journal_date asc;");
				while($row_j = $journals_item->fetch_assoc()){
					  $data .= '<tr><td class="cl4" style="padding: 3px;">'.date("d-m-Y", strtotime($row_j["journal_date"])).'</td>';
					  $data .= '<td class="cl1">'.$row_j["name"].'</td>';
					  $data .= '<td class="cl2">'.$row_j["voucher_number"].'</td>';
					  if ($row['name'] == 'Bank') {
							$data .= '<td class="cl4" style="padding: 3px; text-align: right;">'.format_num($dbt_amt).'</td>';
							$data .= '<td class="cl4" style="padding: 3px; text-align: right;">--</td></tr>';
							$bank_total_dr += $dbt_amt;
						}
					  if ($row['name'] == 'Cash') {
							$data .= '<td class="cl4" style="padding: 3px; text-align: right;">--</td>';
							$data .= '<td class="cl4" style="padding: 3px; text-align: right;">'.format_num($dbt_amt).'</td></tr>';
							$cash_total_dr += $dbt_amt;
					  }
					  $counter_row_debit = $counter_row_debit + 1;
					 }
				}
	}
}

 
$data_dr .= '<tr>'
	  .'<td style="background-color: #f3e3fa;" colspan="3"><b>Total<b></td>'
	  .'<td style="background-color: #f3e3fa;" class="cl5"><b>'.format_num($bank_total_dr).'</b></td>' 
	  .'<td style="background-color: #f3e3fa;" class="cl5"><b>'.format_num($cash_total_dr).'</b></td></tr>'; 
	  
/* #######################################################
####################### CREDIT PART Begins ################################### */

$data_dv = "";

$journals = $conn->query("SELECT je.journal_date, je.debit_count, je.credit_count, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE account_id >= 51 and account_id <= 52 and journal_date >= '$start' and journal_date <= '$end' order by journal_date asc;");
		
$numrows = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE account_id >= 51 and account_id <= 52 and journal_date >= '$start' and journal_date <= '$end' order by journal_date asc;")->num_rows;

	  if ($numrows > 0){	
		while($row = $journals->fetch_assoc()){
			$j_id = $row['journal_id'];
			//$account_name = $row['name'];
			
			if (($row['debit_count'] > 1) && ($row['credit_count'] == 1) && ($row['group_id'] == 4)) {
				//$dbt_amt = $row['amount'];
				$journals_item = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_id = '$j_id' and journal_date >= '$start' and journal_date <= '$end' and group_id = 1 order by journal_date asc;");
				while($row_j = $journals_item->fetch_assoc()){
					  $data_dv .= '<tr><td class="cl4" style="padding: 3px;">'.date("d-m-Y", strtotime($row_j["journal_date"])).'</td>';
					  $data_dv .= '<td class="cl1">'.$row_j['name']. ', '.$row_j['payee_name'].'</td>';
					  $data_dv .= '<td style="text-align: center;" class="cl2">'.$row_j['voucher_number'].'</td>';
					 if ($row['name'] == 'Bank') {
							$data_dv .= '<td class="cl4" style="padding: 3px; text-align: right;">'.format_num($row_j['amount']).'</td>';
							$data_dv .= '<td class="cl4" style="padding: 3px; text-align: right;">--</td></tr>';
							$bank_total_cr += $row_j['amount'];
						}
					  if ($row['name'] == 'Cash') {
							$data_dv .= '<td class="cl4" style="padding: 3px; text-align: right;">--</td>';
							$data_dv .= '<td class="cl4" style="padding: 3px; text-align: right;">'.format_num($row_j['amount']).'</td></tr>';
							$cash_total_cr += $row_j['amount'];
					  }
					  $counter_row_credit = $counter_row_credit + 1;
				}
			}				
				
			
			if (($row['debit_count'] == 1) && ($row['credit_count'] > 1) && ($row['group_id'] == 4)) {
				$dbt_amt = $row['amount'];
				$journals_item = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_id = '$j_id' and journal_date >= '$start' and journal_date <= '$end' and group_id = 1 order by journal_date asc;");
				while($row_j = $journals_item->fetch_assoc()){
					  $data_dv .= '<tr><td class="cl4" style="padding: 3px;">'.date("d-m-Y", strtotime($row_j["journal_date"])).'</td>';
					  $data_dv .= '<td class="cl1">'.$row_j['name']. ', '.$row_j['payee_name'].'</td>';
					  $data_dv .= '<td style="text-align: center;" class="cl2">'.$row_j['voucher_number'].'</td>';
					 if ($row['name'] == 'Bank') {
							$data_dv .= '<td class="cl4" style="padding: 3px; text-align: right;">'.format_num($dbt_amt).'</td>';
							$data_dv .= '<td class="cl4" style="padding: 3px; text-align: right;">--</td></tr>';
							$bank_total_cr += $dbt_amt;
						}
					  if ($row['name'] == 'Cash') {
							$data_dv .= '<td class="cl4" style="padding: 3px; text-align: right;">--</td>';
							$data_dv .= '<td class="cl4" style="padding: 3px; text-align: right;">'.format_num($dbt_amt).'</td></tr>';
							$cash_total_cr += $dbt_amt;
					  }
					  $counter_row_credit = $counter_row_credit + 1;
				}
			}
				
				
			if (($row['debit_count'] == 1) && ($row['credit_count'] == 1) && ($row['group_id'] == 4)) {
				$dbt_amt = $row['amount'];
				$journals_item = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_id = '$j_id' and journal_date >= '$start' and journal_date <= '$end' and group_id = 1 order by journal_date asc;");
				while($row_j = $journals_item->fetch_assoc()){
					 $data_dv .= '<tr><td class="cl4" style="padding: 3px;">'.date("d-m-Y", strtotime($row_j["journal_date"])).'</td>';
					  $data_dv .= '<td class="cl1">'.$row_j['name']. ', '.$row_j['payee_name'].'</td>';
					  $data_dv .= '<td style="text-align: center;" class="cl2">'.$row_j['voucher_number'].'</td>';
					 if ($row['name'] == 'Bank') {
							$data_dv .= '<td class="cl4" style="padding: 3px; text-align: right;">'.format_num($dbt_amt).'</td>';
							$data_dv .= '<td class="cl4" style="padding: 3px; text-align: right;">--</td></tr>';
							$bank_total_cr += $dbt_amt;
						}
					  if ($row['name'] == 'Cash') {
							$data_dv .= '<td class="cl4" style="padding: 3px; text-align: right;">--</td>';
							$data_dv .= '<td class="cl4" style="padding: 3px; text-align: right;">'.format_num($dbt_amt).'</td></tr>';
							$cash_total_cr += $dbt_amt;
					  }
					  $counter_row_credit = $counter_row_credit + 1;
				}	
			}
		}
		$bank_balance = $bank_total_dr - $bank_total_cr;
		$cash_balance = $cash_total_dr - $cash_total_cr;
		if ($bank_balance != 0) {
			$data_dv .= '<tr><td class="cl4" style="padding: 3px; height: 24px;">'.date("d-m-Y", strtotime($end)).'</td>';
			$data_dv .= '<td class="cl1" colspan="2" style="padding: 3px;">Closing Balance C/D </td>';
			$data_dv .= '<td class="cl5" style="padding: 3px;">'.format_num($bank_balance).'</td>';
			$data_dv .= '<td class="cl5" style="padding: 3px;">--</td></tr>';
			$counter_row_credit = $counter_row_credit + 1;
		}
		if ($cash_balance != 0) {
			$data_dv .= '<tr><td class="cl4" style="padding: 3px; height: 24px;">'.date("d-m-Y", strtotime($end)).'</td>';
			$data_dv .= '<td class="cl1" colspan="2" style="padding: 3px;">Closing Balance C/D </td>';
			$data_dv .= '<td class="cl5" style="padding: 3px;">--</td>';
			$data_dv .= '<td class="cl5" style="padding: 3px;">'.format_num($cash_balance).'</td></tr>';
			$counter_row_credit = $counter_row_credit + 1;
		}
	}

$diff = $counter_row_credit - $counter_row_debit;
$diff = $diff * 1.5;

while($diff > 0){
	$data .= '<tr><td class="cl4" style="padding: 3px; height: 24px;"></td>';
	$data .= '<td class="cl1" style="padding: 3px;"></td>';
	$data .= '<td class="cl2" style="text-align: center;" style="padding: 3px;" class="cl2"></td>';
	$data .= '<td class="cl3" style="text-align: center;"  style="padding: 3px;" class="cl3"></td>';
	$data .= '<td class="cl5" style="padding: 3px;"></td></tr>';
	$diff = $diff - 1;
}


$data_cr .= '<tr>'
  .'<td style="background-color: #b7f7db;" colspan="3"><b>Total<b></td>'
  .'<td style="background-color: #b7f7db;" class="cl5"><b>'.format_num($bank_total_cr + $bank_balance).'</b></td>' 
  .'<td style="background-color: #b7f7db;" class="cl5"><b>'.format_num($cash_total_cr + $cash_balance).'</b></td></tr>';



$header = '<!--mpdf
<htmlpageheader name="letterheader">
		<div style="font-size: 10pt; text-align: center; padding-top: 1mm; font-family: nikosh; ">
		<h3 style="margin-bottom: 2px;">==== Cash Book ====</h3>
		<h5 style="margin-top: 2px; border-bottom: 1px solid #000000;"><b>Reporting Period:  &nbsp;&nbsp;' .date("M d, Y",strtotime($start)).' -- '.date("M d, Y",strtotime($end)).'</b></h5>
		</div>
		</htmlpageheader>

		<htmlpagefooter name="letterfooter2">
			<div class="container" style="border-top: 1px solid #000000; font-size: 9pt; font-style: italic; padding-top: 1mm; font-family: sans-serif; ">
				<div class="column1" style="text-align: left">
					BSMSN Development Project
				</div>
				<div class="column1" style="text-align: center">
					Page {PAGENO} of {nbpg}
				</div>
				
				<div class="column1" style="text-align: right">
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
			
			.column1 {
			  float: left;
			  width: 33.3%;
			 /* font-family: nikosh; */
			}
</style>';



$html = '
<html><head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body{font-family: nikosh}
.cl1 {
	width: 33.9%;
	
}
.cl2 {
	width: 10%;
	text-align: center;
}
.cl3 {
	width: 15%;
	text-align: center;
}

.cl5 {
	width: 20%;
	text-align: right;
}


.cl4 {
	width: 15%;
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
  
.column {
  float: left;
  width: 49%;
  padding: 0px;
}

/* Clearfix (clear floats) */
.row::after {
  content: "";
  clear: both;
  display: table;
}

</style>
  </head>
  <body>
  <p style="margin-bottom: 2px; padding-top: 15px; font-size: 11px;"><i><b>Dr</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Cr</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Amount in BDT</i></p>
  <div class="row">
    <div class="column">
	  <table>
		<thead>
		<tr>
		  <th class="cl4" style="background-color: #f3e3fa;"><b>Date</b></th>
		  <th class="cl1" style="background-color: #f3e3fa;"><b>Particulars</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>Voucher Number</b></th>
		  <th class="cl5" style="background-color: #f3e3fa; text-align: right;"><b>Bank</b></th>
		  <th class="cl5" style="background-color: #f3e3fa;"><b>Cash</b></th>
		</tr>
		</thead>		  
		<tbody>
		'.$data.'
		</tbody>
	   </table>
    </div>
	
	<div class="column">
	  <table>
		<thead>
		<tr>
		  <th class="cl4" style="background-color: #b7f7db;"><b>Date</b></th>
		  <th class="cl1" style="background-color: #b7f7db;"><b>Particulars</b></th>
		  <th class="cl2" style="background-color: #b7f7db;"><b>Voucher Number</b></th>
		  <th class="cl5" style="background-color: #b7f7db; text-align: right;"><b>Bank</b></th>
		  <th class="cl5" style="background-color: #b7f7db;"><b>Cash</b></th>
		</tr>
		</thead>		  
		<tbody>
		'.$data_dv.'
		</tbody>
	   </table>
    </div>
   </div>
   

   <div class="row">
    <div class="column">
	  <table>		  
		<tbody>
		'.$data_dr.'
		</tbody>
	   </table>
    </div>
	
	<div class="column">
	  <table>		  
		<tbody>
		'.$data_cr.'
		</tbody>
	   </table>
    </div>
   </div>
   
   
   
   
</body>
</html>';
//$mpdf->WriteHTML($header);
$pdf->WriteHTML($header);
$pdf->WriteHTML($html);
//$txt = "This tutorial is made by \n মধুসূদন সরকার ";

//$pdf->MultiCell(100,10,$txt,1,'L',0);
$pdf->Output('');
exit;
?>

    