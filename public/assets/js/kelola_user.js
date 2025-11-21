document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("userModal");
  const form = document.getElementById("userForm");
  const btnTambah = document.getElementById("btnTambahUser");
  const closeBtn = document.getElementById("closeModal");

  // Tambah User
  btnTambah.addEventListener("click", () => {
    form.reset();
    document.getElementById("modalTitle").textContent = "Tambah Pengguna";
    document.getElementById("action").value = "tambah";
    modal.classList.add("active");
  });

  closeBtn.addEventListener("click", () => modal.classList.remove("active"));

  // Edit
  document.querySelectorAll(".btn-edit").forEach(btn => {
    btn.addEventListener("click", () => {
      modal.classList.add("active");
      document.getElementById("modalTitle").textContent = "Edit Pengguna";
      document.getElementById("action").value = "update";

      document.getElementById("id_user").value = btn.dataset.id;
      document.getElementById("nama").value = btn.dataset.nama;
      document.getElementById("peran").value = btn.dataset.peran;
      document.getElementById("username").value = btn.dataset.username;
      document.getElementById("password").value = "";
    });
  });

  // Hapus
  document.querySelectorAll(".btn-delete").forEach(btn => {
    btn.addEventListener("click", () => {
      const id = btn.dataset.id;
      if (confirm("Yakin ingin menghapus pengguna ini?")) {
        const formData = new FormData();
        formData.append("action", "hapus");
        formData.append("id_user", id);
        fetch("kelola_user.php", { method: "POST", body: formData })
          .then(() => location.reload());
      }
    });
  });

  // Simpan (Tambah/Edit)
  form.addEventListener("submit", e => {
    e.preventDefault();
    fetch("kelola_user.php", { method: "POST", body: new FormData(form) })
      .then(res => res.json())
      .then(data => {
        alert(data.msg);
        modal.classList.remove("active");
        location.reload();
      });
  });
});
