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
		$file = 'a1_pdf_dli.pdf';

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
		$file = 'a1_pdf_nondli.pdf';

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
	
	
	
	
	

$total_opening_qtr = 0;
$total_opening_ytd = 0;
$total_funds_cumulative = 0;


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
///Start of Cash Open	
$journals = $conn->query("SELECT ((case when sum(case when account_id = 52 and group_id = 1 then jt.amount end) is null then 0 else sum(case when account_id = 52 and group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN account_id = 52 and group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 and account_id = 52 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id WHERE journal_date < '$start' and account_id = 52 and dli_type = '$d_type';");
	
$data .= '<tr><td class="cl1" style="padding: 3px;" colspan="7"><b>Opening Balances</b></td></tr>';
if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$data .= '<tr><td class="cl1" style="padding: 3px;">Cash Account</td>';
			$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td>';
			$data .= '<td class="cl3" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td>';
			$total_opening_qtr = $total_opening_qtr + $row['Crt'];
	   }else{
		    $data .= '<tr><td class="cl1" style="padding: 3px;">Cash Account</td>';
			$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td>';
		}
	}
}else{
	$data .= '<tr><td class="cl1" style="padding: 3px;">Cash Account</td>';
	$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	$data .= '<td class="cl3" style="padding: 3px;">--</td>';
}

$journals = $conn->query("SELECT ((case when sum(case when account_id = 52 and group_id = 1 then jt.amount end) is null then 0 else sum(case when account_id = 52 and group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4 and account_id = 52 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 and account_id = 52 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id WHERE journal_date < '$start_ytd' and account_id = 52 and dli_type = '$d_type';");
if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td>';
			$data .= '<td class="cl3" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td>';
			$total_opening_ytd = $total_opening_ytd + $row['Crt'];
	   }else{
			$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td>';
		}
	}
}else{
	$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	$data .= '<td class="cl3" style="padding: 3px;">--</td>';
}
$data .= '<td class="cl2" style="padding: 3px;">--</td>';
$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';

///End of Cash Open

///Start of Bank Open	
$journals = $conn->query("SELECT ((case when sum(case when account_id = 51 and group_id = 1 then jt.amount end) is null then 0 else sum(case when account_id = 51 and group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4 and account_id = 51 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 and account_id = 51 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id WHERE journal_date < '$start' and account_id = 51 and dli_type = '$d_type';");
	
if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$data .= '<tr><td class="cl1" style="padding: 3px;">Designated Account</td>';
			$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td>';
			$data .= '<td class="cl3" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td>';
			$total_opening_qtr = $total_opening_qtr + $row['Crt'];
	   }else{
		    $data .= '<tr><td class="cl1" style="padding: 3px;">Designated Account</td>';
			$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td>';
		}
	}
}else{
	$data .= '<tr><td class="cl1" style="padding: 3px;">Designated Account</td>';
	$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	$data .= '<td class="cl3" style="padding: 3px;">--</td>';
}

$journals = $conn->query("SELECT ((case when sum(case when account_id = 51 and group_id = 1 then jt.amount end) is null then 0 else sum(case when account_id = 51 and group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN group_id = 4 and account_id = 51 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 and account_id = 51 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id WHERE journal_date < '$start_ytd' and account_id = 51 and dli_type = '$d_type';");
if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td>';
			$data .= '<td class="cl3" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td>';
			$total_opening_ytd = $total_opening_ytd + $row['Crt'];
	   }else{
			$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td>';
		}
	}
}else{
	$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	$data .= '<td class="cl3" style="padding: 3px;">--</td>';
}
$data .= '<td class="cl2" style="padding: 3px;">--</td>';
$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';


$data .= '<tr><td class="cl1" style="padding: 3px;"><b>Opening Balance Total</b></td>';
if ($total_opening_qtr != 0){
	$data .= '<td class="cl2" style="padding: 3px;">'.number_format($total_opening_qtr/1000000, 2).'</td>';
	$data .= '<td class="cl3" style="padding: 3px;">'.number_format($total_opening_qtr/1000000, 2).'</td>';
}else{
	$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	$data .= '<td class="cl3" style="padding: 3px;">--</td>';
}
if ($total_opening_ytd != 0){
	$data .= '<td class="cl2" style="padding: 3px;">'.number_format($total_opening_ytd/1000000, 2).'</td>';
	$data .= '<td class="cl3" style="padding: 3px;">'.number_format($total_opening_ytd/1000000, 2).'</td>';
}else {
	$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	$data .= '<td class="cl3" style="padding: 3px;">--</td>';
}
$data .= '<td class="cl2" style="padding: 3px;">--</td>';
$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
///End of Bank Open


