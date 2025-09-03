<?php
    require("includes/conexion.php");
    require("includes/encabezado.php"); 
?>


<main class="main">
    <div class="main-header">
        <h2>Informe de  Inventarios</h2>
    </div>
    <div class="form-contenedor">
        <div class="formulario">
            <h3 class="titulo-seccion">
            <i class="fas fa-desktop"></i> Inventario por Estado
            </h3>
            <div class="col">
                <label for="estado" class="required-field">Usuario</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="">Seleccione un Estado</option>
                    <option value="asignado">Asignados</option>
                    <option value="sin-asignacion">Sin Asignación</option>
                </select>
            </div>
            <div class="col mt-3">
                <table class="table table-bordered table-striped" id="tablaInforme1">
                	<thead>
                		<tr class="text-center">
                            <th>ID Equipo</th>
                            <th>Fecha Asignación</th>
                            <th>Nombre</th>
                            <th>Serial</th>
                            <th>Estado</th>
                            <th>Observaciones</th>
                		</tr>
                	</thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script src="informesEstado.js"></script>
<?php require("includes/pie.php"); ?>
<script>
    $('#tablaInforme1').DataTable(
        {
            "language": {
            "url": "Spanish.json"
            }
        }
    );
</script>