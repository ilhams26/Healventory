<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'staff') {
    header("Location: login.php");
    exit;
}

$total_obat = 940;
$obat_masuk = 50;
$obat_keluar = 45;
$obat_menipis = 3;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Staff</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <h2 class="logo">Healventory</h2>
            <ul class="menu">
                <li class="active"><i class="bi bi-house-fill"></i> Dashboard</li>
                <li><i class="bi bi-arrow-left-right"></i> Transaksi</li>
                <li><i class="bi bi-activity"></i> Monitoring</li>
                <li id="btnLogout"><i class="bi bi-box-arrow-left"></i> Logout</li>
            </ul>
        </aside>

        <main class="main-content">
            <header class="header">
                <span class="role">Staff</span>
                <i class="bi bi-person-circle profile-icon"></i>
            </header>

            <?php include 'includes/dashboard_content.php'; ?>
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
    <?php include 'includes/modal_logout.php'; ?>
    <script src="assets/js/script.js"></script>
</body>

</html>