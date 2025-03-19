
const clientsCtx = document.getElementById('clientsChart').getContext('2d');
const clientsChart = new Chart(clientsCtx, {
  type: 'line',
  data: {
    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
    datasets: [{
      label: 'Nombre de clients',
      data: [120, 150, 200, 180, 250, 300],
      borderColor: '#007bff',
      fill: false,
    }]
  },
});


const basketCtx = document.getElementById('basketChart').getContext('2d');
const basketChart = new Chart(basketCtx, {
  type: 'bar',
  data: {
    labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
    datasets: [{
      label: 'Panier moyen (€)',
      data: [25, 30, 35, 40, 45, 50, 55],
      backgroundColor: '#28a745',
    }]
  },
});