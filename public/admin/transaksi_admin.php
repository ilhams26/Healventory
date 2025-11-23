<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require '../../config/database.php';

// Filter tanggal
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

$query = "SELECT t.id, o.nama AS nama_obat, t.jenis, t.jumlah, t.keterangan, t.tgl_transaksi
          FROM transaksi t
          JOIN obat o ON t.id_obat = o.id
          WHERE 1=1";

$params = [];

if ($from && $to) {
    $query .= " AND DATE(t.tgl_transaksi) BETWEEN ? AND ?";
    $params = [$from, $to];
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$transaksi = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="container">
    <aside class="sidebar">
        <h2 class="logo">Healventory</h2>
        <ul class="menu">
            <li onclick="location.href='dashboard.php'"><i class="bi bi-house-fill"></i> Dashboard</li>
            <li onclick="location.href='kelola_obat.php'"><i class="bi bi-capsule"></i> Kelola Obat</li>
            <li onclick="location.href='kelola_user.php'"><i class="bi bi-person"></i> Kelola User</li>
            <li class="active" onclick="location.href='transaksi_admin.php'"><i class="bi bi-arrow-left-right"></i> Transaksi</li>
            <li onclick="location.href='laporan.php'"><i class="bi bi-file-earmark-text"></i> Laporan</li>
            <li onclick="location.href='monitoring.php'"><i class="bi bi-activity"></i> Monitoring</li>
            <li id="btnLogout"><i class="bi bi-box-arrow-left"></i> Logout</li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="header">
            <span class="role">Super Admin</span>
            <i class="bi bi-person-circle profile-icon"></i>
        </header>

        <h2>Transaksi</h2>

        <form method="GET" class="filter-box">
            <label>Dari:</label>
            <input type="date" name="from" value="<?= htmlspecialchars($from) ?>">

            <label>Sampai:</label>
            <input type="date" name="to" value="<?= htmlspecialchars($to) ?>">

            <button type="submit" class="btn-primary">Filter</button>
        </form>

        <section class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Obat</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transaksi as $row): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['nama_obat'] ?></td>
                            <td><?= ucfirst($row['jenis']) ?></td>
                            <td><?= $row['jumlah'] ?></td>
                            <td><?= $row['keterangan'] ?></td>
                            <td><?= $row['tgl_transaksi'] ?></td>
                        </tr>
                    <?php endforeach; ?>
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