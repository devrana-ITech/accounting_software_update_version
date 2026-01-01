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

*/

$pdf = new \Mpdf\Mpdf([
				'mode' => 'utf-8',
				'format' => 'A4',
				'orientation' => 'P',
				'margin_header' => '6',
				'margin_top' => '14',
				'margin_bottom' => '12',
				'margin_footer' => '8',
				'deafult_font_size' => 8,
				'default_font' => 'nikosh'
			]);
			
/*
$journals = $conn->query("SELECT je.dli_type, al.name, sum(pa.gov_amount) as target_gob_amount, sum(pa.aid_amount) as target_pa_amount, sum(pa.gov_amount+pa.aid_amount) as target_total_amount, sum(jt.amount) as exp_amount, al.id as id FROM `journal_items` jt, `journal_entries` je, account_list al, pr_aid pa WHERE je.id = jt.journal_id and al.id = jt.account_id and pa.account_id = jt.account_id and pa.year_id = je.year_id and journal_date <= '2023-06-30' group by jt.account_id union all select
'', a.name, p.gov_amount, p.aid_amount, p.gov_amount+p.aid_amount as t_total, '', a.id as id from account_list a, pr_aid p where a.id = p.account_id and p.year_id = 4 union all select je.dli_type, al.name, sum(pa.gov_amount) as target_gob_amount, sum(pa.aid_amount) as target_pa_amount,  sum(pa.gov_amount+pa.aid_amount) as target_total_amount, sum(jt.amount) as exp_amount, al.id as id FROM `journal_items` jt, `journal_entries` je, account_list al, pr_aid pa WHERE je.id = jt.journal_id and al.id = jt.account_id and pa.account_id = jt.account_id and pa.year_id = je.year_id and journal_date > '2023-06-30' and journal_date <= '2024-06-30' group by jt.account_id 
order by id asc;");

SELECT al.name, d.dpp_amount FROM account_list al, dpp d WHERE al.id = d.account_id order by al.id asc;

select gov_amount, aid_amount, gov_amount + aid_amount as t_total from pr_aid where account_id = 1 and year_id = 2;

select je.dli_type, al.name, pa.gov_amount as target_gob_amount, pa.aid_amount as target_pa_amount, pa.gov_amount+pa.aid_amount as target_total_amount, sum(jt.amount) as exp_amount, al.id as id FROM `journal_items` jt, `journal_entries` je, account_list al, pr_aid pa WHERE je.id = jt.journal_id and al.id = jt.account_id and pa.account_id = jt.account_id and pa.year_id = je.year_id and journal_date > '2022-06-30' and journal_date <= '2023-06-30' and al.id = 1 group by jt.account_id;
*/

$group_list = $conn->query("SELECT * from imed_report_group order by id asc;");