//Start IDA Funds
$journals = $conn->query("SELECT (case when sum(case when account_id = 51 and group_id = 1 and new = 1 then jt.amount end) is null then 0 else sum(case when account_id = 51 and group_id = 1 and new = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id WHERE journal_date >= '$start' and journal_date <= '$end' and account_id = 51 and dli_type = '$d_type' and journal_type='cv';");

$data .= '<tr><td class="cl1" style="padding: 3px;" colspan="7"><b>Add: Sources of Funds</b></td></tr>';	
if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$data .= '<tr><td class="cl1" style="padding: 3px;">IDA Funds</td>';
			$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td>';
			$data .= '<td class="cl3" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td>';
			$total_opening_qtr = $total_opening_qtr + $row['Crt'];
	   }else{
		    $data .= '<tr><td class="cl1" style="padding: 3px;">IDA Funds</td>';
			$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td>';
		}
	}
}else{
	$data .= '<tr><td class="cl1" style="padding: 3px;">IDA Funds</td>';
	$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	$data .= '<td class="cl3" style="padding: 3px;">--</td>';
}

$journals = $conn->query("SELECT (case when sum(case when account_id = 51 and group_id = 1 and new = 1 then jt.amount end) is null then 0 else sum(case when account_id = 51 and group_id = 1 and new = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id WHERE journal_date >= '$start_ytd' and journal_date <= '$end' and account_id = 51 and dli_type = '$d_type' and journal_type='cv';");

if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td>';
			$data .= '<td class="cl3" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td>';
			$total_opening_ytd = $total_opening_ytd + $row['Crt'];
	   }else{
			$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td>';
		}
	}
}else{
	$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	$data .= '<td class="cl3" style="padding: 3px;">--</td>';
}

$journals = $conn->query("SELECT (case when sum(case when account_id = 51 and group_id = 1 and new = 1 then jt.amount end) is null then 0 else sum(case when account_id = 51 and group_id = 1 and new = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id WHERE journal_date <= '$end' and account_id = 51 and dli_type = '$d_type' and journal_type='cv';");

if ($journals->num_rows > 0){
	while($row = $journals->fetch_assoc()){
	   if ($row['Crt'] != 0) {
			$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td>';
			$data .= '<td class="cl3" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td></tr>';
			$total_funds_cumulative = $row['Crt'];
	   }else{
			$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
		}
	}
}else{
	$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
}

$data .= '<tr><td class="cl1" style="padding: 3px;">Foreign Exchange Gain/Loss</td><td class="cl2">--</td><td class="cl2">--</td><td class="cl2">--</td><td class="cl2">--</td><td class="cl2">--</td><td class="cl2">--</td></tr>';

$data .= '<tr><td class="cl1" style="padding: 3px;"><b>Total Funds Available</b></td><td class="cl2">'.number_format($total_opening_qtr/1000000, 2).'</td><td class="cl2">'.number_format($total_opening_qtr/1000000, 2).'</td><td class="cl2">'.number_format($total_opening_ytd/1000000, 2).'</td><td class="cl2">'.number_format($total_opening_ytd/1000000, 2).'</td><td class="cl2">'.number_format($total_funds_cumulative/1000000, 2).'</td><td class="cl2">'.number_format($total_funds_cumulative/1000000, 2).'</td></tr>';

//////////////////////////////////////////////////////////End IDA Funds

$data .= '<tr><td class="cl1" style="padding: 3px;" colspan="7"><b>Less: Uses of Funds</b></td></tr>';


//Use of Funds
$total_qtr_exp = 0;
$total_ytd_exp = 0;
$total_exp_cumulative = 0;

