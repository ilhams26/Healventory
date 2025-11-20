<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'manager') {
    header("Location: login.php");
    exit;
}

$total_obat = 940;
$obat_masuk = 50;
$obat_keluar = 45;
$obat_menipis = 3;
include '../includes/header.php';
?>

<div class="container">
    <aside class="sidebar">
        <h2 class="logo">Healventory</h2>
        <ul class="menu">
            <li class="active"><i class="bi bi-house-fill"></i> Dashboard</li>
            <li><i class="bi bi-file-earmark-text"></i> Laporan</li>
            <li><i class="bi bi-activity"></i> Monitoring</li>
            <li id="btnLogout"><i class="bi bi-box-arrow-left"></i> Logout</li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="header">
            <span class="role">Manajer</span>
            <i class="bi bi-person-circle profile-icon"></i>
        </header>
        <section class="cards">
            <div class="card"><i class="bi bi-capsule"></i>
                <p>Jumlah Obat</p>
                <h3><?= $total_obat ?></h3>
            </div>
            <div class="card"><i class="bi bi-check-circle"></i>
                <p>Obat Masuk</p>
                <h3><?= $obat_masuk ?></h3>
            </div>
            <div class="card"><i class="bi bi-arrow-up"></i>
                <p>Obat Keluar</p>
                <h3><?= $obat_keluar ?></h3>
            </div>
            <div class="card"><i class="bi bi-exclamation-triangle"></i>
                <p>Obat Menipis</p>
                <h3><?= $obat_menipis ?></h3>
            </div>
        </section>

        <section class="dashboard-content">
            <!-- <canvas id="stokChart"></canvas> -->
            <!-- Chart Besar -->
            <div class="chart">
                <h4></h4>
                <img src="../assets/img/grafik.png" alt="Grafik Transaksi">
            </div>
            <!-- Panel Kanan -->
            <div></div>
            <div class="notif">
                <h4>Notifikasi</h4>
                <ul>
                    <li>Paracetamol (20)</li>
                    <li>Amoxicillin (2 bulan)</li>
                    <li>Vitamin C (25)</li>
                </ul>
            </div>
        </section>

        <section class="table-section">
            <h4>Transaksi Terakhir</h4>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Obat</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2024-04-12</td>
                        <td>Paracetamol</td>
                        <td>Masuk</td>
                        <td>50</td>
                    </tr>
                    <tr>
                        <td>2024-04-12</td>
                        <td>Paracetamol</td>
                        <td>Keluar</td>
                        <td>50</td>
                    </tr>
                </tbody>
            </table>
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