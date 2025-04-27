// Rafraîchir les notifications toutes les 5 minutes
setInterval(() => {
    fetch('{{ path("notifications_widget") }}')
        .then(response => response.text())
        .then(html => {
            document.querySelector('.dropdown-notification').outerHTML = html;
        });
}, 300000);

// Rafraîchir quand on ouvre le dropdown
document.querySelector('.pc-head-link[data-bs-toggle="dropdown"]').addEventListener('click', function() {
    fetch('{{ path("notifications_widget") }}')
        .then(response => response.text())
        .then(html => {
            document.querySelector('.dropdown-notification').outerHTML = html;
        });
});