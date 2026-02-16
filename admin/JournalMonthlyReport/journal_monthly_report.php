<?php
require_once './../../config.php';
require_once './../../vendor/autoload.php';

$year_id = isset($_POST['year_id']) ? (int)$_POST['year_id'] : 0;
$qtr_id  = isset($_POST['qtr_id']) ? (int)$_POST['qtr_id'] : -1;
$d_type  = $_POST['d_type'] ?? 'Non-DLI';

if ($year_id <= 0 || $qtr_id < 0) {
    die('Invalid Request');
}


$fy_stmt = $conn->prepare("SELECT from_date, to_date FROM fy WHERE id = ?");
$fy_stmt->bind_param("i", $year_id);
$fy_stmt->execute();
$fy_result = $fy_stmt->get_result();

if ($fy_result->num_rows === 0) {
    die('Invalid Fiscal Year');
}

$fy = $fy_result->fetch_assoc();
$fy_start = $fy['from_date']; 
$fy_end   = $fy['to_date'];


$month_start = date("Y-m-01", strtotime("$fy_start +$qtr_id month"));
$month_end   = date("Y-m-t", strtotime($month_start));
$month_title = date("F Y", strtotime($month_start));


$stmt = $conn->prepare("
    SELECT 
        a.acc_code,
        a.name,
        journal_date,
        SUM(jt.amount) AS debit_amount
    FROM journal_entries je
    INNER JOIN journal_items jt ON jt.journal_id = je.id
    INNER JOIN account_list a ON a.id = jt.account_id
    WHERE je.year_id = ?
      AND je.dli_type = ?
      AND jt.group_id = 1
      AND je.journal_date BETWEEN ? AND ?
    GROUP BY a.id
    ORDER BY a.acc_code ASC
");

$stmt->bind_param("isss", $year_id, $d_type, $month_start, $month_end);
$stmt->execute();
$result = $stmt->get_result();


$data  = '';
$sn    = 0;
$total = 0;

while ($row = $result->fetch_assoc()) {
    $sn++;
    $total += $row['debit_amount'];

    $data .= "
    <tr>
        <td style='text-align:center'>{$sn}</td>
        <td style='text-align:center'>{$row['acc_code']}</td>
        <td style='text-align:left'>{$row['name']}</td>
        <td style='text-align:right'>" . number_format($row['debit_amount'], 2) . "</td>
    </tr>";
}

$data .= "
<tr>
    <td colspan='3' style='text-align:right'><b>Total</b></td>
    <td style='text-align:right'><b>" . number_format($total, 2) . "</b></td>
</tr>";


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
