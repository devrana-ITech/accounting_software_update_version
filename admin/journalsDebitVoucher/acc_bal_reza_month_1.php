<?php	

require_once('./../../config.php');

require('../../vendor/autoload.php');

function format_num($number){
	$decimals = 0;
	$num_ex = explode('.',$number);
	$decimals = isset($num_ex[1]) ? strlen($num_ex[1]) : 2 ;
	return number_format($number,$decimals);
}

/*

$fy_id = $_POST['year_id'];
//$qtr_id = $_POST['qtr_id'];
//$d_type = $_POST['d_type'];

if ($fy_id == 2){
	$start = '2021-07-01';
	$end = '2022-06-30';
	$f_year = '2021-22';
}

if ($fy_id == 3){
	$start = '2022-07-01';
	$end = '2023-06-30';
	$f_year = '2022-23';
}

if ($fy_id == 4){
	$start = '2023-07-01';
	$end = '2024-06-30';
	$f_year = '2023-24';
}

if ($fy_id == 5){
	$start = '2024-07-01';
	$end = '2025-06-30';
	$f_year = '2024-25';
}

*/

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
$journals = $conn->query("SELECT acc_code, dpp_code, name, dpp_amount from dpp d right join account_list al on al.id = d.account_id WHERE acc_type = 1 order by acc_code;");

$gov_total = 0;
$bal_total = 0;

$gov_exp_total = 0;
$aid_exp_total = 0;

$r_gov_exp = 0;
$r_aid_exp = 0;
$total_exp = 0;

$r_aid_total = 0;
$r_gov_total = 0;

//$data .= '<tr><td class="cl1" style="padding: 3px;" colspan="7"><b>Opening Balances</b></td></tr>';

$data .= '<tr><td style="padding: 3px;" colspan="11"><b>A. Recurrent Expenditure</b></td></tr>';

if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
		$r_aid_total = 0;
		$r_gov_total = 0;
	   if ($row['dpp_amount'] != 0) {
			$data .= '<tr><td class="cl1" style="padding: 3px;">'.$row['acc_code'].'</td>';
			$data .= '<td class="cl4" style="padding: 3px;">'.$row['name'].'</td>';
			if ($row['dpp_amount'] != 0){
				$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['dpp_amount'], 2).'</td>';
				$gov_total = $gov_total + $row['dpp_amount'];
				$r_gov_total = $row['dpp_amount'];
			} else{
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			}
		}else {
			$data .= '<tr><td class="cl1" style="padding: 3px;">'.$row['acc_code'].'</td>';
			$data .= '<td class="cl4" style="padding: 3px;">'.$row['name'].'</td>';
			$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			$r_gov_total = 0;
		}
			
			$acc_code = $row['dpp_code'];
			
			$counter = 1;
			while ($counter <= 6){
				if ($counter == 1)
					$journals_gov_exp = $conn->query("SELECT ((case when sum(case when group_id = 1 then jt.amount end) is null then 0 else sum(case when group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE  dpp_code = '$acc_code' and journal_date >='2025-01-01' and journal_date <='2025-01-31';");
				if ($counter == 2)
					$journals_gov_exp = $conn->query("SELECT ((case when sum(case when group_id = 1 then jt.amount end) is null then 0 else sum(case when group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE  dpp_code = '$acc_code' and journal_date >='2025-02-01' and journal_date <='2025-02-28';");
				
				if ($counter == 3)
					$journals_gov_exp = $conn->query("SELECT ((case when sum(case when group_id = 1 then jt.amount end) is null then 0 else sum(case when group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE  dpp_code = '$acc_code' and journal_date >='2025-03-01' and journal_date <='2025-03-31';");
				
				if ($counter == 4)
					$journals_gov_exp = $conn->query("SELECT ((case when sum(case when group_id = 1 then jt.amount end) is null then 0 else sum(case when group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE  dpp_code = '$acc_code' and journal_date >='2025-04-01' and journal_date <='2025-04-30';");
				
				if ($counter == 5)
					$journals_gov_exp = $conn->query("SELECT ((case when sum(case when group_id = 1 then jt.amount end) is null then 0 else sum(case when group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE  dpp_code = '$acc_code' and journal_date >='2025-05-01' and journal_date <='2025-05-31';");
				
				if ($counter == 6)
					$journals_gov_exp = $conn->query("SELECT ((case when sum(case when group_id = 1 then jt.amount end) is null then 0 else sum(case when group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE  dpp_code = '$acc_code' and journal_date >='2025-06-01' and journal_date <='2025-06-30';");
			
				while($row_gov_exp = $journals_gov_exp->fetch_assoc()){
					$r_gov_exp = 0;
					if ($row_gov_exp['Crt'] != 0) {
						$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row_gov_exp['Crt'], 2).'</td></tr>';
						$gov_exp_total = $gov_exp_total + $row_gov_exp['Crt'];
						$r_gov_exp = $row_gov_exp['Crt'];
					}else {
						$data .= '<td class="cl2" style="padding: 3px;">--</td></tr>';
					}
				}
				$counter++;
			}
			
	}
}

