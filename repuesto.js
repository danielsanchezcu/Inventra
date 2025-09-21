function editar(enlace){
	let id = enlace.dataset.id;
    let nombre = enlace.dataset.nombre;
    let descripcion = enlace.dataset.descripcion;
    let precio = enlace.dataset.precio;
    document.getElementById('id').value=id;
    document.getElementById('nombre').value=nombre;
    document.getElementById('descripcion').value=descripcion;
    document.getElementById('precio').value=precio;
}
