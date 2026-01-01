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
				'orientation' => 'P',
				'margin_header' => '13',
				'margin_top' => '32',
				'margin_bottom' => '12',
				'margin_footer' => '8',
				'deafult_font_size' => 8,
				'default_font' => 'nikosh'
			]);
			
	$debit = 0; $credit = 0;
	$data = "";
	
	$journals = $conn->query("SELECT al.name, je.dli_type, ((case when sum(case when account_id >= 51 and account_id <= 52 and group_id = 1 then jt.amount end) is null then 0 else sum(case when account_id >= 51 and account_id <= 52 and group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date < '$start' and account_id >=51 and account_id <= 52 group by account_id, dli_type order by account_id asc;"); //and journal_type='cv' and journal_type = 'dv' and journal_type = 'dv'
	
	$data .= '<tr><td class="cl1" style="padding: 3px;"><b>Fund Received Details</b></td>';
	$data .= '<td class="cl2" style="padding: 3px;"></td>';
	$data .= '<td class="cl3" style="padding: 3px;"></td></tr>';
	
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$data .= '<tr><td class="cl1" style="padding: 3px;">Opening Balance: '.$row['name']. '--'.$row['dli_type']. '</td>';
			$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			$data .= '<td class="cl3" style="padding: 3px;">'.format_num($row['Crt']).'</td></tr>';
			$credit = $credit + $row['Crt'];
	   }
	}
	
	//$journals = $conn->query("SELECT al.name, je.dli_type, (case when sum(case when account_id >= 51 and account_id <= 52 and journal_type='cv' and group_id = 1 then jt.amount end) is null then 0 else sum(case when account_id >= 51 and account_id <= 52 and journal_type='cv' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE account_id >= 51 and account_id <= 52 and journal_date >= '$start' and journal_date <= '$end' group by account_id, dli_type order by account_id asc;"); Without Cash & Bank transfer
	
	//$num_rows = $conn->query("SELECT al.name, je.dli_type, (case when sum(case when account_id >= 51 and account_id <= 52 and journal_type='cv' and group_id = 1 then jt.amount end) is null then 0 else sum(case when account_id >= 51 and account_id <= 52 and journal_type='cv' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE account_id >= 51 and account_id <= 52 and journal_date >= '$start' and journal_date <= '$end' group by account_id, dli_type order by account_id asc;")->num_rows; Without Cash & Bank transfer
	
	$journals = $conn->query("SELECT al.name, je.dli_type, ((case when sum(case when account_id >= 51 and account_id <= 52 and group_id = 1 and new = 1 then jt.amount end) is null then 0 else sum(case when account_id >= 51 and account_id <= 52 and group_id = 1 and new = 1 then jt.amount end) end) - (case when sum(case when account_id >= 51 and account_id <= 52 and group_id = 4 and new = 1 then jt.amount end) is null then 0 else sum(case when account_id >= 51 and account_id <= 52 and group_id = 4 and new = 1 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE account_id >= 51 and account_id <= 52 and journal_date >= '$start' and journal_date <= '$end' group by account_id, dli_type order by account_id asc;");
	
	$num_rows = $conn->query("SELECT al.name, je.dli_type, ((case when sum(case when account_id >= 51 and account_id <= 52 and group_id = 1 then jt.amount end) is null then 0 else sum(case when account_id >= 51 and account_id <= 52 and group_id = 1 then jt.amount end) end) - (case when sum(case when account_id >= 51 and account_id <= 52 and group_id = 4 and new = 1 then jt.amount end) is null then 0 else sum(case when account_id >= 51 and account_id <= 52 and group_id = 4 and new = 1 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE account_id >= 51 and account_id <= 52 and journal_date >= '$start' and journal_date <= '$end' group by account_id, dli_type order by account_id asc;")->num_rows;
	
	
	if ($num_rows >= 1 ) {
		$data .= '<tr><td class="cl1" style="padding: 3px;"><b>Fund Received during the period</b></td>';
		$data .= '<td class="cl2" style="padding: 3px;"></td>';
		$data .= '<td class="cl3" style="padding: 3px;"></td></tr>';
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$data .= '<tr><td class="cl1" style="padding: 3px;">'.$row['name']. ': ' .$row['dli_type']. '</td>';
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$data .= '<td class="cl3" style="padding: 3px;">'.format_num($row['Crt']).'</td></tr>';
				$credit = $credit + $row['Crt'];
		   }
		}
	}
	$journals_dli = $conn->query("SELECT al.name, je.dli_type, case when ((case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end)-(case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end))>0 then ((case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end)-(case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) else 0 end as Dbt, case when ((case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end)-(case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end))<0 then ((case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end)-(case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end))*-1 else 0 end as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date >= '$start' and journal_date <= '$end' and account_id <> 51 and account_id <> 52 and account_id <> 99 and dli_type = 'DLI' group by account_id order by account_id asc;"); //account_id <> 51 and account_id <> 52 and 
	
	$num_rows_dli = $conn->query("SELECT al.name, je.dli_type, case when ((case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end)-(case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end))>0 then ((case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end)-(case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) else 0 end as Dbt, case when ((case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end)-(case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end))<0 then ((case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end)-(case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end))*-1 else 0 end as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date >= '$start' and journal_date <= '$end' and account_id <> 51 and account_id <> 52 and account_id <> 99 and dli_type = 'DLI' group by account_id order by account_id asc;")->num_rows; //account_id <> 51 and account_id <> 52 and
	
	$journals_nondli = $conn->query("SELECT al.name, je.dli_type, case when ((case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end)-(case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end))>0 then ((case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end)-(case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) else 0 end as Dbt, case when ((case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end)-(case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end))<0 then ((case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end)-(case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end))*-1 else 0 end as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date >= '$start' and journal_date <= '$end' and account_id <> 51 and account_id <> 52 and account_id <> 99 and dli_type = 'Non-DLI' group by account_id order by account_id asc;"); //account_id <> 51 and account_id <> 52 and 
	
	$num_rows_nondli = $conn->query("SELECT al.name, je.dli_type, case when ((case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end)-(case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end))>0 then ((case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end)-(case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) else 0 end as Dbt, case when ((case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end)-(case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end))<0 then ((case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end)-(case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end))*-1 else 0 end as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date >= '$start' and journal_date <= '$end' and account_id <> 51 and account_id <> 52 and account_id <> 99 and dli_type = 'Non-DLI' group by account_id order by account_id asc;")->num_rows; //account_id <> 51 and account_id <> 52 and
	
	if (($num_rows_dli > 0) || ($num_rows_nondli > 0)) {
		$data .= '<tr><td class="cl1" style="padding: 3px;"><b>Item-wise expenditure incurred during the period</b></td>';
		$data .= '<td class="cl2" style="padding: 3px;"></td>';
		$data .= '<td class="cl3" style="padding: 3px;"></td></tr>';
		
		$dli_type = 1;
		$nondli_type = 1;
		$dli_total = 0;
		$nondli_total = 0;
		$dli_counter = 0;
		$nondli_counter = 0;
		while($row = $journals_dli->fetch_assoc()){
			if (($row['dli_type'] == 'DLI') && ($dli_type == 1)) {
				$dli_type = 0;
				$data .= '<tr><td class="cl1" style="padding: 3px;"><b><i>Fund Type: DLI</i></b></td>';
				$data .= '<td class="cl2" style="padding: 3px;"></td>';
				$data .= '<td class="cl3" style="padding: 3px;"></td></tr>';
			}
		   if (($row['Dbt'] != 0) && ($row['dli_type'] == 'DLI')) {
			 $data .= '<tr><td class="cl1" style="padding: 3px;">'.$row['name'].'</td>';
			  if ($row['Dbt'] != 0) {
				$data .= '<td class="cl2">'.format_num($row['Dbt']).'</td>';
				$data .= '<td class="cl2">--</td>';
				$dli_total = $dli_total + $row['Dbt'];
				$dli_counter = $dli_counter + 1;
				$debit = $debit + $row['Dbt'];
			  }
			
			/*  if ($row['Crt'] != 0) 
				$data .= '<td class="cl3">'.format_num($row['Crt']).'</td></tr>';
				else
					$data .= '<td class="cl3">--</td></tr>'; */
		   }
		   //$credit = $credit + $row['Crt'];
		}
		if ($dli_counter > 1) {
			$data .= '<tr><td class="cl1" style="padding: 3px;"><b><i>Sub-Total expenditure -- DLI</i></b></td>';
			$data .= '<td class="cl2" style="padding: 3px;"><b>'.number_format($dli_total, 2).'</b></td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
		}
		
		
		
		while($row1 = $journals_nondli->fetch_assoc()){
			if (($row1['dli_type'] == 'Non-DLI') && ($nondli_type == 1)) {
				$nondli_type = 0;
				$data .= '<tr><td class="cl1" style="padding: 3px;"><b><i>Fund Type: Non-DLI</i></b></td>';
				$data .= '<td class="cl2" style="padding: 3px;"></td>';
				$data .= '<td class="cl3" style="padding: 3px;"></td></tr>';
			}
		   if (($row1['Dbt'] != 0) && ($row1['dli_type'] == 'Non-DLI')) {
			 $data .= '<tr><td class="cl1" style="padding: 3px;">'.$row1['name'].'</td>';
			  if ($row1['Dbt'] != 0) {
				$data .= '<td class="cl2">'.format_num($row1['Dbt']).'</td>';
				$data .= '<td class="cl2">--</td>';
				$nondli_total = $nondli_total + $row1['Dbt'];
				$nondli_counter = $nondli_counter + 1;
				$debit = $debit + $row1['Dbt']; 
			  }
		   }
		 //$credit = $credit + $row['Crt'];
		}
		
		if ($nondli_counter > 1) {
			$data .= '<tr><td class="cl1" style="padding: 3px;"><b><i>Sub-Total expenditure -- Non-DLI</i></b></td>';
			$data .= '<td class="cl2" style="padding: 3px;"><b>'.number_format($nondli_total, 2).'</b></td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
		}
		
		
		
		$data .= '<tr><td class="cl1" style="padding: 3px;"><b>Total expenditure incurred during the period</b></td>';
		$data .= '<td class="cl2" style="padding: 3px;"><b>'.number_format($debit, 2).'</b></td>';
		$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
	}
	
	
	
	
	$journals = $conn->query("SELECT al.name, je.dli_type, ((case when sum(case when account_id >=51 and account_id <= 52 and group_id = 1 then jt.amount end) is null then 0 else sum(case when account_id >=51 and account_id <= 52 and group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Dbt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date <= '$end' and account_id >=51 and account_id <= 52 group by account_id, dli_type order by account_id asc;");
	
	$num_rows = $conn->query("SELECT al.name, je.dli_type, ((case when sum(case when account_id >=51 and account_id <= 52 and group_id = 1 then jt.amount end) is null then 0 else sum(case when account_id >=51 and account_id <= 52 and group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Dbt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date <= '$end' and account_id >=51 and account_id <= 52 group by account_id, dli_type order by account_id asc;")->num_rows;
	
	if ($num_rows > 0) {
		$data .= '<tr><td class="cl1" style="padding: 3px;"><b>Bank and Cash Balance Details</b></td>';
		$data .= '<td class="cl2" style="padding: 3px;"></td>';
		$data .= '<td class="cl3" style="padding: 3px;"></td></tr>';
		$bankcashbal = 0;
		while($row = $journals->fetch_assoc()){
		   if ($row['Dbt'] != 0) {
				$data .= '<tr><td class="cl1" style="padding: 3px;">'.$row['name'].': '.$row['dli_type']. '</td>';
				$data .= '<td class="cl2" style="padding: 3px;">'.format_num($row['Dbt']).'</td>';
				$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
				$bankcashbal = $bankcashbal + $row['Dbt'];
		   }
		}
		$data .= '<tr><td class="cl1" style="padding: 3px;"><b>Total: Bank and Cash Balance</b></td>';
		$data .= '<td class="cl2" style="padding: 3px;"><b>'.number_format($bankcashbal, 2).'</b></td>';
		$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
	}
		$total = $debit + $bankcashbal;
		
		  $data .= '<tr><td class="cl1" style="padding: 3px; background-color: #f3e3fa;"><b>Total</b></td>';
		  $data .= '<td class="cl2" style="background-color: #f3e3fa;"><b>'.number_format($total, 2).'</b></td>';
		  $data .= '<td class="cl3" style="background-color: #f3e3fa;"><b>'.number_format($credit, 2).'</b></td></tr>';

//trigger_error($data, E_USER_NOTICE);



$header = '<!--mpdf
<htmlpageheader name="letterheader">
		<div style="font-size: 10pt; text-align: center; padding-top: 1mm; font-family: nikosh; ">
		<h3 style="margin-bottom: 2px;">==== Trial Balance ====</h3>
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
	width: 60%;
	text-align: Left;
}
.cl2 {
	width: 20%;
	text-align: right;
}
.cl3 {
	width: 20%;
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
	<p style="text-align: right; padding-top: 10px; margin-bottom: 2px; font-size: 11px;"><i>Amount in BDT</i></p>
	  <table>
		<thead>
		<tr>
		  <th class="cl1" style="background-color: #f3e3fa;"><b>Particulars</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>Debit Amt</b></th>
		  <th class="cl3" style="background-color: #f3e3fa;"><b>Credit Amt</b></th>
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

	$pdf->WriteHTML($header);
	$pdf->WriteHTML($html);
	$pdf->Output('');
exit;

?>

    