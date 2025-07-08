document.addEventListener('DOMContentLoaded', function () {
  const ctx = document.getElementById('graficoCircular');

  if (ctx) {
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Disponibles', 'Mantenimiento', 'Asignados'],
        datasets: [{
          data: window.datosGrafico || [0, 0, 0],
          backgroundColor: ['#4caf50', '#ffc107', '#012e42'],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom'
          }
        }
      }
    });
  } else {
    console.warn("No se encontró el canvas con id 'graficoCircular'");
  }
});
