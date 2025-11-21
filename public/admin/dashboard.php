<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include '../../config/database.php';
include '../includes/header.php';

// Ambil data statistik
$total_obat = $pdo->query("SELECT COUNT(*) FROM obat")->fetchColumn();
$obat_masuk = $pdo->query("SELECT COUNT(*) FROM transaksi WHERE jenis='masuk'")->fetchColumn();
$obat_keluar = $pdo->query("SELECT COUNT(*) FROM transaksi WHERE jenis='keluar'")->fetchColumn();
$obat_menipis = $pdo->query("SELECT COUNT(*) FROM obat WHERE stok_awal < stok_minimum")->fetchColumn();

// Ambil notifikasi terbaru
$notif = $pdo->query("SELECT pesan FROM notifikasi ORDER BY tanggal DESC LIMIT 5");
?>

<div class="container">
    <aside class="sidebar">
        <h2 class="logo">Healventory</h2>
        <ul class="menu">
            <li class="active" id="menuDashboard"><i class="bi bi-house-fill"></i> Dashboard</li>
            <li id="menuKelolaObat"><i class="bi bi-capsule"></i> Kelola Obat</li>
            <li id="menuKelolaUser"><i class="bi bi-person"></i> Kelola User</li>
            <li><i class="bi bi-arrow-left-right"></i> Transaksi</li>
            <li><i class="bi bi-file-earmark-text"></i> Laporan</li>
            <li><i class="bi bi-activity"></i> Monitoring</li>
            <li id="btnLogout"><i class="bi bi-box-arrow-left"></i> Logout</li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="header">
            <span class="role">Super Admin</span>
            <i class="bi bi-person-circle profile-icon"></i>
        </header>

        <!-- Kartu Statistik -->
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

        <!-- Grafik & Notifikasi -->
        <section class="dashboard-content">
            <div class="chart">
                <h4>Grafik Stok</h4>
                <img src="../assets/img/grafik.png" alt="Grafik Transaksi">
            </div>

            <div class="notif">
                <h4>Notifikasi Terbaru</h4>
                <ul>
                    <?php while($n = $notif->fetch()): ?>
                        <li><?= htmlspecialchars($n['pesan']) ?></li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </section>

        <!-- Transaksi Terakhir -->
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
                    <?php
                    $transaksi = $pdo->query("
                        SELECT t.tgl_transaksi, o.nama AS nama_obat, t.jenis, t.jumlah
                        FROM transaksi t
                        JOIN obat o ON o.id = t.id_obat
                        ORDER BY t.tgl_transaksi DESC LIMIT 5
                    ");
                    while ($row = $transaksi->fetch()):
                    ?>
                        <tr>
                            <td><?= htmlspecialchars(date('Y-m-d', strtotime($row['tgl_transaksi']))) ?></td>
                            <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                            <td><?= htmlspecialchars(ucfirst($row['jenis'])) ?></td>
                            <td><?= htmlspecialchars($row['jumlah']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>

<!-- Modal Logout -->
<div class="logout-modal" id="logoutModal">
    <div class="logout-box">
        <p>Yakin Ingin Keluar?</p>
        <div class="logout-actions">
            <button id="confirmLogout" class="btn-outline">Ya</button>
            <button id="cancelLogout" class="btn-primary">Batal</button>
        </div>
    </div>
</div>

<!-- Popup Notifikasi -->
<div id="popupContainer"></div>

<?php include '../includes/footer.php'; ?>

<script>
// --- Navigasi Sidebar ---
document.getElementById("menuKelolaObat").addEventListener("click", () => {
    window.location.href = "kelola_obat.php";
});

// --- Logout Modal ---
const btnLogout = document.getElementById("btnLogout");
const modal = document.getElementById("logoutModal");
const confirmBtn = document.getElementById("confirmLogout");
const cancelBtn = document.getElementById("cancelLogout");

btnLogout.addEventListener("click", () => modal.classList.add("active"));
cancelBtn.addEventListener("click", () => modal.classList.remove("active"));
confirmBtn.addEventListener("click", () => (window.location.href = "../logout.php"));

// --- Popup Notifikasi Otomatis ---
setInterval(() => {
fetch("get_notif.php")
    .then(res => res.json())
    .then(data => {
    if (data.new) {
        showPopupNotif(data.pesan);
    }
    });
}, 10000); // tiap 10 detik

function showPopupNotif(pesan) {
const popup = document.createElement("div");
popup.className = "popup-notif";
popup.innerHTML = `<p>${pesan}</p>`;
document.getElementById("popupContainer").appendChild(popup);

setTimeout(() => popup.classList.add("show"), 100);
setTimeout(() => {
    popup.classList.remove("show");
    setTimeout(() => popup.remove(), 500);
}, 6000);
}
</script>
