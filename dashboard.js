document.addEventListener('DOMContentLoaded', function () {
  // Gráfico circular
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

  // Gráfico de barras
  const ctxBarras = document.getElementById('graficoBarras');
  if (ctxBarras && typeof labelsBarras !== 'undefined' && typeof valoresBarras !== 'undefined') {
    new Chart(ctxBarras, {
      type: 'bar',
      data: {
        labels: labelsBarras,
        datasets: [{
          label: 'Asignaciones por área',
          data: valoresBarras,
          backgroundColor: 'rgba(54, 162, 235, 0.7)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          title: {
            display: true,
            text: 'Asignaciones por Área',
            color: '#333',
            font: {
              size: 15
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  } else {
    console.warn("No se encontró el canvas con id 'graficoBarras' o faltan datos.");
  }
});