$item_list = $conn->query("SELECT * from exp_group where id <= 8;");
while($row_item = $item_list->fetch_assoc()){
	$item_name = $row_item['exp_name'];
	$item_code = $row_item['id'];
	
	//$journals = $conn->query("SELECT (case when sum(case when je.exp_group = '$item_code' and group_id = 1 then jt.amount end) is null then 0 else sum(case when je.exp_group = '$item_code' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id WHERE journal_date >= '$start' and journal_date <= '$end' and je.exp_group = '$item_code' and dli_type = '$d_type' group by je.exp_group;");
	
	$journals = $conn->query("SELECT ((case when sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE eg.id = '$item_code' and dli_type = '$d_type' and journal_date >= '$start' and journal_date <= '$end' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$data .= '<tr><td class="cl1" style="padding: 3px;">'.$item_name.'</td>';
				$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td>';
				$data .= '<td class="cl3" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td>';
				$total_qtr_exp = $total_qtr_exp + $row['Crt'];
		   }else{
				$data .= '<tr><td class="cl1" style="padding: 3px;">'.$item_name.'</td>';
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$data .= '<td class="cl3" style="padding: 3px;">--</td>';
			}
		}
	}else{
		$data .= '<tr><td class="cl1" style="padding: 3px;">'.$item_name.'</td>';
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
		$data .= '<td class="cl3" style="padding: 3px;">--</td>';
	}
	//$journals = $conn->query("SELECT (case when sum(case when je.exp_group = '$item_code' and group_id = 1 then jt.amount end) is null then 0 else sum(case when je.exp_group = '$item_code' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id WHERE journal_date >= '$start_ytd' and journal_date <= '$end' and je.exp_group = '$item_code' and dli_type = '$d_type' group by je.exp_group;");
	
	$journals = $conn->query("SELECT ((case when sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE eg.id = '$item_code' and dli_type = '$d_type' and journal_date >= '$start_ytd' and journal_date <= '$end' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td>';
				$data .= '<td class="cl3" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td>';
				$total_ytd_exp = $total_ytd_exp + $row['Crt'];
		   }else{
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$data .= '<td class="cl3" style="padding: 3px;">--</td>';
			}
		}
	}else{
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
		$data .= '<td class="cl3" style="padding: 3px;">--</td>';
	}
	//$journals = $conn->query("SELECT (case when sum(case when je.exp_group = '$item_code' and group_id = 1 then jt.amount end) is null then 0 else sum(case when je.exp_group = '$item_code' and group_id = 1 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id WHERE journal_date <= '$end' and je.exp_group = '$item_code' and dli_type = '$d_type' group by je.exp_group;");
	
	$journals = $conn->query("SELECT ((case when sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE eg.id = '$item_code' and dli_type = '$d_type' and journal_date <= '$end' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td>';
				$data .= '<td class="cl3" style="padding: 3px;">'.number_format($row['Crt']/1000000, 2).'</td></tr>';
				$total_exp_cumulative = $total_exp_cumulative + $row['Crt'];
		   }else{
				$data .= '<td class="cl2" style="padding: 3px;">--</td>';
				$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
			}
		}
	}else{
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
		$data .= '<td class="cl3" style="padding: 3px;">--</td></tr>';
	}
}
$data .= '<tr><td class="cl1" style="padding: 3px;"><b>Uses of Funds Total</b></td><td class="cl2">'.number_format($total_qtr_exp/1000000, 2).'</td><td class="cl2">'.number_format($total_qtr_exp/1000000, 2).'</td><td class="cl2">'.number_format($total_ytd_exp/1000000, 2).'</td><td class="cl2">'.number_format($total_ytd_exp/1000000, 2).'</td><td class="cl2">'.number_format($total_exp_cumulative/1000000, 2).'</td><td class="cl2">'.number_format($total_exp_cumulative/1000000, 2).'</td></tr>';

$bal_qtr = $total_opening_qtr - $total_qtr_exp;
$bal_ytd = $total_opening_ytd - $total_ytd_exp;
$bal_cum = $total_funds_cumulative - $total_exp_cumulative;

$bal_qtr_ = 0;
$bal_ytd_ = 0;
$bal_cum_ = 0;