while($row_group = $group_list->fetch_assoc()){
 $gr_id = $row_group['id'];
 if ($gr_id == 1)
	$data .= '<tr><td style="padding: 3px;" colspan="13"><b>a. '.$row_group['group_name'].'</b></td></tr>';
 if ($gr_id == 2)
	$data .= '<tr><td style="padding: 3px;" colspan="13"><b>b. '.$row_group['group_name'].'</b></td></tr>';
 $gr_dpp_total = 0;
 $gr_gob_total_ach = 0;
 $gr_pa_total_ach = 0;
 $a_total_ach_gr = 0;
 $gr_target_ach = 0;

 $gr_gob_total = 0;
 $gr_pa_total = 0;
 $gr_t_total = 0;
 
 $gr_prog_gob_total = 0;
 $gr_prog_pa_total = 0;
 $gr_prog_total = 0;
 $gr_prog_target_total = 0;
 
 $gr_ach_gob_total = 0;
 $gr_ach_pa_total = 0;
 $gr_ach_total = 0;
 $gr_ach_target = 0;
 
 $gr_target_gob_total = 0;
 $gr_target_pa_total = 0;
 $gr_target_total = 0;
 
 $gr_pr_gob_total = 0;
 $gr_pr_pa_total = 0;
 $gr_pr_total = 0;
 $gr_pr_target = 0;	

 $group_sub_list = $conn->query("SELECT * from imed_report_sub_group where group_id = '$gr_id' order by id asc;");

 while($row_sub_group = $group_sub_list->fetch_assoc()){
	 $sub_gr_id = $row_sub_group['id'];
	 if ($gr_id != 1){
		$data .= '<tr><td style="padding: 3px;" colspan="13"><b><i>'.$row_sub_group['sub_group_name'].'</i></b></td></tr>'; 
	 }
$sub_gr_dpp_total = 0;
$sub_gr_gob_total_ach = 0;
$sub_gr_pa_total_ach = 0;
$a_total_ach_sub_gr = 0;
$sub_gr_target_ach = 0;	

$sub_gr_gob_total = 0;
$sub_gr_pa_total = 0;
$sub_gr_t_total = 0;

$sub_gr_prog_gob_total = 0;
$sub_gr_prog_pa_total = 0;
$sub_gr_prog_total = 0;
$sub_gr_prog_target_total = 0;

$dpp_list = $conn->query("SELECT al.id as id, al.name, d.dpp_amount FROM account_list al, dpp d WHERE al.id = d.account_id and d.group_id = '$gr_id' and d.sub_group_id = '$sub_gr_id' order by d.id asc;");


while($row_dpp = $dpp_list->fetch_assoc()){
	$data .= '<tr><td class="cl1" style="padding: 3px;">'.$row_dpp['name'].'</td>';
	$data .= '<td class="cl3" style="padding: 3px;">'.number_format($row_dpp['dpp_amount']/100000, 2).'</td>';
	
	$gr_dpp_total = $gr_dpp_total + $row_dpp['dpp_amount'];
	$sub_gr_dpp_total = $sub_gr_dpp_total + $row_dpp['dpp_amount'];
	
	$id = $row_dpp['id'];
	$achievement_list = $conn->query("SELECT je.dli_type, al.name, sum(pa.gov_amount) as target_gob_amount, sum(pa.aid_amount) as target_pa_amount, sum(pa.gov_amount+pa.aid_amount) as target_total_amount, sum(jt.amount) as exp_amount, al.id as id FROM `journal_items` jt, `journal_entries` je, account_list al, pr_aid pa WHERE je.id = jt.journal_id and al.id = jt.account_id and pa.account_id = jt.account_id and pa.year_id = je.year_id and journal_date <= '2022-06-30' and al.id = '$id' group by jt.account_id;");
	
   if ($achievement_list->num_rows > 0) {
	while($row_ach = $achievement_list->fetch_assoc()){
		$a_total = 0;
		if ($row_ach['dli_type'] == 'GoB') {
			$data .= '<td class="cl2" style="padding: 3px;">'.format_num($row_ach['exp_amount']/100000).'</td>';
			$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			$a_total = $a_total + $row_ach['exp_amount'];
			$gr_gob_total_ach = $gr_gob_total_ach + $row_ach['exp_amount'];
			$sub_gr_gob_total_ach = $sub_gr_gob_total_ach + $row_ach['exp_amount'];
		}else
		{
			$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			$data .= '<td class="cl2" style="padding: 3px;">'.format_num($row_ach['exp_amount']/100000).'</td>';
			$a_total = $a_total + $row_ach['exp_amount'];
			$gr_pa_total_ach = $gr_pa_total_ach + $row_ach['exp_amount'];
			$sub_gr_pa_total_ach = $sub_gr_pa_total_ach + $row_ach['exp_amount'];
		}
		$data .= '<td class="cl2" style="padding: 3px;">'.format_num($a_total/100000).'</td>';
		$data .= '<td class="cl4" style="padding: 3px;">'.number_format(($a_total/$row_ach['target_total_amount'])*100, 2).'%</td>';
		$a_total_ach_gr = $a_total_ach_gr + $a_total;
		$a_total_ach_sub_gr = $a_total_ach_sub_gr + $a_total;
		$gr_target_ach = $gr_target_ach + $row_ach['target_total_amount'];
		$sub_gr_target_ach = $sub_gr_target_ach + $row_ach['target_total_amount'];
	}
   }else {
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
		$data .= '<td class="cl4" style="padding: 3px;">--</td>';
   }
	
	$target_list = $conn->query("select gov_amount, aid_amount, gov_amount + aid_amount as t_total from pr_aid where account_id = '$id' and year_id = 3;");
   if ($target_list->num_rows > 0) {	
	while($row_target = $target_list->fetch_assoc()){
		$data .= '<td class="cl2" style="padding: 3px;">'.format_num($row_target['gov_amount']/100000).'</td>';
		$data .= '<td class="cl2" style="padding: 3px;">'.format_num($row_target['aid_amount']/100000).'</td>';
		$data .= '<td class="cl2" style="padding: 3px;">'.format_num($row_target['t_total']/100000).'</td>';
		$gr_gob_total = $gr_gob_total + $row_target['gov_amount'];
		$gr_pa_total = $gr_pa_total + $row_target['aid_amount'];
		$gr_t_total = $gr_t_total + $row_target['t_total'];

		$sub_gr_gob_total = $sub_gr_gob_total + $row_target['gov_amount'];
		$sub_gr_pa_total = $sub_gr_pa_total + $row_target['aid_amount'];
		$sub_gr_t_total = $sub_gr_t_total + $row_target['t_total'];

	}
   } else {
	    $data .= '<td class="cl2" style="padding: 3px;">--</td>';
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
   }
	
	$progress_list = $conn->query("select je.dli_type, al.name, pa.gov_amount as target_gob_amount, pa.aid_amount as target_pa_amount, pa.gov_amount+pa.aid_amount as target_total_amount, sum(jt.amount) as exp_amount, al.id as id FROM `journal_items` jt, `journal_entries` je, account_list al, pr_aid pa WHERE je.id = jt.journal_id and al.id = jt.account_id and pa.account_id = jt.account_id and pa.year_id = je.year_id and journal_date > '2022-06-30' and journal_date <= '2023-06-30' and al.id = '$id' group by jt.account_id;");
    if ($progress_list->num_rows > 0) {		
	while($row_progress = $progress_list->fetch_assoc()){
		$a_total = 0;
		if ($row_progress['dli_type'] == 'GoB') {
			$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row_progress['exp_amount']/100000,2).'</td>';
			$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			$a_total = $a_total + $row_progress['exp_amount'];
			$gr_prog_gob_total = $gr_prog_gob_total + $row_progress['exp_amount'];
			$sub_gr_prog_gob_total = $sub_gr_prog_gob_total + $row_progress['exp_amount'];
		}else
		{
			$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			$data .= '<td class="cl2" style="padding: 3px;">'.number_format($row_progress['exp_amount']/100000,2).'</td>';
			$a_total = $a_total + $row_progress['exp_amount'];
			$gr_prog_pa_total = $gr_prog_pa_total + $row_progress['exp_amount'];
			$sub_gr_prog_pa_total = $sub_gr_prog_pa_total + $row_progress['exp_amount'];
		}
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format($a_total/100000,2).'</td>';
		$data .= '<td class="cl4" style="padding: 3px;">'.number_format(($a_total/$row_progress['target_total_amount'])*100, 2).'%</td></tr>';
		$gr_prog_total = $gr_prog_total + $a_total;
		$sub_gr_prog_total = $sub_gr_prog_total + $a_total;
		$gr_prog_target_total = $gr_prog_target_total + $row_progress['target_total_amount'];
		$sub_gr_prog_target_total = $sub_gr_prog_target_total + $row_progress['target_total_amount'];
	}
	} else {
			$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			$data .= '<td class="cl4" style="padding: 3px;">--</td>';
	}
}


  if ($gr_id != 1){
    $data .= '<tr><td class="cl1"><b>Total '.$row_sub_group['sub_group_name'].'</b></td><td class="cl3" style="padding: 3px;">'.number_format($sub_gr_dpp_total/100000, 2).'</td>';
	if ($sub_gr_gob_total_ach != 0)
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format($sub_gr_gob_total_ach/100000, 2).'</td>';
	else
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	if ($sub_gr_pa_total_ach != 0)
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format($sub_gr_pa_total_ach/100000, 2).'</td>';
	else
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	if ($a_total_ach_sub_gr != 0)
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format($a_total_ach_sub_gr/100000, 2).'</td>';
	else
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	if ($sub_gr_target_ach != 0)
		$data .= '<td class="cl4" style="padding: 3px;">'.number_format(($a_total_ach_sub_gr/$sub_gr_target_ach)*100, 2).'</td></tr>';
	else
		$data .= '<td class="cl4" style="padding: 3px;">--</td></tr>';

	if ($sub_gr_gob_total != 0)
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format($sub_gr_gob_total/100000, 2).'</td>';
	else
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	if ($sub_gr_pa_total != 0)
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format($sub_gr_pa_total/100000, 2).'</td>';
	else
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	if ($sub_gr_t_total != 0)
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format($sub_gr_t_total/100000, 2).'</td>';
	else
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	
	if ($sub_gr_prog_gob_total != 0)
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format($sub_gr_prog_gob_total/100000, 2).'</td>';
	else
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	if ($sub_gr_prog_pa_total != 0)
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format($sub_gr_prog_pa_total/100000, 2).'</td>';
	else
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	if ($sub_gr_prog_total != 0)
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format($sub_gr_prog_total/100000, 2).'</td>';
	else
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	if ($sub_gr_prog_target_total != 0)
		$data .= '<td class="cl4" style="padding: 3px;">'.number_format(($sub_gr_prog_total/$sub_gr_prog_target_total)*100, 2).'</td></tr>';
	else
		$data .= '<td class="cl4" style="padding: 3px;">--</td></tr>';

  }

 }
 $data .= '<tr><td class="cl1"><b>Sub-Total ('.$row_group['group_name'].')</b></td><td class="cl3" style="padding: 3px;">'.number_format($gr_dpp_total/100000, 2).'</td>';
 if ($gr_id == 2){
	 $capital_exp = $gr_dpp_total;
 }
 if ($gr_id == 1){
	 $recurrence_exp = $gr_dpp_total;
 }
 
 
 	if ($gr_gob_total_ach != 0){
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format($gr_gob_total_ach/100000, 2).'</td>';
		$gr_ach_gob_total = $gr_ach_gob_total + $gr_gob_total_ach;
	}
	else
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	if ($gr_pa_total_ach != 0) {
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format($gr_pa_total_ach/100000, 2).'</td>';
		$gr_ach_pa_total = $gr_ach_pa_total + $gr_pa_total_ach;
	}
	else
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	if ($a_total_ach_gr != 0) {
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format($a_total_ach_gr/100000, 2).'</td>';
		$gr_ach_total = $gr_ach_total + $a_total_ach_gr;
	}
	else
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	if ($gr_target_ach != 0) {
		$data .= '<td class="cl4" style="padding: 3px;">'.number_format(($a_total_ach_gr/$gr_target_ach)*100, 2).'</td></tr>';
		$gr_ach_target = $gr_ach_target + $gr_target_ach;
	}
	else
		$data .= '<td class="cl4" style="padding: 3px;">--</td></tr>';
	
	

	if ($gr_gob_total != 0) {
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format($gr_gob_total/100000, 2).'</td>';
		$gr_target_gob_total = $gr_target_gob_total + $gr_gob_total;
	}
	else
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	if ($gr_pa_total != 0) {
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format($gr_pa_total/100000, 2).'</td>';
		$gr_target_pa_total =  $gr_target_pa_total + $gr_pa_total;
	}
	else
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	if ($gr_t_total != 0) {
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format($gr_t_total/100000, 2).'</td>';
		$gr_target_total = $gr_target_total + $gr_t_total;
	}
	else
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	
	
 
 
	
	if ($gr_prog_gob_total != 0) {
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format($gr_prog_gob_total/100000, 2).'</td>';
		$gr_pr_gob_total = $gr_pr_gob_total + $gr_prog_gob_total;
	}
	else
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	if ($gr_prog_pa_total != 0){
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format($gr_prog_pa_total/100000, 2).'</td>';
		$gr_pr_pa_total = $gr_pr_pa_total + $gr_prog_pa_total;
	}
	else
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	if ($gr_prog_total != 0) {
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format($gr_prog_total/100000, 2).'</td>';
		$gr_pr_total = $gr_pr_total + $gr_prog_total;
	}
	else
		$data .= '<td class="cl2" style="padding: 3px;">--</td>';
	if ($gr_prog_target_total != 0) {
		$data .= '<td class="cl4" style="padding: 3px;">'.number_format(($gr_prog_total/$gr_prog_target_total)*100, 2).'</td></tr>';
		$gr_pr_target = $gr_pr_target + $gr_prog_target_total;
	}
	else
		$data .= '<td class="cl4" style="padding: 3px;">--</td></tr>';
}
$item_c = $capital_exp*0.02;
$data .= '<tr><td class="cl1"><b>(c) Physical Contingency (@ 2% of Capital Cost)</b></td><td class="cl3" style="padding: 3px;">'.number_format($item_c/100000, 2).'</td><td colspan="11"></td></tr>';
$item_d = $capital_exp*0.05;
$data .= '<tr><td class="cl1"><b>(c) Price Contingency (@ 5% of Capital Cost)</b></td><td class="cl3" style="padding: 3px;">'.number_format($item_d/100000, 2).'</td><td colspan="11"></td></tr>';
$grand_total = $recurrence_exp + $capital_exp + $item_c + $item_d;
$data .= '<tr><td class="cl1"><b>Grand Total (a+ b + c + d)</b></td>';

