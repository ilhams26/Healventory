document.addEventListener("DOMContentLoaded", function () {
    const btn = document.getElementById("btnCetakLaporan");

    btn.addEventListener("click", function () {
        window.open("cetak_laporan.php", "_blank");
    });
});
