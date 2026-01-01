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


	if ($d_type == 'DLI') {
		$file = 'd1_dli.pdf';

		if (file_exists($file)) {
			header('Content-Type: application/pdf');
			header('Content-Disposition: inline; filename="' . basename($file) . '"');
			header('Content-Length: ' . filesize($file));
			readfile($file);
			exit;
		} else {
			echo "File not found.";
		}
	}

	if ($d_type == 'Non-DLI') {
		$file = 'd1_nondli.pdf';

		if (file_exists($file)) {
			header('Content-Type: application/pdf');
			header('Content-Disposition: inline; filename="' . basename($file) . '"');
			header('Content-Length: ' . filesize($file));
			readfile($file);
			exit;
		} else {
			echo "File not found.";
		}
	}
	
	
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


$advances = 0;
$advances_beg = 0;
$expenses = 0;
$outstanding = 0;

$opening_bal = 0;
$advances_qtr = 0;
$subtotal_adv = 0;


$pdf = new \Mpdf\Mpdf([
				'mode' => 'utf-8',
				'format' => 'A4',
				'orientation' => 'P',
				'margin_header' => '15',
				'margin_top' => '42',
				'margin_bottom' => '12',
				'margin_footer' => '8',
				'deafult_font_size' => 8,
				'default_font' => 'nikosh'
			]);

$data = "";

//Start IDA Funds
$journals = $conn->query("SELECT (case when sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) is null then 0 else sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date <= '$end' and (account_id = 51) and journal_type = 'cv' and dli_type = '$d_type';");


$data .= '<tr><td style="padding: 3px;" colspan="4"><b>Part I</b></td></tr>';	
if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$data .= '<tr><td class="cl1" style="padding: 3px;">1</td>';
			$data .= '<td class="cl2" style="padding: 3px;">Cumulative advances to end of current reporting quarter</td>';
			$data .= '<td class="cl3" style="padding: 3px;">'.number_format($row['Crt'], 2).'</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
			$advances = $advances + $row['Crt'];
	   }else{
		    $data .= '<tr><td class="cl1" style="padding: 3px;">1</td>';
			$data .= '<td class="cl2" style="padding: 3px;">Cumulative advances to end of current reporting quarter</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
		}
	}
}else{
	$data .= '<tr><td class="cl1" style="padding: 3px;">1</td>';
	$data .= '<td class="cl2" style="padding: 3px;">Cumulative advances to end of current reporting quarter</td>';
	$data .= '<td class="cl3" style="padding: 3px;">--</td>';
	$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
}



$item_list = $conn->query("SELECT * from exp_group where id <= 8;"); 
while($row_item = $item_list->fetch_assoc()){
	$item_code = $row_item['id'];
	$journals = $conn->query("SELECT ((case when sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE journal_date < '$start' and eg.id = '$item_code' and dli_type = '$d_type' group by eg.id;");
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$expenses = $expenses + $row['Crt'];
			}
		}
	}
}


/*

$journals = $conn->query("SELECT al.name, je.dli_type, (case when sum(CASE WHEN group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 THEN jt.amount END) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date < '$start' and (account_id = 51 or account_id = 52) and new <> 1 and dli_type = '$d_type' group by account_id;");

if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$expenses = $expenses + $row['Crt'];
	   }
	}
}
*/

if ($expenses != 0){
	$data .= '<tr><td class="cl1" style="padding: 3px;">2</td>';
	$data .= '<td class="cl2" style="padding: 3px;">Less: Cumulative expenditures to end of last reporting quarter</td>';
	$data .= '<td class="cl3" style="padding: 3px;">'.number_format($expenses, 2).'</td>';
	$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
}else{
	$data .= '<tr><td class="cl1" style="padding: 3px;">2</td>';
	$data .= '<td class="cl2" style="padding: 3px;">Less: Cumulative expenditures to end of last reporting quarter</td>';
	$data .= '<td class="cl3" style="padding: 3px;">--</td>';
	$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
}



$outstanding = $advances - $expenses;
$data .= '<tr><td class="cl1" style="padding: 3px;">3</td>';
$data .= '<td class="cl2" style="padding: 3px;"><b>Outstanding advances to be accounted</b></td>';
$data .= '<td class="cl3" style="padding: 3px;"><b>'.number_format($outstanding, 2).'</b></td>';
$data .= '<td class="cl3" style="padding: 3px;"><b>'.number_format($outstanding, 2).'</b></td></tr>';

$data .= '<tr><td style="padding: 3px;" colspan="4"><b>Part II</b></td></tr>';

$data .= '<tr><td class="cl1" style="padding: 3px;">4</td>';
$data .= '<td class="cl2" style="padding: 3px;">Opening DA Balance as at beginning of the disbursement period</td>';


