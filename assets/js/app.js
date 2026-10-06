document.addEventListener('DOMContentLoaded', function () {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alertEl) {
        setTimeout(() => alertEl.remove(), 4000);
    });
});
