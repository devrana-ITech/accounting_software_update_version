<?php
require_once('./../../config.php');
require('../../vendor/autoload.php');

$year_id = $_POST['year_id'] ?? '';
$qtr_id  = $_POST['qtr_id'] ?? '';
$d_type  = $_POST['d_type'] ?? 'Non-DLI';

if(empty($year_id) || empty($qtr_id)){
    die('Invalid Request');
}

$monthMap = [
    1  => 6,   // JUNE
    2  => 7,   // JULY
    3  => 8,   // AUGUST
    4  => 9,   // SEPTEMBER
    5  => 10,  // OCTOBER
    6  => 11,  // NOVEMBER
    7  => 12,  // DECEMBER
    8  => 1,   // JANUARY
    9  => 2,   // FEBRUARY
    10 => 3,   // MARCH
    11 => 4,   // APRIL
    12 => 5    // MAY
];

$month = $monthMap[$qtr_id];

if ($year_id == 2){
    $fy_start = "2021-07-01";
    $fy_end   = "2022-06-30";
}elseif ($year_id == 3){
    $fy_start = "2022-07-01";
    $fy_end   = "2023-06-30";
}elseif ($year_id == 4){
    $fy_start = "2023-07-01";
    $fy_end   = "2024-06-30";
}elseif ($year_id == 5){
    $fy_start = "2024-07-01";
    $fy_end   = "2025-06-30";
}elseif ($year_id == 6){
    $fy_start = "2025-07-01";
    $fy_end   = "2026-06-30";
}else{
    die('Invalid Fiscal Year');
}


$month_start = date("Y-m-01", strtotime("$fy_start +".($month)." month"));
$month_end   = date("Y-m-t", strtotime($month_start));
$month_title = date("F Y", strtotime($month_start));


$sql = $conn->query("
    SELECT 
        a.acc_code,
        a.name,
        SUM(jt.amount) AS debit_amount
    FROM journal_entries je
    INNER JOIN journal_items jt ON jt.journal_id = je.id
    INNER JOIN account_list a ON a.id = jt.account_id
    WHERE je.year_id = '$year_id'
      AND je.dli_type = '$d_type'
      AND jt.group_id = 1
      AND je.journal_date BETWEEN '$month_start' AND '$month_end'
    GROUP BY a.id
    ORDER BY a.acc_code ASC
");


$data  = '';
$sn    = 0;
$total = 0;

while($row = $sql->fetch_assoc()){
    $sn++;
    $total += $row['debit_amount'];

    $data .= '
    <tr>
        <td style="text-align:center">'.$sn.'</td>
        <td style="text-align:center">'.$row['acc_code'].'</td>
        <td style="text-align:left">'.$row['name'].'</td>
        <td style="text-align:right">'.number_format($row['debit_amount'],2).'</td>
    </tr>';
}

$data .= '
<tr>
    <td colspan="3" style="text-align:right"><b>Total</b></td>
    <td style="text-align:right"><b>'.number_format($total,2).'</b></td>
</tr>';


$pdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'margin_top' => 28,
    'margin_bottom' => 15,
    'default_font' => 'nikosh'
]);


$header = '
<h3 style="text-align:center;margin-bottom:4px;">
    Journal Debit Monthly Report
</h3>
<h5 style="text-align:center;margin-top:0;">
    '.$d_type.' | '.$month_title.'
</h5>
<hr>
';


$html = '
<html>
<head>
<style>
body{font-family: nikosh;}
table, th, td {
    border: 1px solid #d9d0f7;
    border-collapse: collapse;
    padding: 6px;
    font-size: 12px;
}
th{
    background:#f3e3fa;
}
</style>
</head>
<body>

<p style="text-align:right;font-size:11px;">
    <i>Amount in BDT</i>
</p>

<table width="100%">
<thead>
<tr>
    <th width="8%">SN</th>
    <th width="20%">Account Code</th>
    <th width="42%">Account Name</th>
    <th width="30%" style="text-align:right">Debit Amount</th>
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
$pdf->Output('journal_debit_monthly_report.pdf','I');
exit;
?>