$journals = $conn->query("SELECT (case when sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) is null then 0 else sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date < '$start' and (account_id = 51) and journal_type = 'cv' and dli_type = '$d_type';");

if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$advances_beg = $advances_beg + $row['Crt'];
	   }
	}
}
$opening_bal = $advances_beg - $expenses;
if ($opening_bal != 0)
	$data .= '<td class="cl3" style="padding: 3px;">'.number_format($opening_bal, 2).'</td>';
else
	$data .= '<td class="cl3" style="padding: 3px;">--</td>';

$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';

$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
$data .= '<td class="cl2" style="padding: 3px;">Add/subtract: Cumulative Adjustments (if any)</td>';
$data .= '<td class="cl3" style="padding: 3px;">--</td>';
$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';

$journals = $conn->query("SELECT (case when sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) is null then 0 else sum(case when (account_id = 51) and journal_type = 'cv' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE journal_date >= '$start' and journal_date <= '$end' and (account_id = 51) and journal_type = 'cv' and dli_type = '$d_type';");


if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$advances_qtr = $advances_qtr + $row['Crt'];
	   }
	}
}

$data .= '<tr><td class="cl1" style="padding: 3px;">5</td>';
$data .= '<td class="cl2" style="padding: 3px;">Add: Advance during the quarter</td>';
if ($advances_qtr != 0)
	$data .= '<td class="cl3" style="padding: 3px;">'.number_format($advances_qtr, 2).'</td>';
else
	$data .= '<td class="cl3" style="padding: 3px;">--</td>';
$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';

$subtotal_adv = $opening_bal + $advances_qtr;

$data .= '<tr><td class="cl1" style="padding: 3px;"><b>6</b></td>';
$data .= '<td class="cl2" style="padding: 3px;"><b>Sub-Total of Advances and Adjustments</td>';
$data .= '<td class="cl3" style="padding: 3px;"><b>'.number_format($subtotal_adv, 2).'</b></td>';
$data .= '<td class="cl3" style="padding: 3px;"><b>'.number_format($subtotal_adv, 2).'</b></td></tr>';

$data .= '<tr><td class="cl1" style="padding: 3px;">7</td>';
$data .= '<td class="cl2" style="padding: 3px;">Less: Refund from DA during the quarter</td>';
$data .= '<td class="cl3" style="padding: 3px;">--</td>';
$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';


$data .= '<tr><td class="cl1" style="padding: 3px;"><b>8</b></td>';
$data .= '<td class="cl2" style="padding: 3px;"><b>Outstanding advances to be accounted</b></td>';
$data .= '<td class="cl3" style="padding: 3px;"><b>'.number_format($subtotal_adv, 2).'</b></td>';
$data .= '<td class="cl3" style="padding: 3px;"><b>'.number_format($subtotal_adv, 2).'</b></td></tr>';

$data .= '<tr><td style="padding: 3px;" colspan="4"><b>IUFRs</b></td></tr>';
$total_qtr_exp = 0;
$da_balance = 0;






$item_list = $conn->query("SELECT * from exp_group where id <= 8;"); 
while($row_item = $item_list->fetch_assoc()){
	$item_code = $row_item['id'];
	$journals = $conn->query("SELECT ((case when sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE journal_date >= '$start' and journal_date <= '$end' and eg.id = '$item_code' and dli_type = '$d_type' group by eg.id;");
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$total_qtr_exp = $total_qtr_exp + $row['Crt'];
			}
		}
	}
}
$da_balance = $subtotal_adv - $total_qtr_exp; // $outstanding - $total_qtr_exp;

$in_cash_qtr = 0;

$out_cash_qtr = 0;

$journals = $conn->query("SELECT (case when sum(case when group_id = 1 and account_id = 52 then jt.amount end) is null then 0 else sum(case when group_id = 1 and account_id = 52 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id WHERE journal_date <= '$end' and dli_type = '$d_type' and account_id = 52;");

if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
			   $in_cash_qtr = $row['Crt'];
			   //$da_balance = $da_balance - $cash_qtr;
		   }
		}
}

$journals = $conn->query("SELECT (case when sum(case when group_id = 4 and account_id = 52 then jt.amount end) is null then 0 else sum(case when group_id = 4 and account_id = 52 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id WHERE journal_date <= '$end' and dli_type = '$d_type' and account_id = 52;");

if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
			   $out_cash_qtr = $row['Crt'];
			   //$da_balance = $da_balance - $cash_qtr;
		   }
		}
}

$inout = $in_cash_qtr - $out_cash_qtr;

if ($inout >= 0){
    $da_balance = $da_balance - $inout;
}

