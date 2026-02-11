<?php
ob_start();
require '../vendor/autoload.php';
require_once '../koneksi.php';

session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: ../login.php');
    exit();
}

$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

if (empty($start_date) || empty($end_date)) {
    die('Tanggal mulai dan akhir harus diisi!');
}

$report_title = 'Laporan pembayaran fitnes mart "Suplement dan Alat Olahraga"';

$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Company Name');
$pdf->SetTitle($report_title);
$pdf->SetSubject('Data Pembayaran');
$pdf->SetKeywords('TCPDF, PDF, laporan, pembayaran');

$pdf->SetHeaderData('', 0, $report_title, 'Rentang: ' . htmlspecialchars($start_date) . ' - ' . htmlspecialchars($end_date));
$pdf->setHeaderFont(['helvetica', '', 12]);
$pdf->SetHeaderMargin(10);
$pdf->SetTopMargin(25);

$pdf->setFooterData([0, 64, 0], [0, 64, 128]);
$pdf->setFooterFont(['helvetica', '', 8]);
$pdf->SetFooterMargin(10);

$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, $report_title, 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Tanggal: ' . htmlspecialchars($start_date) . ' - ' . htmlspecialchars($end_date), 0, 1, 'C');
$pdf->Ln(10);

$query = "
    SELECT 
        p.222247_id_pembayaran, p.222247_status_pembayaran, p.222247_tanggal_pembayaran, 
        o.222247_total_harga, o.222247_tanggal_pesanan, o.222247_jumlah AS jumlah_produk, 
        pr.222247_nama_produk, pr.222247_harga,
        u.222247_nama_lengkap
    FROM tbl_pembayaran_222247 p
    JOIN tbl_pesanan_222247 o ON p.222247_id_pesanan = o.222247_id_pesanan
    JOIN tbl_produk_222247 pr ON o.222247_id_produk = pr.222247_id_produk
    JOIN tbl_pengguna_222247 u ON o.222247_id_pengguna = u.222247_id_pengguna
    WHERE p.222247_tanggal_pembayaran BETWEEN ? AND ?
";

$stmt = $koneksi->prepare($query);
if (!$stmt) {
    die('Query gagal dipersiapkan: ' . $koneksi->error);
}

$stmt->bind_param('ss', $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

$total_harga = 0;
$total_produk = 0;

$html = '
<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; font-family: Arial, sans-serif; font-size: 12px;">
    <thead>
        <tr style="background-color: #4CAF50; color: white; text-align: center;">
            <th>Nama Lengkap</th>
            <th>Nama Produk</th>
            <th>Tanggal Pesanan</th>
            <th>Tanggal Pembayaran</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Total</th>
            <th>Status Pembayaran</th>                                 
        </tr>
    </thead>
    <tbody>';

while ($row = $result->fetch_assoc()) {
    $harga_produk = $row['222247_harga'];
    $total_harga += $row['222247_total_harga'];
    $total_produk += $row['jumlah_produk']; 
    $html .= '
        <tr style="text-align: center;">
            <td>' . htmlspecialchars($row['222247_nama_lengkap']) . '</td>
            <td>' . htmlspecialchars($row['222247_nama_produk']) . '</td>
            <td>' . htmlspecialchars($row['222247_tanggal_pesanan']) . '</td>
            <td>' . htmlspecialchars($row['222247_tanggal_pembayaran']) . '</td>
            <td>' . number_format($harga_produk, 2, ',', '.') . '</td>
            <td>' . htmlspecialchars($row['jumlah_produk']) . '</td>
            <td>' . number_format($row['222247_total_harga'], 2, ',', '.') . '</td>
            <td>' . htmlspecialchars($row['222247_status_pembayaran']) . '</td>
        </tr>';
}

$html .= '</tbody>
    <tfoot>
        <tr style="background-color: #4CAF50; color: white; text-align: center;">
            <td colspan="5" style="font-weight: bold;">GRAND TOTAL</td>
            <td colspan="3">' . number_format($total_harga, 2, ',', '.') . '</td>
        </tr>
        <tr style="background-color: #f2f2f2; text-align: center;">
            <td colspan="5" style="font-weight: bold;">TOTAL PRODUK</td>
            <td colspan="3">' . $total_produk . '</td>
        </tr>
    </tfoot>
</table>';

$pdf->writeHTML($html, true, false, true, false, '');

$stmt->close();
$koneksi->close();

$pdf->Output('Laporan_Pembayaran.pdf', 'I');
?>
