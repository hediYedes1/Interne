document.addEventListener('DOMContentLoaded', function() {
    // Graphique pour les interviews
    const interviewCtx = document.getElementById('interviewChart').getContext('2d');
    new Chart(interviewCtx, {
        type: 'pie',
        data: {
            labels: ['En ligne', 'En personne'],
            datasets: [{
                data: [
                    document.getElementById('interviewChart').dataset.online,
                    document.getElementById('interviewChart').dataset.inperson
                ],
                backgroundColor: ['#4e73df', '#1cc88a']
            }]
        }
    });

    // Graphique pour les tests techniques
    const testCtx = document.getElementById('testTechniqueChart').getContext('2d');
    new Chart(testCtx, {
        type: 'bar',
        data: {
            labels: ['Accepté', 'Refusé', 'En attente'],
            datasets: [{
                data: [
                    document.getElementById('testTechniqueChart').dataset.accepted,
                    document.getElementById('testTechniqueChart').dataset.refused,
                    document.getElementById('testTechniqueChart').dataset.pending
                ],
                backgroundColor: ['#1cc88a', '#e74a3b', '#f6c23e']
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});

// Recherche dynamique
function initDynamicSearch(inputId, tableId) {
    const searchInput = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    
    searchInput.addEventListener('input', function(e) {
        const searchText = e.target.value.toLowerCase();
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName('td');
            let found = false;

            for (let j = 0; j < cells.length; j++) {
                const cellText = cells[j].textContent.toLowerCase();
                if (cellText.includes(searchText)) {
                    found = true;
                    break;
                }
            }

            row.style.display = found ? '' : 'none';
        }
    });
} 