$cash_qtr = 0;
$cash_qtr = $in_cash_qtr - $out_cash_qtr;


$data .= '<tr><td class="cl1" style="padding: 3px;"><b>9</b></td>';
$data .= '<td class="cl2" style="padding: 3px;">Closing DA Balance at the end of current disbursement period</td>';
$data .= '<td class="cl3" style="padding: 3px;"><b>'.number_format($da_balance, 2).'</b></td>';
$data .= '<td class="cl3" style="padding: 3px;"><b>'.number_format($da_balance, 2).'</b></td></tr>';

$data .= '<tr><td class="cl1" style="padding: 3px;">10</td>';
$data .= '<td class="cl2" style="padding: 3px;">Add/Subtract: Cumulative Adjustments (if any)</td>';
$data .= '<td class="cl3" style="padding: 3px;">--</td>';
$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';

$data .= '<tr><td class="cl1" style="padding: 3px;">11</td>';
$data .= '<td class="cl2" style="padding: 3px;">Add: Amount of eligible expenditures for current reporting period</td>';
$data .= '<td class="cl3" style="padding: 3px;">--</td>';
$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';


//Use of Funds
$total_qtr_exp = 0;
//$total_exp_cumulative = 0;

$item_list = $conn->query("SELECT * from exp_group where id <= 8;");
while($row_item = $item_list->fetch_assoc()){
	$item_name = $row_item['exp_name'];
	$item_code = $row_item['id'];
	
	$journals = $conn->query("SELECT ((case when sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE journal_date >= '$start' and journal_date <= '$end' and eg.id = '$item_code' and dli_type = '$d_type' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
				if ($item_code == 1) {
					if ($row['Crt'] != 0) {
						$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
						$data .= '<td class="cl2" style="padding: 3px;">a) '.$item_name.'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">'.number_format($row['Crt'], 2).'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
					} else{
						$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
						$data .= '<td class="cl2" style="padding: 3px;">a) '.$item_name.'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
					}
				}
				if ($item_code == 2) {
					if ($row['Crt'] != 0) {
						$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
						$data .= '<td class="cl2" style="padding: 3px;">b) '.$item_name.'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">'.number_format($row['Crt'], 2).'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
					} else{
						$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
						$data .= '<td class="cl2" style="padding: 3px;">b) '.$item_name.'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
					}
				}
				if ($item_code == 3) {
					if ($row['Crt'] != 0) {
						$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
						$data .= '<td class="cl2" style="padding: 3px;">c) '.$item_name.'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">'.number_format($row['Crt'], 2).'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
					} else{
						$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
						$data .= '<td class="cl2" style="padding: 3px;">c) '.$item_name.'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
					}
				}
				if ($item_code == 4) {
					if ($row['Crt'] != 0) {
						$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
						$data .= '<td class="cl2" style="padding: 3px;">d) '.$item_name.'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">'.number_format($row['Crt'], 2).'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
					} else{
						$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
						$data .= '<td class="cl2" style="padding: 3px;">d) '.$item_name.'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
					}
				}
				if ($item_code == 5) {
					if ($row['Crt'] != 0) {
						$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
						$data .= '<td class="cl2" style="padding: 3px;">e) '.$item_name.'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">'.number_format($row['Crt'], 2).'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
					} else{
						$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
						$data .= '<td class="cl2" style="padding: 3px;">e) '.$item_name.'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
					}
				}
				if ($item_code == 6) {
					if ($row['Crt'] != 0) {
						$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
						$data .= '<td class="cl2" style="padding: 3px;">f) '.$item_name.'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">'.number_format($row['Crt'], 2).'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
					} else{
						$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
						$data .= '<td class="cl2" style="padding: 3px;">f) '.$item_name.'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
					}
					$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
					$data .= '<td class="cl2" style="padding: 3px;">g) Grants under Part 3.2 (b)</td>';
					$data .= '<td class="cl3" style="padding: 3px;">--</td>';
					$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
				}
				
				if ($item_code == 7) {
					if ($row['Crt'] != 0) {
						$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
						$data .= '<td class="cl2" style="padding: 3px;">&nbsp;&nbsp;(i) '.$item_name.'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">'.number_format($row['Crt'], 2).'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
					} else{
						$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
						$data .= '<td class="cl2" style="padding: 3px;">&nbsp;&nbsp;(i) '.$item_name.'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
					}
				}
				if ($item_code == 8) {
					if ($row['Crt'] != 0) {
						$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
						$data .= '<td class="cl2" style="padding: 3px;">&nbsp;&nbsp;&nbsp;(ii) '.$item_name.'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">'.number_format($row['Crt'], 2).'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
					} else{
						$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
						$data .= '<td class="cl2" style="padding: 3px;">&nbsp;&nbsp;(ii) '.$item_name.'</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td>';
						$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
					}
				}
				$total_qtr_exp = $total_qtr_exp + $row['Crt'];
		   }
	}else{
		if ($item_code == 1) {
			$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
			$data .= '<td class="cl2" style="padding: 3px;">a) '.$item_name.'</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
		}
		if ($item_code == 2) {
			$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
			$data .= '<td class="cl2" style="padding: 3px;">b) '.$item_name.'</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
		}
		if ($item_code == 3) {
			$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
			$data .= '<td class="cl2" style="padding: 3px;">c) '.$item_name.'</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
		}
		if ($item_code == 4) {
			$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
			$data .= '<td class="cl2" style="padding: 3px;">d) '.$item_name.'</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
		}
		if ($item_code == 5) {
			$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
			$data .= '<td class="cl2" style="padding: 3px;">e) '.$item_name.'</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
		}
		if ($item_code == 6) {
			$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
			$data .= '<td class="cl2" style="padding: 3px;">f) '.$item_name.'</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
			
			$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
			$data .= '<td class="cl2" style="padding: 3px;">g) Grants under Part 3.2 (b)</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
		}
		if ($item_code == 7) {
			$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
			$data .= '<td class="cl2" style="padding: 3px;">&nbsp;&nbsp;&nbsp;(i) '.$item_name.'</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
		}
		if ($item_code == 8) {
			$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
			$data .= '<td class="cl2" style="padding: 3px;">&nbsp;&nbsp;(ii) '.$item_name.'</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
		}
	}
}