$data .= '<tr><td class="cl1" style="padding: 3px;"><b>Closing Balance Total</b></td><td class="cl2">'.number_format($bal_qtr/1000000, 2).'</td><td class="cl2">'.number_format($bal_qtr/1000000, 2).'</td><td class="cl2">'.number_format($bal_ytd/1000000, 2).'</td><td class="cl2">'.number_format($bal_ytd/1000000, 2).'</td><td class="cl2">'.number_format($bal_cum/1000000, 2).'</td><td class="cl2">'.number_format($bal_cum/1000000, 2).'</td></tr>';

$cash_qtr = 0;
//$journals = $conn->query("SELECT (case when sum(case when group_id = 1 and account_id = 52 then jt.amount end) is null then 0 else sum(case when group_id = 1 and account_id = 52 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id WHERE journal_date >= '$start' and journal_date <= '$end' and dli_type = '$d_type' and account_id = 52;");

$journals = $conn->query("SELECT ((case when sum(case when account_id = 52 and group_id = 1 then jt.amount end) is null then 0 else sum(case when account_id = 52 and group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN account_id = 52 and group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 and account_id = 52 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id WHERE journal_date >= '$start' and journal_date <= '$end' and account_id = 52 and dli_type = '$d_type';");

if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
			   $cash_qtr = $row['Crt'];
			   if ($cash_qtr < 0)
				   $bal_qtr = $bal_qtr;
			   else
				   $bal_qtr_ = $bal_qtr - $cash_qtr;
		   }
		}
}

$cash_ytd = 0;
//$journals = $conn->query("SELECT (case when sum(case when group_id = 1 and account_id = 52 then jt.amount end) is null then 0 else sum(case when group_id = 1 and account_id = 52 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id WHERE journal_date >= '$start_ytd' and journal_date <= '$end' and dli_type = '$d_type' and account_id = 52;");

$journals = $conn->query("SELECT ((case when sum(case when account_id = 52 and group_id = 1 then jt.amount end) is null then 0 else sum(case when account_id = 52 and group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN account_id = 52 and group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 and account_id = 52 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id WHERE journal_date >= '$start_ytd' and journal_date <= '$end' and account_id = 52 and dli_type = '$d_type';");

if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
			   $cash_ytd = $row['Crt'];
			   if ($cash_ytd < 0)
				$bal_ytd = $bal_ytd;
			   else
				$bal_ytd_ = $bal_ytd - $cash_ytd;
		   }
		}
}

$cash_cum = 0;

//$journals = $conn->query("SELECT (case when sum(case when group_id = 1 and account_id = 52 then jt.amount end) is null then 0 else sum(case when group_id = 1 and account_id = 52 then jt.amount end) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id WHERE journal_date <= '$end' and dli_type = '$d_type' and account_id = 52;");

$journals = $conn->query("SELECT ((case when sum(case when account_id = 52 and group_id = 1 then jt.amount end) is null then 0 else sum(case when account_id = 52 and group_id = 1 then jt.amount end) end) - (case when sum(CASE WHEN account_id = 52 and group_id = 4  THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 4 and account_id = 52 THEN jt.amount END) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id WHERE journal_date <= '$end' and account_id = 52 and dli_type = '$d_type';");



if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
			   $cash_cum = $row['Crt'];
			    if ($cash_cum < 0)
					$bal_cum = $bal_cum;
			    else
					$bal_cum_ = $bal_cum - $cash_cum;
		   }
		}
}

$data .= '<tr><td class="cl1" style="padding: 3px;">DA Bank Balance</td>';
if ($cash_qtr > 0){
	$data .= '<td class="cl2">'.number_format($bal_qtr_/1000000, 2).'</td>';
	$data .= '<td class="cl2">'.number_format($bal_qtr_/1000000, 2).'</td>';
}
else {
	$data .= '<td class="cl2">'.number_format($bal_qtr/1000000, 2).'</td>';
	$data .= '<td class="cl2">'.number_format($bal_qtr/1000000, 2).'</td>';
}
if ($cash_ytd > 0) {
	$data .= '<td class="cl2">'.number_format($bal_ytd_/1000000, 2).'</td>';
	$data .= '<td class="cl2">'.number_format($bal_ytd_/1000000, 2).'</td>';
}
else {
	$data .= '<td class="cl2">'.number_format($bal_ytd/1000000, 2).'</td>';
	$data .= '<td class="cl2">'.number_format($bal_ytd/1000000, 2).'</td>';
}
if ($cash_cum > 0) {
	$data .= '<td class="cl2">'.number_format($bal_cum_/1000000, 2).'</td>';
	$data .= '<td class="cl2">'.number_format($bal_cum_/1000000, 2).'</td></tr>';
}
else {
	$data .= '<td class="cl2">'.number_format($bal_cum/1000000, 2).'</td>';
	$data .= '<td class="cl2">'.number_format($bal_cum/1000000, 2).'</td></tr>';
}


