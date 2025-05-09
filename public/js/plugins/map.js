let map;
let marker;

function initializeMap(existingLocation = null) {
    // Initialiser la carte
    map = L.map('map').setView([36.8065, 10.1815], 13); // Centré sur Tunis

    // Ajouter la couche OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Si une localisation existe, ajouter un marqueur
    if (existingLocation) {
        const coords = existingLocation.split(',');
        const lat = parseFloat(coords[0]);
        const lng = parseFloat(coords[1]);

        map.setView([lat, lng], 15);
        marker = L.marker([lat, lng]).addTo(map);

        // Faire une requête reverse geocoding pour obtenir l'adresse
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                const address = data.display_name;
                const addressField = document.getElementById('brancheentreprise_localisationbranche');
                if (addressField) {
                    addressField.value = address;
                }
            })
            .catch(error => console.error('Erreur de géocodage inverse:', error));
    }

    // Ajouter un marqueur lors du clic sur la carte
    map.on('click', function (e) {
        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker(e.latlng).addTo(map);

        // Mettre à jour le champ caché avec les coordonnées
        const coordsInput = document.getElementById('location_coords');
        if (coordsInput) {
            coordsInput.value = `${e.latlng.lat},${e.latlng.lng}`;
        }

        // Faire une requête reverse geocoding pour obtenir l'adresse
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${e.latlng.lat}&lon=${e.latlng.lng}`)
            .then(response => response.json())
            .then(data => {
                const address = data.display_name;
                const addressField = document.getElementById('brancheentreprise_localisationbranche');
                if (addressField) {
                    addressField.value = address;
                }
            })
            .catch(error => console.error('Erreur de géocodage inverse:', error));
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const locationInput = document.getElementById('location_coords');
    const existingLocation = locationInput ? locationInput.value : null;

    initializeMap(existingLocation);
});
