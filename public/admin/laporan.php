<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include '../../config/database.php';

$laporan = $pdo->query(query:"
            SELECT 
                nama, 
                stok_awal, 
                (SELECT COALESCE(SUM(jumlah),0) FROM transaksi WHERE id_obat = obat.id AND jenis='masuk') AS pemasukan, 
                (SELECT COALESCE(SUM(jumlah),0) FROM transaksi WHERE id_obat = obat.id AND jenis='keluar') AS pengeluaran,
                (stok_awal + 
                    (SELECT COALESCE(SUM(jumlah),0) FROM transaksi WHERE id_obat = obat.id AND jenis='masuk') -
                    (SELECT COALESCE(SUM(jumlah),0) FROM transaksi WHERE id_obat = obat.id AND jenis='keluar')
                ) AS stok_akhir
            FROM obat ORDER BY nama ASC
");
$data = $laporan->fetchAll(PDO::FETCH_ASSOC);

include "../includes/header.php";
?>

<div class="container-laporan">
    <aside class="sidebar">
        <h2 class="logo">Healventory</h2>
        <ul class="menu">
            <li onclick="location.href='dashboard.php'"><i class="bi bi-house-fill"></i> Dashboard</li>
            <li onclick="location.href='kelola_obat.php'"><i class="bi bi-capsule"></i> Kelola Obat</li>
            <li onclick="location.href='kelola_user.php'"><i class="bi bi-person"></i> Kelola User</li>
            <li onclick="location.href='transaksi_admin.php'"><i class="bi bi-arrow-left-right"></i> Transaksi</li>
            <li class="active" onclick="location.href='laporan.php'"><i class="bi bi-file-earmark-text"></i> Laporan</li>
            <li onclick="location.href='monitoring.php'"><i class="bi bi-activity"></i> Monitoring</li>
            <li id="btnLogout"><i class="bi bi-box-arrow-left"></i> Logout</li>
        </ul>

    </aside>

    <main class="main-content">
        <header class="header">
            <span class="role">Super Admin</span>
            <i class="bi bi-person-circle profile-icon"></i>
        </header>

        <h2>Laporan</h2>
        
        <section class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>Obat</th>
                        <th>Stok Awal</th>
                        <th>Pemasukan</th>
                        <th>Pengeluaran</th>
                        <th>Stok Akhir</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td><?= $row['nama'] ?></td>
                            <td><?= $row['stok_awal'] ?></td>
                            <td><?= $row['pemasukan'] ?></td>
                            <td><?= $row['pengeluaran'] ?></td>
                            <td><?= $row['stok_akhir'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button class="btn-print" id="btnCetakLaporan">
                    <i class="bi bi-printer"></i> 
                    Cetak Laporan
            </button>

            <script src="../assets/js/laporan.js"></script>
        </section>
    </main>
</div>

<div class="logout-modal" id="logoutModal">
    <div class="logout-box">
        <p>Yakin Ingin Keluar?</p>
        <div class="logout-actions">
            <button id="confirmLogout" class="btn-outline">Ya</button>
            <button id="cancelLogout" class="btn-primary">Batal</button>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>