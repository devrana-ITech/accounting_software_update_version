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
				'orientation' => 'L',
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



//FY-Wise Expenditures

$nondli_2 = 0;
$nondli_total = 0;


$item_list = $conn->query("SELECT * from exp_group where id <= 8;"); 
while($row_item = $item_list->fetch_assoc()){
	$item_code = $row_item['id'];
	$journals = $conn->query("SELECT ((case when sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = '$item_code' and dli_type = 'Non-DLI' group by eg.id;");
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$nondli_2 = $nondli_2 + $row['Crt'];
		   }
		}
	}
}


$nondli_3 = 0;

$item_list = $conn->query("SELECT * from exp_group where id <= 8;"); 
while($row_item = $item_list->fetch_assoc()){
	$item_code = $row_item['id'];
	$journals = $conn->query("SELECT ((case when sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = '$item_code' and dli_type = 'Non-DLI' group by eg.id;");
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$nondli_3 = $nondli_3 + $row['Crt'];
		   }
		}
	}
}


$nondli_4 = 0;
$item_list = $conn->query("SELECT * from exp_group where id <= 8;"); 
while($row_item = $item_list->fetch_assoc()){
	$item_code = $row_item['id'];
	$journals = $conn->query("SELECT ((case when sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = '$item_code' and dli_type = 'Non-DLI' group by eg.id;");
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$nondli_4 = $nondli_4 + $row['Crt'];
		   }
		}
	}
}

$nondli_total = $nondli_2 + $nondli_3 + $nondli_4;


////////////D L I


$dli_2 = 0;
$dli_total = 0;

$item_list = $conn->query("SELECT * from exp_group where id <= 8;"); 
while($row_item = $item_list->fetch_assoc()){
	$item_code = $row_item['id'];
	$journals = $conn->query("SELECT ((case when sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = '$item_code' and dli_type = 'DLI' group by eg.id;");
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$dli_2 = $dli_2 + $row['Crt'];
		   }
		}
	}
}


$journals = $conn->query("SELECT (case when sum(CASE WHEN group_id = 1 THEN jt.amount END) is null then 0 else sum(CASE WHEN group_id = 1 THEN jt.amount END) end) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE year_id = 3 and (account_id <> 51 and account_id <> 52 and account_id <> 100 and new <> 1) and dli_type = 'DLI' and journal_type = 'dv';");


$dli_3 = 0;
$item_list = $conn->query("SELECT * from exp_group where id <= 8;"); 
while($row_item = $item_list->fetch_assoc()){
	$item_code = $row_item['id'];
	$journals = $conn->query("SELECT ((case when sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = '$item_code' and dli_type = 'DLI' group by eg.id;");
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$dli_3 = $dli_3 + $row['Crt'];
		   }
		}
	}
}


$dli_4 = 0;

$item_list = $conn->query("SELECT * from exp_group where id <= 8;"); 
while($row_item = $item_list->fetch_assoc()){
	$item_code = $row_item['id'];
	$journals = $conn->query("SELECT ((case when sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = '$item_code' and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = '$item_code' and dli_type = 'DLI' group by eg.id;");
	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
		   if ($row['Crt'] != 0) {
				$dli_4 = $dli_4 + $row['Crt'];
		   }
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




//Non-DLI Group-wise

 
$item_1_2 = 0;
$item_2_2 = 0;
$item_3_2 = 0;
$item_4_2 = 0;
$item_5_2 = 0;
$item_6_2 = 0;
$item_7_2 = 0;
$item_8_2 = 0;

$item_1_3 = 0;
$item_2_3 = 0;
$item_3_3 = 0;
$item_4_3 = 0;
$item_5_3 = 0;
$item_6_3 = 0;
$item_7_3 = 0;
$item_8_3 = 0;

$item_1_4 = 0;
$item_2_4 = 0;
$item_3_4 = 0;
$item_4_4 = 0;
$item_5_4 = 0;
$item_6_4 = 0;
$item_7_4 = 0;
$item_8_4 = 0;

$item_1_total = 0;
$item_2_total = 0;
$item_3_total = 0;
$item_4_total = 0;
$item_5_total = 0;
$item_6_total = 0;
$item_7_total = 0;
$item_8_total = 0;

$col_1_total = 0;
$col_2_total = 0;
$col_3_total = 0;
$col_4_total = 0;


// for FY2021-22

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 1 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_1_2 = $row['Crt'];
			} else{
				$item_1_2 = 0;
			}
		}
	}else $item_1_2 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 2 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_2_2 = $row['Crt'];
			} else{
				$item_2_2 = 0;
			}
		} 
	}else $item_2_2 = 0;


