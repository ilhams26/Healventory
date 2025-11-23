<?php
require '../../config/database.php';
require '../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\FrameDecorator\AbstractFrameDecorator;
use Dompdf\FrameDecorator\FrameDecorator;
use Dompdf\FrameDecorator\TextFrameDecorator;

ob_start();

// Ambil data dengan JOIN
$laporan = $pdo->query("
    SELECT 
        transaksi.*, 
        obat.nama AS nama_obat,
        obat.id
    FROM transaksi
    JOIN obat ON obat.id = transaksi.id
    ORDER BY transaksi.tgl_transaksi DESC
");

// siapkan HTML
?>
<h2 style="text-align:center;">Laporan Transaksi</h2>
<table width="100%" border="1" cellspacing="0" cellpadding="5">
<tr>
    <th>No</th>
    <th>Kode Obat</th>
    <th>Nama Obat</th>
    <th>Jumlah</th>
    <th>Tanggal</th>
</tr>

<?php 
$no = 1;
while ($row = $laporan->fetch(PDO::FETCH_ASSOC)) : ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $row['id'] ?></td>
    <td><?= $row['nama_obat'] ?></td>
    <td><?= $row['jumlah'] ?></td>
    <td><?= $row['tgl_transaksi'] ?></td>
</tr>
<?php endwhile; ?>

</table>
<?php

// ambil HTML
$html = ob_get_clean();

// Buat PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Tampilkan PDF
$dompdf->stream("laporan_transaksi.pdf", ["Attachment" => false]);
?>
