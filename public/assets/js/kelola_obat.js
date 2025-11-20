document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("modalForm");
  const form = document.getElementById("formObat");
  const btnTambah = document.getElementById("btnTambah");
  const btnBatal = document.getElementById("btnBatal");
  const modalTitle = document.getElementById("modalTitle");

  let editMode = false;
  let editId = null;

  // buka tambah
  btnTambah.addEventListener("click", () => {
    editMode = false;
    form.reset();
    modalTitle.textContent = "Tambah Obat";
    modal.classList.add("active");
  });

  // batal
  btnBatal.addEventListener("click", () => modal.classList.remove("active"));

  // edit
  document.querySelectorAll(".btn-edit").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const row = e.target.closest("tr");
      editMode = true;
      editId = row.dataset.id;
      modalTitle.textContent = "Edit Obat";

      document.getElementById("id").value = editId;
      document.getElementById("kode_obat").value = row.children[0].textContent;
      document.getElementById("nama").value = row.children[1].textContent;
      document.getElementById("kategori").value = row.children[2].textContent;
      document.getElementById("stok_awal").value = row.children[3].textContent;
      document.getElementById("stok_minimum").value = row.children[4].textContent;
      document.getElementById("tgl_kadaluarsa").value = row.children[5].textContent;

      modal.classList.add("active");
    });
  });

  // simpan data
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    const url = editMode ? "update_obat.php" : "tambah_obat.php";

    fetch(url, {
      method: "POST",
      body: formData,
    }).then(() => location.reload());
  });

  // hapus obat
  const modalHapus = document.getElementById("modalHapus");
  let hapusId = null;

  document.querySelectorAll(".btn-delete").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      hapusId = e.target.closest("tr").dataset.id;
      modalHapus.classList.add("active");
    });
  });

  document.getElementById("confirmHapus").addEventListener("click", () => {
    fetch("hapus_obat.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "id=" + hapusId,
    }).then(() => location.reload());
  });

  document.getElementById("cancelHapus").addEventListener("click", () => {
    modalHapus.classList.remove("active");
  });

  // Navigasi dari dashboard → kelola obat
  const menuDashboard = document.getElementById("menuDashboard");
  if (menuDashboard) {
    menuDashboard.addEventListener("click", () => {
      window.location.href = "dashboard.php";
    });
  }
});