$journals = $conn->query("SELECT ((case when sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 3 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_3_2 = $row['Crt'];
			} else{
				$item_3_2 = 0;
			}
		}
	} else $item_3_2 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 4 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_4_2 = $row['Crt'];
			} else{
				$item_4_2 = 0;
			}
		}
	} else $item_4_2 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 5 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_5_2 = $row['Crt'];
			} else{
				$item_5_2 = 0;
			}
		}
	} else $item_5_2 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 6 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_6_2 = $row['Crt'];
			} else{
				$item_6_2 = 0;
			}
		}
	} else $item_6_2 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 7 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_7_2 = $row['Crt'];
			} else{
				$item_7_2 = 0;
			}			
		}
	} else $item_7_2 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 8 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_8_2 = $row['Crt'];
			} else{
				$item_8_2 = 0;
			}
		}
	} else $item_8_2 = 0;



// for FY2022-23

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 1 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_1_3 = $row['Crt'];
			} else{
				$item_1_3 = 0;
			}
		}
	}else $item_1_3 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 2 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_2_3 = $row['Crt'];
			} else{
				$item_2_3 = 0;
			}
		} 
	}else $item_2_3 = 0;


$journals = $conn->query("SELECT ((case when sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 3 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_3_3 = $row['Crt'];
			} else{
				$item_3_3 = 0;
			}
		}
	} else $item_3_3 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 4 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_4_3 = $row['Crt'];
			} else{
				$item_4_3 = 0;
			}
		}
	} else $item_4_3 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 5 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_5_3 = $row['Crt'];
			} else{
				$item_5_3 = 0;
			}
		}
	} else $item_5_3 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 6 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_6_3 = $row['Crt'];
			} else{
				$item_6_3 = 0;
			}
		}
	} else $item_6_3 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 7 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_7_3 = $row['Crt'];
			} else{
				$item_7_3 = 0;
			}			
		}
	} else $item_7_3 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 8 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_8_3 = $row['Crt'];
			} else{
				$item_8_3 = 0;
			}
		}
	} else $item_8_3 = 0;
	
	
// for FY2023-24

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 1 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_1_4 = $row['Crt'];
			} else{
				$item_1_4 = 0;
			}
		}
	}else $item_1_4 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 2 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_2_4 = $row['Crt'];
			} else{
				$item_2_4 = 0;
			}
		} 
	}else $item_2_4 = 0;


$journals = $conn->query("SELECT ((case when sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 3 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_3_4 = $row['Crt'];
			} else{
				$item_3_4 = 0;
			}
		}
	} else $item_3_4 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 4 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_4_4 = $row['Crt'];
			} else{
				$item_4_4 = 0;
			}
		}
	} else $item_4_4 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 5 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_5_4 = $row['Crt'];
			} else{
				$item_5_4 = 0;
			}
		}
	} else $item_5_4 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 6 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_6_4 = $row['Crt'];
			} else{
				$item_6_4 = 0;
			}
		}
	} else $item_6_4 = 0;


$item_6_4 = $item_6_4;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 7 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_7_4 = $row['Crt'];
			} else{
				$item_7_4 = 0;
			}			
		}
	} else $item_7_4 = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 8 and dli_type = 'Non-DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_8_4 = $row['Crt'];
			} else{
				$item_8_4 = 0;
			}
		}
	} else $item_8_4 = 0;


$item_1_total = $item_1_2 + $item_1_3 + $item_1_4;
$item_2_total = $item_2_2 + $item_2_3 + $item_2_4;
$item_3_total = $item_3_2 + $item_3_3 + $item_3_4;
$item_4_total = $item_4_2 + $item_4_3 + $item_4_4;
$item_5_total = $item_5_2 + $item_5_3 + $item_5_4;
$item_6_total = $item_6_2 + $item_6_3 + $item_6_4;
$item_7_total = $item_7_2 + $item_7_3 + $item_7_4;
$item_8_total = $item_8_2 + $item_8_3 + $item_8_4;


