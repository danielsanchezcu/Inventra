// 📝 Cargar datos en el formulario para editar
function editar(element) {
    document.getElementById("id").value = element.dataset.id;
    document.getElementById("nombre").value = element.dataset.nombre;
    document.getElementById("descripcion").value = element.dataset.descripcion;
    document.getElementById("precio").value = element.dataset.precio;
    document.getElementById('cantidad').value = element.dataset.cantidad;
}

// ✅ Mostrar mensajes flotantes
function mostrarMensaje(texto) {
    document.addEventListener("DOMContentLoaded", () => {
        const msg = document.getElementById("mensaje");
        document.getElementById("mensaje-texto").textContent = texto;
        msg.style.display = "block";
        setTimeout(() => {
            msg.style.display = "none";
            location.href = "repuestos.php";
        }, 1500);
    });
}

// ❌ Confirmación antes de eliminar
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("a.fa-trash").forEach(btn => {
        btn.addEventListener("click", (e) => {
            if (!confirm("¿Seguro que deseas eliminar este repuesto?")) {
                e.preventDefault();
            }
        });
    });
});
