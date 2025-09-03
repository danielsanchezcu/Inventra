document.addEventListener('DOMContentLoaded', function () {

	let contadorFilas=1;
	const tipo=document.getElementById("tipo");
	const miDiv=document.getElementById("repuestos");
	const boton=document.getElementById("agregar");
	const btnGuardaMtto=document.getElementById("grabaMtto");
	miDiv.style.display = 'none';

	tipo.addEventListener("change", function(){
		const resp=tipo.value;
		if (resp==="correctivo") {
			miDiv.style.display="flex";
			const precio=document.getElementById("precio")
		}else{
			miDiv.style.display="none";
		}
	});

	boton.addEventListener("click", function(e){
			e.preventDefault();
	    contadorFilas++;
	    let nuevaFila = document.querySelector('#tabla tbody tr').cloneNode(true);
	    nuevaFila.querySelectorAll('select').forEach(function(selectElement) {
	        let nombreOriginal = selectElement.getAttribute('id');
	        let baseName = nombreOriginal.split('_')[0];
	        selectElement.setAttribute('id', baseName + '_' + contadorFilas);
	        // selectElement.setAttribute('name', baseName + '_' + contadorFilas);
	        selectElement.value = ''; 
	    });
	    nuevaFila.querySelectorAll('input').forEach(function(selectElement) {
	        let nombreOriginal = selectElement.getAttribute('id');
	        let baseName = nombreOriginal.split('_')[0];
	        selectElement.setAttribute('id', baseName + '_' + contadorFilas);
	        // selectElement.setAttribute('name', baseName + '_' + contadorFilas);
	        selectElement.value = ''; 
	    });
	    document.querySelector('#tabla tbody').appendChild(nuevaFila);
	});
});

let precioSinFormato=0;
const formatterCOP = new Intl.NumberFormat('es-CO', {
		style: 'currency',
		currency: 'COP'
	});
function calcula(id){
	const i=id.split("_");
	const iden=document.getElementById(id);
	const opcion = iden.options[iden.selectedIndex];
	const precio = opcion.getAttribute('data-precio');
	precioSinFormato=precio;
  if (precio) {
  	document.getElementById("precio_"+i[1]).value=formatterCOP.format(precio);
  } else {
    document.getElementById("precio_"+i[1]).value='0';
  }
}

function total(id){
	const i=id.split("_");
	const precio=precioSinFormato;
	const cantidad=document.getElementById(id).value;
	document.getElementById("total_"+i[1]).value=formatterCOP.format(parseFloat(cantidad)*parseFloat(precio));
}


function edita (id) {
	document.getElementById("id").value=id
}