if ($grand_total != 0) 
	$data .= '<td class="cl3" style="padding: 3px;">'.number_format($grand_total/100000, 2).'</td>';
else
	$data .= '<td class="cl3" style="padding: 3px;">--</td>';

if ($gr_ach_gob_total != 0)
	$data .= '<td class="cl2" style="padding: 3px;">'.number_format($gr_ach_gob_total/100000,2).'</td>';
else
	$data .= '<td class="cl2" style="padding: 3px;">--</td>';

if ($gr_ach_pa_total != 0)
	$data .= '<td class="cl2" style="padding: 3px;">'.number_format($gr_ach_pa_total/100000,2).'</td>';
else
	$data .= '<td class="cl2" style="padding: 3px;">--</td>';

if ($gr_ach_total != 0)
	$data .= '<td class="cl2" style="padding: 3px;">'.number_format($gr_ach_total/100000,2).'</td>';
else
	$data .= '<td class="cl2" style="padding: 3px;">--</td>';
if ($gr_ach_target != 0)
	$data .= '<td class="cl4" style="padding: 3px;">'.number_format(($gr_ach_total/$gr_ach_target)*100,2).'</td>';
else
	$data .= '<td class="cl4" style="padding: 3px;">--</td>';

if ($gr_target_gob_total != 0)
	$data .= '<td class="cl2" style="padding: 3px;">'.number_format($gr_target_gob_total/100000,2).'</td>';