/*
$data .= '<tr><td style="padding: 3px;" colspan="2"><b>Total Recurrent Expenditure</b></td><td class="cl2">'.number_format($gov_total, 2).'</td><td class="cl2">'.number_format($gov_exp_total, 2).'</td></tr>';

$col3 = 0;
$col4 = 0;


$col3 = $gov_total;
$col4 = $gov_exp_total;
*/

///////////////////////////////////////////////////////////////////

$journals = $conn->query("SELECT acc_code, dpp_code, name, dpp_amount from dpp d right join account_list al on al.id = d.account_id WHERE acc_type = 2 order by acc_code;");

$gov_total = 0;

$gov_exp_total = 0;
$aid_exp_total = 0;

$r_gov_exp = 0;
$r_aid_exp = 0;
$total_exp = 0;

$r_aid_total = 0;
$r_gov_total = 0;

//$data .= '<tr><td class="cl1" style="padding: 3px;" colspan="7"><b>Opening Balances</b></td></tr>';

$data .= '<tr><td style="padding: 3px;" colspan="11"><b>B. Capital Expenditure</b></td></tr>';

if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
		$r_aid_total = 0;
		$r_gov_total = 0;
	   if ($row['dpp_amount'] != 0) {
			$data .= '<tr><td class="cl1" style="padding: 3px;">'.$row['acc_code'].'</td>';
			$data .= '<td class="cl4" style="padding: 3px;">'.$row['name'].'</td>';
			if ($row['dpp_amount'] != 0){
				$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['dpp_amount'], 2).'</td>';
				$gov_total = $gov_total + $row['dpp_amount'];
				$r_gov_total = $row['dpp_amount'];
			} else{
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			}
		}else {
			$data .= '<tr><td class="cl1" style="padding: 3px;">'.$row['acc_code'].'</td>';
			$data .= '<td class="cl4" style="padding: 3px;">'.$row['name'].'</td>';
			$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			$r_gov_total = 0;
		}
			
			$acc_code = $row['dpp_code'];
			
			$counter = 1;
			while ($counter <= 6){
				if ($counter == 1)
					$journals_gov_exp = $conn->query("SELECT ((case when sum(case when group_id = 1 then jt.amount end) is null then 0 else sum(case when group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE  dpp_code = '$acc_code' and journal_date >='2025-01-01' and journal_date <='2025-01-31';");
				if ($counter == 2)
					$journals_gov_exp = $conn->query("SELECT ((case when sum(case when group_id = 1 then jt.amount end) is null then 0 else sum(case when group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE  dpp_code = '$acc_code' and journal_date >='2025-02-01' and journal_date <='2025-02-28';");
				
				if ($counter == 3)
					$journals_gov_exp = $conn->query("SELECT ((case when sum(case when group_id = 1 then jt.amount end) is null then 0 else sum(case when group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE  dpp_code = '$acc_code' and journal_date >='2025-03-01' and journal_date <='2025-03-31';");
				
				if ($counter == 4)
					$journals_gov_exp = $conn->query("SELECT ((case when sum(case when group_id = 1 then jt.amount end) is null then 0 else sum(case when group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE  dpp_code = '$acc_code' and journal_date >='2025-04-01' and journal_date <='2025-04-30';");
				
				if ($counter == 5)
					$journals_gov_exp = $conn->query("SELECT ((case when sum(case when group_id = 1 then jt.amount end) is null then 0 else sum(case when group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE  dpp_code = '$acc_code' and journal_date >='2025-05-01' and journal_date <='2025-05-31';");
				
				if ($counter == 6)
					$journals_gov_exp = $conn->query("SELECT ((case when sum(case when group_id = 1 then jt.amount end) is null then 0 else sum(case when group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE  dpp_code = '$acc_code' and journal_date >='2025-06-01' and journal_date <='2025-06-30';");
			
			//journal_date >= '$start' and journal_date <='$end' and and source_fund = 'GoB'
			
					while($row_gov_exp = $journals_gov_exp->fetch_assoc()){
						$r_gov_exp = 0;
						if ($row_gov_exp['Crt'] != 0) {
							$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row_gov_exp['Crt'], 2).'</td></tr>';
							$gov_exp_total = $gov_exp_total + $row_gov_exp['Crt'];
							$r_gov_exp = $row_gov_exp['Crt'];
						}else {
							$data .= '<td class="cl2" style="padding: 3px;">--</td></tr>';
						}
					}
					/*
					if ($counter == 1)
						$data .= '<tr><td style="padding: 3px;" colspan="2"><b>Total Capital Expenditure</b></td><td class="cl2">'.number_format($gov_total, 2).'</td>;
					if ($counter == 2)
						$data .= '<td class="cl2">'.number_format($gov_exp_total, 2).'</td></tr>';
					*/
					$counter++;
			}
	}
}



