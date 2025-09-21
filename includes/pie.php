	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
	<script src="https://cdn.datatables.net/2.3.3/js/dataTables.js"></script>
	<script src="https://cdn.datatables.net/2.3.3/js/dataTables.bootstrap5.js"></script>
	<script>
	    // Datos del gráfico circular
	    window.datosGrafico = [<?= $equipos ?>, <?= $mantenimiento ?>, <?= $asignaciones ?>];

	    // Datos del gráfico de barras
	    const labelsBarras = <?= json_encode($labels); ?>;
	    const valoresBarras = <?= json_encode($valores); ?>;
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="dashboard.js"></script>
</body>
</html>