else
	$data .= '<td class="cl2" style="padding: 3px;">--</td>';

if ($gr_target_pa_total != 0)
	$data .= '<td class="cl2" style="padding: 3px;">'.number_format($gr_target_pa_total/100000,2).'</td>';
else
	$data .= '<td class="cl2" style="padding: 3px;">--</td>';

if ($gr_target_total != 0)
	$data .= '<td class="cl2" style="padding: 3px;">'.number_format($gr_target_total/100000,2).'</td>';
else
	$data .= '<td class="cl2" style="padding: 3px;">--</td>';

if ($gr_pr_gob_total != 0)
	$data .= '<td class="cl2" style="padding: 3px;">'.number_format($gr_pr_gob_total/100000,2).'</td>';
else
	$data .= '<td class="cl2" style="padding: 3px;">--</td>';

if ($gr_pr_pa_total != 0)
	$data .= '<td class="cl2" style="padding: 3px;">'.number_format($gr_pr_pa_total/100000,2).'</td>';
else
	$data .= '<td class="cl2" style="padding: 3px;">--</td>';
if ($gr_pr_total != 0)
	$data .= '<td class="cl2" style="padding: 3px;">'.number_format($gr_pr_total/100000,2).'</td>';
else
	$data .= '<td class="cl2" style="padding: 3px;">--</td>';