$col_1_total = $item_1_2 + $item_2_2 + $item_3_2 + $item_4_2 + $item_5_2 + $item_6_2 + $item_7_2 + $item_8_2;
$col_2_total = $item_1_3 + $item_2_3 + $item_3_3 + $item_4_3 + $item_5_3 + $item_6_3 + $item_7_3 + $item_8_3;
$col_3_total = $item_1_4 + $item_2_4 + $item_3_4 + $item_4_4 + $item_5_4 + $item_6_4 + $item_7_4 + $item_8_4;

$col_4_total = $col_1_total + $col_2_total + $col_3_total;





//         DLI Part by Main Group   --->
 
$item_1_2_d = 0;
$item_2_2_d = 0;
$item_3_2_d = 0;
$item_4_2_d = 0;
$item_5_2_d = 0;
$item_6_2_d = 0;
$item_7_2_d = 0;
$item_8_2_d = 0;

$item_1_3_d = 0;
$item_2_3_d = 0;
$item_3_3_d = 0;
$item_4_3_d = 0;
$item_5_3_d = 0;
$item_6_3_d = 0;
$item_7_3_d = 0;
$item_8_3_d = 0;

$item_1_4_d = 0;
$item_2_4_d = 0;
$item_3_4_d = 0;
$item_4_4_d = 0;
$item_5_4_d = 0;
$item_6_4_d = 0;
$item_7_4_d = 0;
$item_8_4_d = 0;

$item_1_total_d = 0;
$item_2_total_d = 0;
$item_3_total_d = 0;
$item_4_total_d = 0;
$item_5_total_d = 0;
$item_6_total_d = 0;
$item_7_total_d = 0;
$item_8_total_d = 0;

$col_1_total_d = 0;
$col_2_total_d = 0;
$col_3_total_d = 0;
$col_4_total_d = 0;


// for FY2021-22 

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 1 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_1_2_d = $row['Crt'];
			} else{
				$item_1_2_d = 0;
			}
		}
	}else $item_1_2_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 2 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_2_2_d = $row['Crt'];
			} else{
				$item_2_2_d = 0;
			}
		} 
	}else $item_2_2_d = 0;


$journals = $conn->query("SELECT ((case when sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 3 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_3_2_d = $row['Crt'];
			} else{
				$item_3_2_d = 0;
			}
		}
	} else $item_3_2_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 4 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_4_2_d = $row['Crt'];
			} else{
				$item_4_2_d = 0;
			}
		}
	} else $item_4_2_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 5 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_5_2_d = $row['Crt'];
			} else{
				$item_5_2_d = 0;
			}
		}
	} else $item_5_2_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 6 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_6_2_d = $row['Crt'];
			} else{
				$item_6_2_d = 0;
			}
		}
	} else $item_6_2_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 7 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_7_2_d = $row['Crt'];
			} else{
				$item_7_2_d = 0;
			}			
		}
	} else $item_7_2_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 2 and eg.id = 8 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_8_2_d = $row['Crt'];
			} else{
				$item_8_2_d = 0;
			}
		}
	} else $item_8_2_d = 0;



// for FY2022-23

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 1 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_1_3_d = $row['Crt'];
			} else{
				$item_1_3_d = 0;
			}
		}
	}else $item_1_3_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 2 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_2_3_d = $row['Crt'];
			} else{
				$item_2_3_d = 0;
			}
		} 
	}else $item_2_3_d = 0;


$journals = $conn->query("SELECT ((case when sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 3 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_3_3_d = $row['Crt'];
			} else{
				$item_3_3_d = 0;
			}
		}
	} else $item_3_3_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 4 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_4_3_d = $row['Crt'];
			} else{
				$item_4_3_d = 0;
			}
		}
	} else $item_4_3_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 5 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_5_3_d = $row['Crt'];
			} else{
				$item_5_3_d = 0;
			}
		}
	} else $item_5_3_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 6 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_6_3_d = $row['Crt'];
			} else{
				$item_6_3_d = 0;
			}
		}
	} else $item_6_3_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 7 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_7_3_d = $row['Crt'];
			} else{
				$item_7_3_d = 0;
			}			
		}
	} else $item_7_3_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 3 and eg.id = 8 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_8_3_d = $row['Crt'];
			} else{
				$item_8_3_d = 0;
			}
		}
	} else $item_8_3_d = 0;
	
	
// for FY2023-24

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 1 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 1 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_1_4_d = $row['Crt'];
			} else{
				$item_1_4_d = 0;
			}
		}
	}else $item_1_4_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 2 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 2 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_2_4_d = $row['Crt'];
			} else{
				$item_2_4_d = 0;
			}
		} 
	}else $item_2_4_d = 0;


