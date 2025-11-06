document.addEventListener("DOMContentLoaded", () => {
  const btnLogout = document.getElementById("btnLogout");
  const modal = document.getElementById("logoutModal");
  const confirmBtn = document.getElementById("confirmLogout");
  const cancelBtn = document.getElementById("cancelLogout");

  btnLogout.addEventListener("click", () => modal.classList.add("active"));
  cancelBtn.addEventListener("click", () => modal.classList.remove("active"));
  confirmBtn.addEventListener("click", () => {
    window.location.href = "../logout.php";
  });
});