$data .= '<tr><td class="cl1" style="padding: 3px;">Advance at Petty Cash</td>';
if ($cash_qtr > 0){
	$data .= '<td class="cl2">'.number_format($cash_qtr/1000000, 2).'</td>';
	$data .= '<td class="cl2">'.number_format($cash_qtr/1000000, 2).'</td>';
}
else {
	$data .= '<td class="cl2">--</td>';
	$data .= '<td class="cl2">--</td>';
}
if ($cash_ytd > 0) {
	$data .= '<td class="cl2">'.number_format($cash_ytd/1000000, 2).'</td>';
	$data .= '<td class="cl2">'.number_format($cash_ytd/1000000, 2).'</td>';
} else {
	$data .= '<td class="cl2">--</td>';
	$data .= '<td class="cl2">--</td>';
}
if ($cash_cum > 0) {
	$data .= '<td class="cl2">'.number_format($cash_cum/1000000, 2).'</td>
	<td class="cl2">'.number_format($cash_cum/1000000, 2).'</td></tr>';
} else {
	$data .= '<td class="cl2">--</td>';
	$data .= '<td class="cl2">--</td></tr>';
}

$data .= '<tr><td class="cl1" style="padding: 3px;"><b>Total Fund Available</b></td><td class="cl2">'.number_format($bal_qtr/1000000, 2).'</td><td class="cl2">'.number_format($bal_qtr/1000000, 2).'</td><td class="cl2">'.number_format($bal_ytd/1000000, 2).'</td><td class="cl2">'.number_format($bal_ytd/1000000, 2).'</td><td class="cl2">'.number_format($bal_cum/1000000, 2).'</td><td class="cl2">'.number_format($bal_cum/1000000, 2).'</td></tr>';
//////////////////////



$header = '<!--mpdf
<htmlpageheader name="letterheader">
	<div style="font-size: 10pt; text-align: center; padding-top: 1mm; font-family: nikosh; ">
		<h3 style="margin-bottom: -1px;">National Special Economic Zone (NSEZ) Development Project</h3>
		<h4 style="margin-top: -5px; margin-bottom: -1px;">Credit No. IDA-6676 BD ('.$d_type.' Part)</h4>
		<h4 style="margin-top: -5px; margin-bottom: -1px;">1A. Project Source & Uses of Funds</h4>
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
	width: 28%;
	text-align: left;
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
	width: 15%;
	text-align: right;
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
  <p style="text-align: right; margin-bottom: 2px; font-size: 11px;"><i>(Amount in BDT Million)</i></p>
	  <table>
		<thead>
		<tr>
		  <th class="cl1" style="background-color: #f3e3fa;" rowspan="2"><b>Particulars</b></th>
		  <th style="background-color: #f3e3fa;" colspan="2"><b>Current Quarter</b></th>
		  <th style="background-color: #f3e3fa;" colspan="2"><b>Year to Date</b></th>
		  <th style="background-color: #f3e3fa;" colspan="2"><b>Cumulative to Date</b></th>
		</tr>
		<tr>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>IDA</b></th>
		  <th class="cl3" style="background-color: #f3e3fa;"><b>Total</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>IDA</b></th>
		  <th class="cl3" style="background-color: #f3e3fa;"><b>Total</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>IDA</b></th>
		  <th class="cl3" style="background-color: #f3e3fa;"><b>Total</b></th>
		</tr>
		</thead>		  
		<tbody>
		'.$data.'
		</tbody>
	   </table>

<br><br>
<p style="margin-bottom: 2px; font-size: 11px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Financial Management Specialist&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Project Director</p>
</body>
</html>';
$pdf->WriteHTML($header);
$pdf->WriteHTML($html);
$pdf->Output('');
exit;
?>