$journals = $conn->query("SELECT ((case when sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 3 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 3 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_3_4_d = $row['Crt'];
			} else{
				$item_3_4_d = 0;
			}
		}
	} else $item_3_4_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 4 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 4 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_4_4_d = $row['Crt'];
			} else{
				$item_4_4_d = 0;
			}
		}
	} else $item_4_4_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 5 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 5 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_5_4_d = $row['Crt'];
			} else{
				$item_5_4_d = 0;
			}
		}
	} else $item_5_4_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 6 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 6 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_6_4_d = $row['Crt'];
			} else{
				$item_6_4_d = 0;
			}
		}
	} else $item_6_4_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 7 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 7 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_7_4_d = $row['Crt'];
			} else{
				$item_7_4_d = 0;
			}			
		}
	} else $item_7_4_d = 0;

$journals = $conn->query("SELECT ((case when sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52  then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 1 and account_id <> 51 and account_id <> 52 then jt.amount end) end) - (case when sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) is null then 0 else sum(case when eg.id = 8 and group_id = 4 and account_id <> 51 and account_id <> 52 then jt.amount end) end)) as Crt from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join exp_group eg on eg.id = je.exp_group WHERE year_id = 4 and eg.id = 8 and dli_type = 'DLI' group by eg.id;");

	if ($journals->num_rows > 0){
		while($row = $journals->fetch_assoc()){
			if ($row['Crt'] != 0) {
				$item_8_4_d = $row['Crt'];
			} else{
				$item_8_4_d = 0;
			}
		}
	} else $item_8_4_d = 0;


$item_1_total_d = $item_1_2_d + $item_1_3_d + $item_1_4_d;
$item_2_total_d = $item_2_2_d + $item_2_3_d + $item_2_4_d;
$item_3_total_d = $item_3_2_d + $item_3_3_d + $item_3_4_d;
$item_4_total_d = $item_4_2_d + $item_4_3_d + $item_4_4_d;
$item_5_total_d = $item_5_2_d + $item_5_3_d + $item_5_4_d;
$item_6_total_d = $item_6_2_d + $item_6_3_d + $item_6_4_d;
$item_7_total_d = $item_7_2_d + $item_7_3_d + $item_7_4_d;
$item_8_total_d = $item_8_2_d + $item_8_3_d + $item_8_4_d;


$col_1_total_d = $item_1_2_d + $item_2_2_d + $item_3_2_d + $item_4_2_d + $item_5_2_d + $item_6_2_d + $item_7_2_d + $item_8_2_d;
$col_2_total_d = $item_1_3_d + $item_2_3_d + $item_3_3_d + $item_4_3_d + $item_5_3_d + $item_6_3_d + $item_7_3_d + $item_8_3_d;
$col_3_total_d = $item_1_4_d + $item_2_4_d + $item_3_4_d + $item_4_4_d + $item_5_4_d + $item_6_4_d + $item_7_4_d + $item_8_4_d;

$col_4_total_d = $col_1_total_d + $col_2_total_d + $col_3_total_d;



// Combined Dli and Non-DLI     -->

$item_1_total_d = $item_1_total_d + $item_1_total;
$item_2_total_d = $item_2_total_d + $item_2_total;
$item_3_total_d = $item_3_total_d + $item_3_total;
$item_4_total_d = $item_4_total_d + $item_4_total;
$item_5_total_d = $item_5_total_d + $item_5_total;
$item_6_total_d = $item_6_total_d + $item_6_total;
$item_7_total_d = $item_7_total_d + $item_7_total;
$item_8_total_d = $item_8_total_d + $item_8_total;


$col_1_total = $item_1_2 + $item_2_2 + $item_3_2 + $item_4_2 + $item_5_2 + $item_6_2 + $item_7_2 + $item_8_2;
$col_1_total_d = $item_1_2_d + $item_2_2_d + $item_3_2_d + $item_4_2_d + $item_5_2_d + $item_6_2_d + $item_7_2_d + $item_8_2_d;
$col2 = $col_1_total + $col_1_total_d;

$col_2_total = $item_1_3 + $item_2_3 + $item_3_3 + $item_4_3 + $item_5_3 + $item_6_3 + $item_7_3 + $item_8_3;
$col_2_total_d = $item_1_3_d + $item_2_3_d + $item_3_3_d + $item_4_3_d + $item_5_3_d + $item_6_3_d + $item_7_3_d + $item_8_3_d;
$col3 = $col_2_total + $col_2_total_d;

