<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include '../../config/database.php';

// 🧩 BAGIAN AKSI CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    // --- TAMBAH USER ---
    if ($action === 'tambah') {
        $fullname = $_POST['fullname'];
        $role = $_POST['role'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("INSERT INTO users (fullname, role, username, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$fullname, $role, $username, $password]);
        echo json_encode(['status' => 'success', 'msg' => 'Pengguna berhasil ditambahkan']);
        exit;
    }

    // --- UPDATE USER ---
    if ($action === 'update') {
        $id = $_POST['id'];
        $fullname = $_POST['fullname'];
        $role = $_POST['role'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (!empty($password)) {
            $password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE users SET fullname=?, role=?, username=?, password=? WHERE id=?");
            $stmt->execute([$fullname, $role, $username, $password, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET fullname=?, role=?, username=? WHERE id=?");
            $stmt->execute([$fullname, $role, $username, $id]);
        }
        echo json_encode(['status' => 'success', 'msg' => 'Pengguna berhasil diperbarui']);
        exit;
    }

    // --- HAPUS USER ---
    if ($action === 'hapus') {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
        $stmt->execute([$id]);
        echo json_encode(['status' => 'success', 'msg' => 'Pengguna dihapus']);
        exit;
    }
}

// 🧩 BAGIAN TAMPILAN
include '../includes/header.php';
$users = $pdo->query("SELECT * FROM users ORDER BY id DESC");
?>

<div class="container">
    <aside class="sidebar">
        <h2 class="logo">Healventory</h2>
        <ul class="menu">
            <li><i class="bi bi-house-fill"></i> Dashboard</li>
            <li><i class="bi bi-capsule"></i> Kelola Obat</li>
            <li class="active"><i class="bi bi-person"></i> Kelola User</li>
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
            <div class="table-header">
                <h2>Kelola User</h2>
                <button class="btn-primary" id="btnTambahUser">+ Tambah Pengguna</button>
            </div>

            <table>
                <thead>
                    <tr>
                        <!-- ID tidak ditampilkan di tabel -->
                        <th>Nama Lengkap</th>
                        <th>Peran</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $users->fetch()): ?>
                        <tr>
                            <!-- ID disembunyikan tapi tetap digunakan -->
                            <td hidden><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['fullname']) ?></td>
                            <td><?= htmlspecialchars($row['role']) ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td>••••••••</td>
                            <td>
                                <button class="btn-edit"
                                    data-id="<?= $row['id'] ?>"
                                    data-fullname="<?= htmlspecialchars($row['fullname']) ?>"
                                    data-role="<?= htmlspecialchars($row['role']) ?>"
                                    data-username="<?= htmlspecialchars($row['username']) ?>">
                                    ✏️
                                </button>
                                <button class="btn-delete" data-id="<?= $row['id'] ?>">🗑️</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>

<!-- MODAL POPUP -->
<div class="modal-blur" id="userModal">
  <div class="modal-content">
    <h3 id="modalTitle">Tambah Pengguna</h3>
    <form id="userForm">
        <input type="hidden" name="id" id="id">
        <input type="hidden" name="action" id="action" value="tambah">

        <label>Nama Lengkap</label>
        <input type="text" name="fullname" id="fullname" required>

        <label>Peran</label>
        <select name="role" id="role" required>
            <option value="admin">Admin</option>
            <option value="staff">Staff</option>
        </select>

        <label>Username</label>
        <input type="text" name="username" id="username" required>

        <label>Password</label>
        <input type="password" name="password" id="password">

        <div class="form-actions">
            <button type="submit" class="btn-primary">Simpan</button>
            <button type="button" class="btn-outline" id="closeModal">Batal</button>
        </div>
    </form>
  </div>
</div>

<?php include '../includes/footer.php'; ?>

<script src="../assets/js/kelola_user.js"></script>
