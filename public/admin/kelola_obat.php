<?php 
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include '../../config/database.php';
include '../includes/header.php';

$obat = $pdo->query("SELECT * FROM obat ORDER BY id DESC");
?>

<div class="container">
    <aside class="sidebar">
        <h2 class="logo">Healventory</h2>
        <ul class="menu">
            <li id="menuDashboard"><i class="bi bi-house-fill"></i> Dashboard</li>
            <li class="active" id="menuObat"><i class="bi bi-capsule"></i> Kelola Obat</li>
            <li><i class="bi bi-person"></i> Kelola User</li>
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

        <section class="table-section">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                <h3>Kelola Obat</h3>
                <button id="btnTambah" class="btn-primary">+ Tambah Obat</button>
            </div>

            <table id="tabelObat">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Obat</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Stok Minimum</th>
                        <th>Kadaluarsa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $obat->fetch()): ?>
                        <tr data-id="<?= $row['id'] ?>">
                            <td><?= htmlspecialchars($row['kode_obat']) ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['kategori']) ?></td>
                            <td><?= htmlspecialchars($row['stok_awal']) ?></td>
                            <td><?= htmlspecialchars($row['stok_minimum']) ?></td>
                            <td><?= htmlspecialchars($row['tgl_kadaluarsa']) ?></td>
                            <td>
                                <button class="btn-outline btn-edit">✏️</button>
                                <button class="btn-primary btn-delete">🗑️</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>


<!-- ========================= -->
<!--    MODALS DITARUH DI LUAR -->
<!-- ========================= -->

<!-- Modal Form -->
<div class="modal" id="modalForm">
    <div class="modal-box">
        <h3 id="modalTitle">Tambah Obat</h3>
        <form id="formObat">
            <input type="hidden" name="id" id="id">

            <label>Kode Obat</label>
            <input type="text" name="kode_obat" id="kode_obat" required placeholder="Contoh: OB001">

            <label>Nama Obat</label>
            <input type="text" name="nama" id="nama" required>

            <label>Kategori</label>
            <input type="text" name="kategori" id="kategori">

            <label>Stok Awal</label>
            <input type="number" name="stok_awal" id="stok_awal" required>

            <label>Stok Minimum</label>
            <input type="number" name="stok_minimum" id="stok_minimum" required>

            <label>Tanggal Kadaluarsa</label>
            <input type="date" name="tgl_kadaluarsa" id="tgl_kadaluarsa" required>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Simpan</button>
                <button type="button" class="btn-outline" id="btnBatal">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal" id="modalHapus">
    <div class="modal-box">
        <p>Yakin ingin menghapus obat ini?</p>
        <div class="form-actions">
            <button id="confirmHapus" class="btn-outline">Ya</button>
            <button id="cancelHapus" class="btn-primary">Batal</button>
        </div>
    </div>
</div>

<script src="../assets/js/kelola_obat.js"></script>
<?php include '../includes/footer.php'; ?>