$col_3_total = $item_1_4 + $item_2_4 + $item_3_4 + $item_4_4 + $item_5_4 + $item_6_4 + $item_7_4 + $item_8_4;
$col_3_total_d = $item_1_4_d + $item_2_4_d + $item_3_4_d + $item_4_4_d + $item_5_4_d + $item_6_4_d + $item_7_4_d + $item_8_4_d;
$col4 = $col_3_total + $col_3_total_d;

$col_total = $col2 + $col3 + $col4;

$data .= '
<p style="text-align: center; margin-bottom: -100px; font-size:16px;"><b>Financial Year-wise Expenditures by Main Groups</b></p>
<p style="text-align: right; margin-bottom: 0px; font-size: 14px;"><i>(Amount in BDT)</i></p>
        <table>
			<thead>
				<tr>
				  <th rowspan="2" style="background-color: #d48104; color: white; font-size: 10px; width: 10%;"><b>Group Name</b></th>
				  <th colspan="3" style="background-color: #d48104; color: white; font-size: 10px; text-align: center;"><b>2021-22</b></th>
				  <th colspan="3" style="background-color: #d48104; color: white; font-size: 10px; text-align: center;"><b>2022-23</b></th>
				  <th colspan="3" style="background-color: #d48104; color: white; font-size: 10px; text-align: center;"><b>2023-24</b></th>
				  <th rowspan="2" style="background-color: #d48104; color: white; font-size: 10px; width: 9%; text-align: right;"><b>Total</b></th>
				</tr>
				<tr>
				  <th style="background-color: #d48104; color: white; font-size: 10px; width: 9%; text-align: right;"><b>Non-DLI</b></th>
				  <th style="background-color: #d48104; color: white; font-size: 10px; width: 9%; text-align: right;"><b>DLI</b></th>
				  <th style="background-color: #d48104; color: white; font-size: 10px; width: 9%; text-align: right;"><b>Total</b></th>
				  
				  <th style="background-color: #d48104; color: white; font-size: 10px; width: 9%; text-align: right;"><b>Non-DLI</b></th>			  
				  <th style="background-color: #d48104; color: white; font-size: 10px; width: 9%; text-align: right;"><b>DLI</b></th>
				  <th style="background-color: #d48104; color: white; font-size: 10px; width: 9%; text-align: right;"><b>Total</b></th>
				  
				  <th style="background-color: #d48104; color: white; font-size: 10px; width: 9%; text-align: right;"><b>Non-DLI</b></th>
				  <th style="background-color: #d48104; color: white; font-size: 10px; width: 9%; text-align: right;"><b>DLI</b></th>
				  <th style="background-color: #d48104; color: white; font-size: 10px; width: 9%; text-align: right;"><b>Total</b></th>
				</tr>
			</thead>		  
			<tbody>
				<tr>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 10%; text-align: left;"><b>a) Goods</b></td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($item_1_2, 2).'</b></td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_1_2_d <= 0) $data .= '--'; else $data .= ''.number_format($item_1_2_d, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format(($item_1_2 + $item_1_2_d), 2).'</b></td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($item_1_3, 2).'</b></td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_1_3_d <= 0) $data .= '--'; else $data .= ''.number_format($item_1_3_d, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if (($item_1_3 + $item_1_3_d) <= 0) $data .= '--'; else $data .= ''.number_format(($item_1_3 + $item_1_3_d), 2).'</b>';
				  $data .= '</td>		  
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($item_1_4, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_1_4_d <= 0) $data .= '--'; else $data .= ''.number_format($item_1_4_d, 2).'</b>';
				  $data .= '</td>	  
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if (($item_1_4 + $item_1_4_d) <= 0) $data .= '--'; else $data .= ''.number_format(($item_1_4 + $item_1_4_d), 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_1_total_d <= 0) $data .= '--'; else $data .= ''.number_format($item_1_total_d, 2).'</b>';
				  $data .= '</td>
				</tr>
				<tr>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 10%; text-align: left;"><b>b) Works</b></td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_2_2 <= 0) $data .= '--'; else $data .= ''.number_format($item_2_2, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_2_2_d <= 0) $data .= '--'; else $data .= ''.number_format($item_2_2_d, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if (($item_2_2 +  $item_2_2_d) <= 0) $data .= '--'; else $data .= ''.number_format(($item_2_2 +  $item_2_2_d), 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($item_2_3, 2).'</b></td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($item_2_3_d, 2).'</b></td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format(($item_2_3 + $item_2_3_d), 2).'</b></td>
				  
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_2_4 <= 0) $data .= '--'; else $data .= ''.number_format($item_2_4, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_2_4_d <= 0) $data .= '--'; else $data .= ''.number_format($item_2_4_d, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if (($item_2_4 + $item_2_4_d) <= 0) $data .= '--'; else $data .= ''.number_format(($item_2_4 + $item_2_4_d), 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($item_2_total_d, 2).'</b>';
				  $data .= '</td>
				</tr>
				<tr>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 10%; text-align: left;"><b>c) Consultants\' Services</b></td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($item_3_2, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_3_2_d <= 0) $data .= '--'; else $data .= ''.number_format($item_3_2_d, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format(($item_3_2 + $item_3_2_d), 2).'</b></td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($item_3_3, 2).'</b></td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_3_3_d <= 0) $data .= '--'; else $data .= ''.number_format($item_3_3_d, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format(($item_3_3 + $item_3_3_d), 2).'</b></td>
				  
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($item_3_4, 2).'</b></td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_3_4_d <= 0) $data .= '--'; else $data .= ''.number_format($item_3_4_d, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #faf5fc;font-size: 10px; width: 9%; text-align: right;"><b>'.number_format(($item_3_4 + $item_3_4_d), 2).'</b></td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($item_3_total_d, 2).'</b></td>
				</tr>
				<tr>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 10%; text-align: left;"><b>d) Non-Consulting Services</b></td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($item_4_2, 2).'</b></td>
				  <td style="background-color: #f2ebf5;font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_4_2_d <= 0) $data .= '--'; else $data .= ''.number_format($item_4_2_d, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format(($item_4_2 + $item_4_2_d), 2).'</b></td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($item_4_3, 2).'</b></td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_4_4_d <= 0) $data .= '--'; else $data .= ''.number_format($item_4_4_d, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format(($item_4_3 + $item_4_3_d), 2).'</b></td>
				  
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($item_4_4, 2).'</b></td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_4_4_d <= 0) $data .= '--'; else $data .= ''.number_format($item_4_4_d, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format(($item_4_4 + $item_4_4_d), 2).'</b></td>
				  
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($item_4_total_d, 2).'</b></td>
				</tr>
				<tr>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 10%; text-align: left;"><b>e) Training, Workshop</b></td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($item_5_2, 2).'</b></td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_5_2_d <= 0) $data .= '--'; else $data .= ''.number_format($item_5_2_d, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format(($item_5_2 + $item_5_2_d), 2).'</b></td>
				  
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($item_5_3, 2).'</b></td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_5_3_d <= 0) $data .= '--'; else $data .= ''.number_format($item_5_3_d, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format(($item_5_3 + $item_5_3_d), 2).'</b></td>
				  
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($item_5_4, 2).'</b></td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_5_4_d <= 0) $data .= '--'; else $data .= ''.number_format($item_5_4_d, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format(($item_5_4 + $item_5_4_d), 2).'</b></td>
				  
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($item_5_total_d, 2).'</b></td>
				</tr>
				<tr>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 10%; text-align: left;"><b>f) Operating Costs</b></td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($item_6_2, 2).'</b></td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_6_2_d <= 0) $data .= '--'; else $data .= ''.number_format($item_6_2_d, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format(($item_6_2 + $item_6_2_d), 2).'</b></td>
				  
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($item_6_3, 2).'</b></td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_6_3_d <= 0) $data .= '--'; else $data .= ''.number_format($item_6_3_d, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format(($item_6_3 + $item_6_3_d), 2).'</b></td>
				  
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($item_6_4, 2).'</b></td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_6_4_d <= 0) $data .= '--'; else $data .= ''.number_format($item_6_4_d, 2).'?></b>';
				  $data .= '</td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format(($item_6_4 + $item_6_4_d), 2).'</b></td>
				  
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($item_6_total_d, 2).'</b></td>
				</tr>
				<tr>
				  <td style="background-color: #f4f5f2; font-size: 10px;" colspan="11"><b>g) Grants under Part 3.2(b)</b></td>
				  
				</tr>
				<tr>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 10%; text-align: left;"><b>&nbsp;&nbsp;&nbsp;&nbsp;(i) Voucher Program</b></td>
				  
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_7_2 <= 0) $data .= '--'; else $data .= ''.number_format($item_7_2, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_7_2_d <= 0) $data .= '--'; else $data .= ''.number_format($item_7_2_d, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if (($item_7_2 + $item_7_2_d) <= 0) $data .= '--'; else $data .= ''.number_format(($item_7_2 + $item_7_2_d), 2).'</b>';
				  $data .= '</td>
				  
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_7_3 <= 0) $data .= '--'; else $data .= ''.number_format($item_7_3, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_7_3_d <= 0) $data .= '--'; else $data .= ''.number_format($item_7_3_d, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if (($item_7_3 + $item_7_3_d) <= 0) $data .= '--'; else $data .= ''.number_format(($item_7_3 + $item_7_3_d), 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_7_4 <= 0) $data .= '--'; else $data .= ''.number_format($item_7_4, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_7_4_d <= 0) $data .= '--'; else $data .= ''.number_format($item_7_4_d, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if (($item_7_4 + $item_7_4_d) <= 0) $data .= '--'; else $data .= ''.number_format(($item_7_4 + $item_7_4_d), 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #faf5fc; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_7_total_d <= 0) $data .= '--'; else $data .= ''.number_format($item_7_total_d, 2).'</b>';
				  $data .= '</td>
				</tr>
				<tr>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 10%; text-align: left;"><b>&nbsp;&nbsp;&nbsp;(ii) Grant Program</b></td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_8_2 <= 0) $data .= '--'; else $data .= ''.number_format($item_8_2, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_8_2_d <= 0) $data .= '--'; else $data .= ''.number_format($item_8_2_d, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if (($item_8_2 + $item_8_2_d) <= 0) $data .= '--'; else $data .= ''.number_format(($item_8_2 + $item_8_2_d), 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_8_3 <= 0) $data .= '--'; else $data .= ''.number_format($item_8_3, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_8_3_d <= 0) $data .= '--'; else $data .= ''.number_format($item_8_3_d, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if (($item_8_3 + $item_8_3_d) <= 0) $data .= '--'; else $data .= ''.number_format(($item_8_3 + $item_8_3_d), 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_8_4 <= 0) $data .= '--'; else $data .= ''.number_format($item_8_4, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_8_4_d <= 0) $data .= '--'; else $data .= ''.number_format($item_8_4_d, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if (($item_8_4 + $item_8_4_d) <= 0) $data .= '--'; else $data .= ''.number_format(($item_8_4 + $item_8_4_d), 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #f2ebf5; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($item_8_total_d <= 0) $data .= '--'; else $data .= ''.number_format($item_8_total_d, 2).'</b>';
				  $data .= '</td>
				</tr>
				<tr>
				  <td style="background-color: #ffe6e6; font-size: 10px; width: 10%; text-align: left;"><b>Total</b></td>
				  <td style="background-color: #ffe6e6; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($col_1_total, 2).'</b></td>
				  <td style="background-color: #ffe6e6; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($col_1_total_d <= 0) $data .= '--'; else $data .= ''.number_format($col_1_total_d, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #ffe6e6; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($col2, 2).'</b></td>
				  
				  <td style="background-color: #ffe6e6; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($col_2_total, 2).'</b></td>
				  <td style="background-color: #ffe6e6; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($col_2_total_d, 2).'</b></td>
				  <td style="background-color: #ffe6e6; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($col3, 2).'</b></td>
				  
				  <td style="background-color: #ffe6e6; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($col_3_total, 2).'</b></td>
				  <td style="background-color: #ffe6e6; font-size: 10px; width: 9%; text-align: right;"><b>';
				  if ($col_3_total_d <= 0) $data .= '--'; else $data .= ''.number_format($col_3_total_d, 2).'</b>';
				  $data .= '</td>
				  <td style="background-color: #ffe6e6; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($col4, 2).'</b></td>
				  <td style="background-color: #ffe6e6; font-size: 10px; width: 9%; text-align: right;"><b>'.number_format($col_total, 2).'</b></td>
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
	<p style="margin-bottom: 2px; font-size: 11px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Financial Management Specialist&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Project Director</p>
  </body>
</html>';
$pdf->WriteHTML($header);
$pdf->WriteHTML($html);
$pdf->Output('');
exit;
?>