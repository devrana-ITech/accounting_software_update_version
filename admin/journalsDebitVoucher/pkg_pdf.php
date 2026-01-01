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

if (!empty($_POST['package_id']))
	$package_id = $_POST['package_id'];


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
//$pdf = new mPDF('', 'A4-L',8,'');

//inner join `account_list` on account_list.id = journal_items.account_id
//$dat .= '<h3><b>Advertisements details from ' .date("M d, Y",strtotime($start)).' to '.date("M d, Y",strtotime($end)).'</b></h3>';
$numrows = 0;
$acc_name = "";
$hcount = 0;
$data = "";

if (empty($_POST['package_id'])) {  
	$pack_list = $conn->query("SELECT id, pack_name, pack_details from pkg");
	while($p_list = $pack_list->fetch_assoc()){
		$pack_name = $p_list['pack_name']. ': '.$p_list['pack_details'];
		$package_id = $p_list['id']; 
		
		$journals = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE pkg_number = '$package_id' and journal_date >= '$start' and journal_date <= '$end' UNION ALL select '$start', '', '', 'Opening Balance B/D' as txtNarrative, '', '', sum(CASE WHEN group_id = 1 THEN jt.amount END) as amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE pkg_number = '$package_id' and journal_date < '$start' group by 'Opening Balance B/D' order by journal_date desc;");
		
		
		$numrows = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE pkg_number = '$package_id' and journal_date >= '$start' and journal_date <= '$end' UNION ALL select '$start', '', '', 'Opening Balance B/D' as txtNarrative, '', '', sum(CASE WHEN group_id = 1 THEN jt.amount END) as amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE pkg_number = '$package_id' and journal_date < '$start' group by 'Opening Balance B/D' order by journal_date asc;")->num_rows;
		

	  if ($numrows > 0) {	
		$balance = 0;
				
				//'.$row["name"].', '.$row["description"].'
				
				while($row = $journals->fetch_assoc()){
				 if (($row["amount"] != 0) && ($row['group_id'] == 1)) {
				  $data .= '<tr><td class="cl4" style="padding: 3px;">'.date("d-m-Y", strtotime($row["journal_date"])).'</td>';
				   if ($row['payee_name'] == null)
						$data .= '<td class="cl1">'.$row["description"].'</td>';
				   else
					   $data .= '<td class="cl1">'.$row["description"].', '.$row["payee_name"].'</td>';
				   
				   
				   $data .= '<td class="cl4">'.$row["voucher_number"].'</td>';
						
							$data .= '<td class="cl2">'.format_num($row["amount"]).'</td>';
							$balance = $balance + $row['amount'];
					$data .= '<td class="cl5">'.format_num($balance).'</td></tr>';
					}
				}
		

//trigger_error($data, E_USER_NOTICE);



$header = '<!--mpdf
<htmlpageheader name="letterheader">
		<div style="font-size: 10pt; text-align: center; padding-top: 1mm; font-family: nikosh; ">
		<h3 style="margin-bottom: 2px;">==== Package-wise Expenditure ====</h3>
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
    <p style="text-align: left; padding-top: 10px; padding-bottom: -15px; font-size: 11px;"><i><b>'.$pack_name.'</b></i></p>
	<p style="text-align: right; margin-bottom: 2px; font-size: 11px;"><i>Amount in BDT</i></p>
	  <table>
		<thead>
		<tr>
		  <th class="cl4" style="background-color: #f3e3fa;"><b>Date</b></th>
		  <th class="cl1" style="background-color: #f3e3fa;"><b>Particulars</b></th>
		  <th class="cl4" style="background-color: #f3e3fa;"><b>V. No</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>Debit Amt</b></th>
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
$package_id = $_POST['package_id'];

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
//$pdf = new mPDF('', 'A4-L',8,'');

//inner join `account_list` on account_list.id = journal_items.account_id
//$dat .= '<h3><b>Advertisements details from ' .date("M d, Y",strtotime($start)).' to '.date("M d, Y",strtotime($end)).'</b></h3>';
$numrows = 0;

$pack_name = "";
$acc_name_q = $conn->query("SELECT * from pkg where id = '$package_id '");
while($r = $acc_name_q->fetch_assoc()){
	$pack_name = $r['pack_name']. ': '.$r['pack_details'];
}


$data = "";

$journals = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE pkg_number = '$package_id' and journal_date >= '$start' and journal_date <= '$end' UNION ALL select '$start', '', '', 'Opening Balance B/D' as txtNarrative, '', '', sum(CASE WHEN group_id = 1 THEN jt.amount END) as amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE pkg_number = '$package_id' and journal_date < '$start' group by 'Opening Balance B/D' order by journal_date desc;");
		
		
$numrows = $conn->query("SELECT je.journal_date, jt.journal_id, jt.group_id, je.description, al.name, je.voucher_number, jt.amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE pkg_number = '$package_id' and journal_date >= '$start' and journal_date <= '$end' UNION ALL select '$start', '', '', 'Opening Balance B/D' as txtNarrative, '', '', sum(CASE WHEN group_id = 1 THEN jt.amount END) as amount, jt.account_id, jt.group_id, je.payee_name FROM `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join account_list al on al.id = jt.account_id WHERE pkg_number = '$package_id' and journal_date < '$start' group by 'Opening Balance B/D' order by journal_date asc;")->num_rows;		

$balance = 0;
while($row = $journals->fetch_assoc()){
	 if (($row["amount"] != 0) && ($row['group_id'] == 1)) {
	  $data .= '<tr><td class="cl4" style="padding: 3px;">'.date("d-m-Y", strtotime($row["journal_date"])).'</td>';
	   if ($row['payee_name'] == null)
			$data .= '<td class="cl1">'.$row["description"].'</td>';
	   else
		   $data .= '<td class="cl1">'.$row["description"].', '.$row["payee_name"].'</td>';
	   
	   
	   $data .= '<td class="cl4">'.$row["voucher_number"].'</td>';
			
				$data .= '<td class="cl2">'.format_num($row["amount"]).'</td>';
				$balance = $balance + $row['amount'];
		$data .= '<td class="cl5">'.format_num($balance).'</td></tr>';
		}
	}				
				



$header = '<!--mpdf
<htmlpageheader name="letterheader">
		<div style="font-size: 10pt; text-align: center; padding-top: 1mm; font-family: nikosh; ">
		<h3 style="margin-bottom: 2px;">==== Package-wise Expenditure ====</h3>
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
    <p style="text-align: left; padding-top: 10px; padding-bottom: -15px; font-size: 11px;"><i><b>'.$pack_name.'</b></i></p>
	<p style="text-align: right; margin-bottom: 2px; font-size: 11px;"><i>Amount in BDT</i></p>
	  <table>
		<thead>
		<tr>
		  <th class="cl4" style="background-color: #f3e3fa;"><b>Date</b></th>
		  <th class="cl1" style="background-color: #f3e3fa;"><b>Particulars</b></th>
		  <th class="cl4" style="background-color: #f3e3fa;"><b>V. No</b></th>
		  <th class="cl2" style="background-color: #f3e3fa;"><b>Debit Amt</b></th>
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

    