$data .= '<tr><td class="cl1" style="padding: 3px;"><b>12</b></td>';
$data .= '<td class="cl2" style="padding: 3px;"><b>Sub-Total of Adjustments and Expenditures</b></td>';
$data .= '<td class="cl3" style="padding: 3px;"><b>'.number_format($total_qtr_exp, 2).'</b></td>';
$data .= '<td class="cl3" style="padding: 3px;"><b>'.number_format($total_qtr_exp, 2).'</b></td></tr>';

$data .= '<tr><td class="cl1" style="padding: 3px;"></td>';
$data .= '<td class="cl2" style="padding: 3px;"><b>Add: Petty Cash Advance</b></td>';
if ($cash_qtr > 0) {
	$data .= '<td class="cl3" style="padding: 3px;"><b>'.number_format($cash_qtr, 2).'</b></td>';
	$data .= '<td class="cl3" style="padding: 3px;"><b>'.number_format($cash_qtr, 2).'</b></td></tr>';
} else {
	$data .= '<td class="cl3" style="padding: 3px;"><b>--</b></td>';
	$data .= '<td class="cl3" style="padding: 3px;"><b>--</b></td></tr>';
}


$outstanding = $da_balance + $total_qtr_exp + $cash_qtr;

$data .= '<tr><td class="cl1" style="padding: 3px;"><b>13</b></td>';
$data .= '<td class="cl2" style="padding: 3px;"><b>Total Advance accounted for</b></td>';
$data .= '<td class="cl3" style="padding: 3px;"><b>'.number_format($outstanding, 2).'</b></td>';
$data .= '<td class="cl3" style="padding: 3px;"><b>'.number_format($outstanding, 2).'</b></td></tr>';

$header = '<!--mpdf
<htmlpageheader name="letterheader">
	<div style="font-size: 10pt; text-align: center; padding-top: 1mm; font-family: nikosh; ">
		<h3 style="margin-bottom: -1px;">National Special Economic Zone (NSEZ) Development Project</h3>
		<h4 style="margin-top: -5px; margin-bottom: -1px;">Credit No. IDA-6676 BD ('.$d_type.' Part)</h4>
		<h4 style="margin-top: -5px; margin-bottom: -1px;">1D. Designated Account (DA) Activity Statement</h4>
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
	width: 7%;
	text-align: center;
}
.cl2 {
	width: 55%;
	text-align: left;
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
		  <th class="cl1" style="background-color: #f3e3fa;"><b>SL No</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>Description</b></th>
		  <th class="cl3" style="background-color: #f3e3fa;"><b>Amount</b></th>
		  <th class="cl3" style="background-color: #f3e3fa;"><b>Total Amount</b></th>
		</tr>
		</thead>		  
		<tbody>
		'.$data.'
		</tbody>
	   </table>

<br><br><br>
<p style="margin-bottom: 2px; font-size: 11px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Financial Management Specialist&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Project Director</p>
</body>
</html>';
$pdf->WriteHTML($header);
$pdf->WriteHTML($html);
$pdf->Output('');
exit;
?>