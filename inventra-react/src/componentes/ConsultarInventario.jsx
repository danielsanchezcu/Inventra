import React, { useState, useEffect, useCallback } from 'react';
import axios from 'axios';
import './ConsultarInventario.css';
import { FaEdit, FaTrash } from 'react-icons/fa';
import EditarAsignacionModal from './EditarAsignacionModal';
import Swal from 'sweetalert2';

const ConsultarInventario = () => {
  const [asignaciones, setAsignaciones] = useState([]);
  const [asignacionesFiltradas, setAsignacionesFiltradas] = useState([]);
  const [textoFiltro, setTextoFiltro] = useState('');
  const [departamentoFiltro, setDepartamentoFiltro] = useState('Todos');
  const [tipoEquipoFiltro, setTipoEquipoFiltro] = useState('Todos');
  const [configOrden, setConfigOrden] = useState({ clave: null, direccion: 'asc' });
  const [paginaActual, setPaginaActual] = useState(1);
  const itemsPorPagina = 8;
  const [modalAbierto, setModalAbierto] = useState(false);
  const [asignacionAEliminar, setAsignacionAEliminar] = useState(null);
  const [modalEditarAbierto, setModalEditarAbierto] = useState(false);
  const [asignacionAEditar, setAsignacionAEditar] = useState(null);

  // lista fija de áreas (sin setAreas para evitar advertencias)
  const [areas] = useState(['Todos', 'Tecnologia', 'Contabilidad', 'Diseño', 'Ventas']);

  // Traer datos (memoizada para poder reutilizarla tras guardar)
  const fetchAsignaciones = useCallback(async () => {
    try {
      const response = await axios.get(
        `${window.location.origin}/api/api_inventario.php`,
        {
          params: {
            busqueda: textoFiltro,
            area: departamentoFiltro,
            tipo: tipoEquipoFiltro,
          },
        }
      );

      const data = Array.isArray(response.data) ? response.data : [];
      setAsignaciones(data);
      setAsignacionesFiltradas(data);
    } catch (error) {
      console.error('Error al obtener datos:', error);
      setAsignaciones([]);
      setAsignacionesFiltradas([]);
    }
  }, [textoFiltro, departamentoFiltro, tipoEquipoFiltro]);

  useEffect(() => {
    fetchAsignaciones();
  }, [fetchAsignaciones]);

  useEffect(() => {
    let filtradas = asignaciones.filter((asignacion) => {
      const textoNormalizado = textoFiltro.toLowerCase();
      const coincideTexto =
        asignacion.nombres?.toLowerCase().includes(textoNormalizado) ||
        asignacion.apellidos?.toLowerCase().includes(textoNormalizado) ||
        asignacion.identificacion?.toLowerCase().includes(textoNormalizado);

      const coincideDepto =
        departamentoFiltro === 'Todos' || asignacion.area === departamentoFiltro;

      const coincideTipo =
        tipoEquipoFiltro === 'Todos' || asignacion.tipo_equipo === tipoEquipoFiltro;

      return coincideTexto && coincideDepto && coincideTipo;
    });

    if (configOrden.clave) {
      filtradas.sort((a, b) => {
        const valorA = a[configOrden.clave] || '';
        const valorB = b[configOrden.clave] || '';

        if (valorA < valorB) return configOrden.direccion === 'asc' ? -1 : 1;
        if (valorA > valorB) return configOrden.direccion === 'asc' ? 1 : -1;
        return 0;
      });
    }

    setAsignacionesFiltradas(filtradas);
    setPaginaActual(1);
  }, [textoFiltro, departamentoFiltro, tipoEquipoFiltro, configOrden, asignaciones]);

  const solicitarOrden = (clave) => {
    let direccion = 'asc';
    if (configOrden.clave === clave && configOrden.direccion === 'asc') {
      direccion = 'desc';
    }
    setConfigOrden({ clave, direccion });
  };

  const obtenerIconoOrden = (clave) => {
    if (configOrden.clave !== clave) return '⇅';
    return configOrden.direccion === 'asc' ? '↑' : '↓';
  };

  const confirmarEliminar = (asignacion) => {
    setAsignacionAEliminar(asignacion);
    setModalAbierto(true);
  };

  // ✅ Solo esta parte fue corregida
  const eliminarAsignacion = async () => {
    try {
      const response = await axios.delete(
        `${window.location.origin}/api/api_eliminar_asignacion.php`,
        {
          data: { id_asignacion: asignacionAEliminar.id_asignacion },
        }
      );

      if (response.data.success) {
        setAsignaciones((prev) =>
          prev.filter((asig) => asig.id_asignacion !== asignacionAEliminar.id_asignacion)
        );
        setModalAbierto(false);
        setAsignacionAEliminar(null);

        Swal.fire({
          icon: 'success',
          title: 'Asignación eliminada',
          text: 'La asignación fue eliminada correctamente.',
          timer: 5000,
          customClass: {
            popup: 'my-swal-popup',
            title: 'my-swal-title',
            htmlContainer: 'my-swal-text',
            confirmButton: 'my-swal-btn',
          },
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: response.data.message || 'Error al eliminar',
          confirmButtonColor: '#d33',
        });
      }
    } catch (error) {
      console.error('Error al eliminar asignación:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Ocurrió un error al eliminar la asignación.',
        confirmButtonColor: '#d33',
      });
    }
  };

  const indiceUltimoItem = paginaActual * itemsPorPagina;
  const indicePrimerItem = indiceUltimoItem - itemsPorPagina;
  const itemsActuales = asignacionesFiltradas.slice(indicePrimerItem, indiceUltimoItem);

  const editarAsignacion = (id) => {
    setAsignacionAEditar({ id_asignacion: id });
    setModalEditarAbierto(true);
  };

  return (
    <div className="contenedor-inventario">
      <div className="contenedor-principal">
        <h1 className="titulo-inventario">Gestión de Inventario</h1>

        {/* Filtros */}
        <div className="contenedor-filtros">
          <div className="contenedor-flex-filtros">
            <div className="contenedor-busqueda">
              <label className="etiqueta-busqueda">Buscar</label>
              <input
                type="text"
                className="entrada-busqueda"
                placeholder="Buscar por nombre, apellido o Cedula..."
                value={textoFiltro}
                onChange={(e) => setTextoFiltro(e.target.value)}
              />
            </div>

            <div className="contenedor-selector">
              <label className="etiqueta-busqueda">Área</label>
              <select
                className="entrada-busqueda"
                value={departamentoFiltro}
                onChange={(e) => setDepartamentoFiltro(e.target.value)}
              >
                {areas.map((area) => (
                  <option key={area} value={area}>
                    {area}
                  </option>
                ))}
              </select>
            </div>

            <div className="contenedor-selector">
              <label className="etiqueta-busqueda">Tipo de Equipo</label>
              <select
                className="entrada-busqueda"
                value={tipoEquipoFiltro}
                onChange={(e) => setTipoEquipoFiltro(e.target.value)}
              >
                <option value="Todos">Todos</option>
                <option value="Laptop">Laptop</option>
                <option value="Desktop">Desktop</option>
              </select>
            </div>
          </div>
        </div>

        {/* Tabla */}
        <div className="contenedor-tabla">
          <div className="contenedor-scroll-tabla">
            <table className="tabla-datos">
              <thead className="encabezado-tabla">
                <tr>
                  <th className="celda-encabezado" onClick={() => solicitarOrden('nombres')}>
                    Nombres {obtenerIconoOrden('nombres')}
                  </th>
                  <th className="celda-encabezado" onClick={() => solicitarOrden('apellidos')}>
                    Apellidos {obtenerIconoOrden('apellidos')}
                  </th>
                  <th className="celda-encabezado" onClick={() => solicitarOrden('identificacion')}>
                    Identificación {obtenerIconoOrden('identificacion')}
                  </th>
                  <th className="celda-encabezado" onClick={() => solicitarOrden('tipo_contrato')}>
                    Contrato{obtenerIconoOrden('tipo_contrato')}
                  </th>
                  <th className="celda-encabezado" onClick={() => solicitarOrden('placa_inventario')}>
                    Placa Inventario {obtenerIconoOrden('placa_inventario')}
                  </th>
                  <th className="celda-encabezado" onClick={() => solicitarOrden('serial')}>
                    Serial {obtenerIconoOrden('serial')}
                  </th>
                  <th className="celda-encabezado" onClick={() => solicitarOrden('tipo_equipo')}>
                    Tipo {obtenerIconoOrden('tipo_equipo')}
                  </th>
                  <th className="celda-encabezado" onClick={() => solicitarOrden('marca')}>
                    Marca {obtenerIconoOrden('marca')}
                  </th>
                  <th className="celda-encabezado" onClick={() => solicitarOrden('area')}>
                    Área {obtenerIconoOrden('area')}
                  </th>
                  <th className="celda-encabezado" onClick={() => solicitarOrden('fecha_asignacion')}>
                    Fecha de Asignación {obtenerIconoOrden('fecha_asignacion')}
                  </th>
                  <th className="celda-encabezado" onClick={() => solicitarOrden('estado')}>
                    Estado {obtenerIconoOrden('estado')}
                  </th>
                  <th className="celda-encabezado">Acciones</th>
                </tr>
              </thead>
              <tbody>
                {itemsActuales.length > 0 ? (
                  itemsActuales.map((asignacion) => (
                    <tr key={asignacion.id_asignacion} className="fila-tabla">
                      <td className="celda-dato">{asignacion.nombres}</td>
                      <td className="celda-dato">{asignacion.apellidos}</td>
                      <td className="celda-dato">{asignacion.identificacion}</td>
                      <td className="celda-dato">{asignacion.tipo_contrato}</td>
                      <td className="celda-dato">{asignacion.placa_inventario}</td>
                      <td className="celda-dato">{asignacion.serial}</td>
                      <td className="celda-dato">{asignacion.tipo_equipo}</td>
                      <td className="celda-dato">{asignacion.marca}</td>
                      <td className="celda-dato">{asignacion.area}</td>
                      <td className="celda-dato">{asignacion.fecha_asignacion}</td>
                      <td className="celda-dato">{asignacion.estado}</td>
                      <td className="celda-dato">
                        <div className="contenedor-botones">
                          <button
                            className="boton-icono editar"
                            onClick={() => editarAsignacion(asignacion.id_asignacion)}
                            title="Editar"
                          >
                            <FaEdit />
                          </button>
                          <button
                            className="boton-icono eliminar"
                            onClick={() => confirmarEliminar(asignacion)}
                            title="Eliminar"
                          >
                            <FaTrash />
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan="11">
                      <div className="sin-resultados">No se encontraron resultados</div>
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>

        {/* Modal de confirmación */}
        {modalAbierto && (
          <div className="fondo-modal">
            <div className="contenedor-modal-confirmacion">
              <h3 className="titulo-modal">Confirmar eliminación</h3>
              <p className="mensaje-modal">
                ¿Estás seguro de eliminar la asignación de{' '}
                <span className="destacado-modal">
                  {asignacionAEliminar?.nombres} {asignacionAEliminar?.apellidos}
                </span>
                ?
              </p>
              <p className="mensaje2-modal">
                El equipo eliminado quedará disponible para ser reasignado.
              </p>
              <div className="acciones-modal">
                <button className="boton-cancelar-modal" onClick={() => setModalAbierto(false)}>
                  Cancelar
                </button>
                <button className="boton-confirmar-modal" onClick={eliminarAsignacion}>
                  Confirmar
                </button>
              </div>
            </div>
          </div>
        )}

        {/* Modal de edición */}
        {modalEditarAbierto && (
          <EditarAsignacionModal
            asignacion={asignacionAEditar}
            onClose={() => setModalEditarAbierto(false)}
            onUpdate={(actualizada) => {
              setAsignaciones((prev) =>
                prev.map((asig) =>
                  asig.id_asignacion === actualizada.id_asignacion ? actualizada : asig
                )
              );
              // Cierra el modal
              setModalEditarAbierto(false);
              // Refresca la tabla desde la API (estado real en BD)
              fetchAsignaciones();
            }}
          />
        )}
      </div>
    </div>
  );
};

export default ConsultarInventario;