/*
$col3 = $col3 + $gov_total;
$col4 = $col4 + $gov_exp_total;


$data .= '<tr><td style="padding: 3px;" colspan="2"><b>Total</b></td><td class="cl2">'.number_format($col3, 2).'</td><td class="cl2">'.number_format($col4, 2).'</td><td class="cl2">'.number_format($col5, 2).'</td></tr>';

*/

$header = '<!--mpdf
<htmlpageheader name="letterheader">
	<div style="font-size: 10pt; text-align: center; padding-top: 1mm; font-family: nikosh; ">
		<h3 style="margin-bottom: -1px;">National Special Economic Zone (NSEZ) Development Project</h3>
		<h4 style="margin-top: -5px; margin-bottom: -1px;">Credit No. IDA-6676 BD</h4>
		<h4 style="margin-top: -5px; margin-bottom: -1px;">Fund Allocation and Expenditure</h4>
		<h4 style="margin-top: -5px; margin-bottom: -1px; border-bottom: 1px solid #000000;">As of 30 June 2025</h4>
		
	</div>
</htmlpageheader>
<htmlpagefooter name="letterfooter2">
	<div class="container" style="border-top: 1px solid #000000; font-size: 9pt; font-style: italic; padding-top: 1mm; font-family: sans-serif; ">
		<div class="column" style="text-align: left">
			NSEZ Development Project
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
	width: 10%;
	text-align: left;
}
.cl4 {
	width: 20%;
	text-align: left;
}
.cl2 {
	width: 10%;
	text-align: right;
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
  <p style="text-align: right; margin-bottom: 2px; font-size: 11px;"><i>(Amount in BDT)</i></p>
	  <table>
		<thead>
		<tr>
		  <th class="cl1" style="background-color: #f3e3fa;"><b>Account Code</b></th>
		  <th class="cl4" style="background-color: #f3e3fa;"><b>Account Name</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>Fund Allocation</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>Jan</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>Feb</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>Mar</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>Apr</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>May</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>Jun</b></th>
		</tr>
		</thead>		  
		<tbody>
		'.$data.'
		</tbody>
	   </table>
	   
</body>
</html>';
$pdf->WriteHTML($header);
$pdf->WriteHTML($html);
$pdf->Output('');
exit;
?>

    