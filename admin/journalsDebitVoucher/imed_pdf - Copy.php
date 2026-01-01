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
				'margin_top' => '22',
				'margin_bottom' => '12',
				'margin_footer' => '8',
				'deafult_font_size' => 8,
				'default_font' => 'nikosh'
			]);
			

$journals = $conn->query("SELECT je.dli_type, al.name, sum(pa.gov_amount) as target_gob_amount, sum(pa.aid_amount) as target_pa_amount, sum(pa.gov_amount+pa.aid_amount) as target_total_amount, sum(jt.amount) as exp_amount, al.id as id FROM `journal_items` jt, `journal_entries` je, account_list al, pr_aid pa WHERE je.id = jt.journal_id and al.id = jt.account_id and pa.account_id = jt.account_id and pa.year_id = je.year_id and journal_date <= '2023-06-30' group by jt.account_id union all select
'', a.name, p.gov_amount, p.aid_amount, p.gov_amount+p.aid_amount as t_total, '', a.id as id from account_list a, pr_aid p where a.id = p.account_id and p.year_id = 4 union all select je.dli_type, al.name, sum(pa.gov_amount) as target_gob_amount, sum(pa.aid_amount) as target_pa_amount,  sum(pa.gov_amount+pa.aid_amount) as target_total_amount, sum(jt.amount) as exp_amount, al.id as id FROM `journal_items` jt, `journal_entries` je, account_list al, pr_aid pa WHERE je.id = jt.journal_id and al.id = jt.account_id and pa.account_id = jt.account_id and pa.year_id = je.year_id and journal_date > '2023-06-30' and journal_date <= '2024-06-30' group by jt.account_id 
order by id asc;");
/*
SELECT je.dli_type, al.name, sum(pa.gov_amount) as target_gob_amount, sum(pa.aid_amount) as target_pa_amount, sum(pa.gov_amount+pa.aid_amount) as target_total_amount, sum(jt.amount) as exp_amount, al.id as id FROM `journal_items` jt, `journal_entries` je, account_list al, pr_aid pa WHERE je.id = jt.journal_id and al.id = jt.account_id and pa.account_id = jt.account_id and pa.year_id = je.year_id and journal_date <= '2023-06-30' group by jt.account_id 
union all select
'', a.name, p.gov_amount, p.aid_amount, p.gov_amount+p.aid_amount as t_total, '', a.id as id from account_list a, pr_aid p where a.id = p.account_id and p.year_id = 4
union all select 
je.dli_type, al.name, pa.gov_amount as target_gob_amount, pa.aid_amount as target_pa_amount, pa.gov_amount+pa.aid_amount as target_total_amount, sum(jt.amount) as exp_amount, al.id as id FROM pr_aid pa inner join `journal_entries` je on pa.year_id = je.year_id inner join account_list al on al.id = pa.account_id left join `journal_items` jt on je.id = jt.journal_id and al.id = jt.account_id and pa.account_id = jt.account_id and journal_date > '2023-06-30' and journal_date <= '2024-06-30' group by jt.account_id order by id asc;
*/
$counter = 1;
while($row = $journals->fetch_assoc()){
	$a_total = 0;
	if ($counter > 3)
		$counter = 1;
	if ($counter == 1) {
		$data .= '<tr><td class="cl1" style="padding: 3px;">'.$row['name'].'</td>';
		if ($row['dli_type'] == 'GoB') {
			$data .= '<td class="cl2" style="padding: 3px;">'.format_num($row['exp_amount']/100000).'</td>';
			$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			$a_total = $a_total + $row['exp_amount'];
		}else
		{
			$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			$data .= '<td class="cl2" style="padding: 3px;">'.format_num($row['exp_amount']/100000).'</td>';
			$a_total = $a_total + $row['exp_amount'];
		}
		$data .= '<td class="cl2" style="padding: 3px;">'.format_num($a_total/100000).'</td>';
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format(($a_total/$row['target_total_amount'])*100, 2).'%</td>';
	} else if ($counter == 2) {
		$data .= '<td class="cl2" style="padding: 3px;">'.format_num($row['target_gob_amount']/100000).'</td>';
		$data .= '<td class="cl2" style="padding: 3px;">'.format_num($row['target_pa_amount']/100000).'</td>';
		$data .= '<td class="cl2" style="padding: 3px;">'.format_num($row['target_total_amount']/100000).'</td>';
	} else  if ($counter == 3) {
		if ($row['dli_type'] == 'GoB') {
			$data .= '<td class="cl2" style="padding: 3px;">'.format_num($row['exp_amount']/100000).'</td>';
			$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			$a_total = $a_total + $row['exp_amount'];
		}else
		{
			$data .= '<td class="cl2" style="padding: 3px;">--</td>';
			$data .= '<td class="cl2" style="padding: 3px;">'.format_num($row['exp_amount']/100000).'</td>';
			$a_total = $a_total + $row['exp_amount'];
		}
		$data .= '<td class="cl2" style="padding: 3px;">'.format_num($a_total/100000).'</td>';
		$data .= '<td class="cl2" style="padding: 3px;">'.number_format(($a_total/$row['target_total_amount'])*100, 2).'%</td>';
	}
	$counter = $counter + 1;
}	

$header = '<!--mpdf
<htmlpageheader name="letterheader">
		<div style="font-size: 10pt; text-align: center; padding-top: 1mm; font-family: nikosh; ">
		<h3 style="margin-bottom: 2px;">==== RPA Ledger ====</h3>
		<h5 style="margin-top: 2px; border-bottom: 1px solid #000000;"><b>Reporting Period:  &nbsp;&nbsp;</b></h5>
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
	width: 34%;
	text-align: Left;
}
.cl2 {
	width: 6%;
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
	  <table>
		<thead>
			<tr>
			  <th class="cl1" style="background-color: #f3e3fa;"><b>Name of Components</b></th>
			  <th class="cl2" style="background-color: #f3e3fa;"><b>GoB</b></th>
			  <th class="cl2" style="background-color: #f3e3fa;"><b>RPA</b></th>
			  <th class="cl2" style="background-color: #f3e3fa;"><b>Total</b></th>
			  <th class="cl2" style="background-color: #f3e3fa;"><b>Physical (%)</b></th>
			  <th class="cl2" style="background-color: #f3e3fa;"><b>GoB</b></th>
			  <th class="cl2" style="background-color: #f3e3fa;"><b>RPA</b></th>
			  <th class="cl2" style="background-color: #f3e3fa;"><b>Total</b></th>
			  <th class="cl2" style="background-color: #f3e3fa;"><b>GoB</b></th>
			  <th class="cl2" style="background-color: #f3e3fa;"><b>RPA</b></th>
			  <th class="cl2" style="background-color: #f3e3fa;"><b>Total</b></th>
			  <th class="cl2" style="background-color: #f3e3fa;"><b>Physical (%)</b></th>
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

    