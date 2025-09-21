document.addEventListener('DOMContentLoaded', function () {
	const opcion = document.getElementById("estado");

	opcion.addEventListener("change", function(){
		const opcion = this.value;
		fetch("api/listarEstados.php", {
		    method: "POST",
		    headers: { "Content-Type": "application/x-www-form-urlencoded" },
		   	body: "opcion="+opcion
      	})
        .then(response => {
	        if (!response.ok) {
	          throw new Error('Error en la respuesta del servidor.');
	        }
	        return response.json(); // Esperamos una respuesta en formato JSON
	      })
        .then(data => {
        	llenarTabla(data);
        })
        .catch(error => {
          console.error("Error:", error);
        })
	});
});

function llenarTabla(datos) {
	const tbody = document.getElementById('tablaInforme1').getElementsByTagName('tbody')[0];
	tbody.innerHTML = ''; 
	datos.forEach(fila => {
		const tr = document.createElement('tr');
		tr.innerHTML = `
		<td>${fila.id_equipo}</td>
		<td>${fila.fecha_asignacion}</td>
		<td>${fila.nombre}</td>
		<td>${fila.serial}</td>
		<td>${fila.estado}</td>
		<td>${fila.observaciones}</td>`;
		tbody.appendChild(tr);
	});
}