if ($gr_pr_target != 0)
	$data .= '<td class="cl4" style="padding: 3px;">'.number_format(($gr_pr_total/$gr_pr_target)*100,2).'</td></tr>';
else
	$data .= '<td class="cl2" style="padding: 3px;">--</td></tr>';
 
$header = '<!--mpdf
<htmlpageheader name="letterheader">
	<!--	<div style="font-size: 10pt; text-align: center; padding-top: 1mm; font-family: nikosh; ">
		<h3 style="margin-bottom: 2px;">==== RPA Ledger ====</h3>
		<h5 style="margin-top: 2px; border-bottom: 1px solid #000000;"><b>Reporting Period:  &nbsp;&nbsp;</b></h5> -->
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
	width: 23%;
	text-align: Left;
}
.cl2 {
	width: 8%;
	text-align: right;
}
.cl3 {
	width: 9%;
	text-align: right;
}

.cl5 {
	width: 15%;
	text-align: right;
}

.cl4 {
	width: 9%;
	text-align: right;
}

table, th, td {
  border: 1px solid #d9d0f7;
  border-collapse: collapse;
  width: 100%;
  padding: 5px;
  font-size: 9px;
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
    <p style="text-align: left; padding-top: 10px; padding-bottom: -15px; font-size: 10px;"><i><b>8. Target and Achievement of the main Components of the Project</b></i></p>
	<p style="text-align: right; margin-bottom: 2px; font-size: 10px;"><i>[Amount in Lakh (BDT)]</i></p>
	  <table>
		<thead>
			<tr>
				<th class="cl1" style="background-color: #f3e3fa;" rowspan="2"><b>Name of Components</b></th>
				<th class="cl3" style="background-color: #f3e3fa; text-align: center;" rowspan="2"><b>Cost as per DPP</b></th>
				<th class="cl3" style="background-color: #f3e3fa; text-align: center;" colspan="4"><b>Achievement up to June, 2022 </b></th>
				<th class="cl3" style="background-color: #f3e3fa; text-align: center;" colspan="3"><b>Target of as per RADP (FY: 2022-23)</b></th>
				<th class="cl3" style="background-color: #f3e3fa; text-align: center;" colspan="4"><b>Progress up to June, 2023 </b></th>
			</tr>
			<tr>
			  <th class="cl2" style="background-color: #f3e3fa;"><b>GoB</b></th>
			  <th class="cl2" style="background-color: #f3e3fa;"><b>RPA</b></th>
			  <th class="cl2" style="background-color: #f3e3fa;"><b>Total</b></th>
			  <th class="cl4" style="background-color: #f3e3fa; text-align: center;"><b>Physical (%)</b></th>
			  <th class="cl2" style="background-color: #f3e3fa;"><b>GoB</b></th>
			  <th class="cl2" style="background-color: #f3e3fa;"><b>RPA</b></th>
			  <th class="cl2" style="background-color: #f3e3fa;"><b>Total</b></th>
			  <th class="cl2" style="background-color: #f3e3fa;"><b>GoB</b></th>
			  <th class="cl2" style="background-color: #f3e3fa;"><b>RPA</b></th>
			  <th class="cl2" style="background-color: #f3e3fa;"><b>Total</b></th>
			  <th class="cl4" style="background-color: #f3e3fa; text-align: center;"><b>Physical (%)</b></th>
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

    