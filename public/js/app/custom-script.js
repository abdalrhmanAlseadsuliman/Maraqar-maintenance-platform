document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.querySelector(".filament-sidebar");
    const toggleBtn = document.querySelector("[data-toggle-sidebar]");

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener("click", () => {
            sidebar.classList.toggle("open");
        });
    }
});
