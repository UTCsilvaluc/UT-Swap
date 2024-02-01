document.addEventListener('DOMContentLoaded', function () {
    // Récupérer l'élément de notification et son contenu
    var notificationTab = document.getElementById('notificationTab');
    var notificationContent = notificationTab.querySelector('.notification-content');

    // Ajouter un gestionnaire d'événements au clic sur l'élément de notification
    notificationTab.addEventListener('click', function () {
        // Inverser la visibilité de la section des notifications en ajoutant/supprimant la classe 'visible'
        notificationContent.classList.toggle('visible');
    });
});
