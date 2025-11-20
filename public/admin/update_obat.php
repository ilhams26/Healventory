<?php
include '../../config/database.php';

$id = $_POST['id'];
$kode = $_POST['kode_obat'];
$nama = $_POST['nama'];
$kategori = $_POST['kategori'];
$stok_awal = $_POST['stok_awal'];
$stok_minimum = $_POST['stok_minimum'];
$tgl_kadaluarsa = $_POST['tgl_kadaluarsa'];

$stmt = $pdo->prepare("UPDATE obat 
                       SET kode_obat=?, nama=?, kategori=?, stok_awal=?, stok_minimum=?, tgl_kadaluarsa=? 
                       WHERE id=?");
$stmt->execute([$kode, $nama, $kategori, $stok_awal, $stok_minimum, $tgl_kadaluarsa, $id]);

echo "success";
?>
