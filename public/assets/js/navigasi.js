document.addEventListener("DOMContentLoaded", () => {

    // ============================
    // BASE PATH (sesuaikan folder kamu)
    // ============================
    const base = "/Healventory/public/admin/";

    // ============================
    // MAP NAVIGASI
    // ============================
    const nav = {
        menuDashboard: "dashboard.php",
        menuObat: "kelola_obat.php",
        menuUser: "kelola_user.php",
        menuTransaksi: "transaksi.php",
        menuLaporan: "laporan.php",
        menuMonitoring: "monitoring.php"
    };

    // ============================
    // SET EVENT CLICK UNTUK SEMUA MENU
    // ============================
    Object.keys(nav).forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener("click", () => {
                window.location.href = base + nav[id];
            });
        }
    });

    // ============================
    // LOGOUT
    // ============================

    const btnLogout = document.getElementById("btnLogout");
    if (btnLogout) {
        btnLogout.addEventListener("click", () => {
            window.location.href = base + "logout.php";
        });
    }

});
