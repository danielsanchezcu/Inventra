import React, { useState } from 'react';
import axios from 'axios';
import Swal from 'sweetalert2';
import './EditarAsignacionModal.css'; // Asegúrate de tener este archivo CSS

const EditarAsignacionModal = ({ asignacion, onClose, onUpdate }) => {
    const [formData, setFormData] = useState(asignacion);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData({ ...formData, [name]: value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const response = await axios.put('http://localhost/Inventra-final/api/api_editar_asignacion.php', formData);
            if (response.data.success) {
                onUpdate(formData);
                Swal.fire({
                    icon: 'success',
                    title: 'Asignación actualizada',
                    text: 'La asignación fue actualizada correctamente.',
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.data.message || 'Error al actualizar',
                });
            }
        } catch (error) {
            console.error('Error al actualizar asignación:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al actualizar la asignación.',
            });
        }
    };

    return (
        <div className="fondo-modal">
            <div className="contenedor-modal">
                <main className="main">
                    <div className="main-header">
                        <h2>Editar Asignación de equipo</h2>
                    </div>

                    <div className="form-contenedor">
                        <div className="formulario">
                            <h3 className="titulo-seccion">
                                <i className="bx bx-user"></i> Información del usuario
                            </h3>

                            <form onSubmit={handleSubmit} id="asignacionForm">
                                <div className="form-usuario">
                                    <div className="form-group">
                                        <label htmlFor="nombres" className="required-field">Nombres</label>
                                        <input type="text" id="nombres" name="nombres" placeholder="Ingrese nombres completos" value={formData.nombres} onChange={handleChange} required />
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="apellidos" className="required-field">Apellidos</label>
                                        <input type="text" id="apellidos" name="apellidos" placeholder="Ingrese apellidos completos" value={formData.apellidos} onChange={handleChange} required />
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="area" className="required-field">Área</label>
                                        <input type="text" id="area" name="area" placeholder="Ej: Recursos Humanos" value={formData.area} onChange={handleChange} required />
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="sede" className="required-field">Sede</label>
                                        <input type="text" id="sede" name="sede" placeholder="Sede" value={formData.sede} onChange={handleChange} required />
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="correo" className="required-field">Correo electrónico</label>
                                        <input type="email" id="correo" name="correo" placeholder="ejemplo@inventra.com" value={formData.correo} onChange={handleChange} required />
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="cargo" className="required-field">Cargo</label>
                                        <input type="text" id="cargo" name="cargo" placeholder="Ej: Analista de RH" value={formData.cargo} onChange={handleChange} required />
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="contrato" className="required-field">Tipo de Contrato</label>
                                        <select id="contrato" name="contrato" value={formData.contrato} onChange={handleChange} required>
                                            <option value="" disabled>Seleccione una opción</option>
                                            <option value="planta">Planta</option>
                                            <option value="prestacion">Prestación de servicios</option>
                                            <option value="pasante">Pasante</option>
                                            <option value="temporal">Temporal</option>
                                        </select>
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="identificacion" className="required-field">Identificación</label>
                                        <input type="text" id="identificacion" name="identificacion" placeholder="Sin puntos ni guiones" value={formData.identificacion} onChange={handleChange} required />
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="extension">Extensión / Teléfono </label>
                                        <input type="text" id="extension" name="extension" placeholder="Extensión / Teléfono" value={formData.extension} onChange={handleChange} />
                                    </div>
                                </div>

                                <div className="form_equipo">
                                    <h3 className="titulo-seccion">
                                        <i className="bx bx-laptop"></i> Información del equipo
                                    </h3>

                                    <div className="form-grid">
                                        <div className="form-group">
                                            <label htmlFor="placa_inventario" className="required-field">Placa de Inventario</label>
                                            <input type="text" id="placa_inventario" name="placa_inventario" placeholder="Ej: INV-001" value={formData.placa_inventario} onChange={handleChange} required />
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="serial" className="required-field">Serial</label>
                                            <input type="text" id="serial" name="serial" placeholder="Número de serial del equipo" value={formData.serial} onChange={handleChange} required />
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="marca" className="required-field">Marca</label>
                                            <input type="text" id="marca" name="marca" placeholder="Marca" value={formData.marca} onChange={handleChange} required />
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="modelo" className="required-field">Modelo</label>
                                            <input type="text" id="modelo" name="modelo" placeholder="Modelo" value={formData.modelo} onChange={handleChange} required />
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="tipo" className="required-field">Tipo de Equipo</label>
                                            <input type="text" id="tipo" name="tipo" placeholder="Desktop,Laptop,Workstation,Otro" value={formData.tipo} onChange={handleChange} required />
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="estado" className="required-field">Estado</label>
                                            <select id="estado" name="estado" value={formData.estado} onChange={handleChange} required>
                                                <option value="" disabled>Seleccione una opción</option>
                                                <option value="asignado">Asignado</option>
                                                <option value="sin-asignacion">Sin asignación</option>
                                                <option value="mantenimiento">En mantenimiento</option>
                                                <option value="baja">Dado de baja</option>
                                            </select>
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="accesorios" className="required-field">Accesorios adicionales</label>
                                            <input type="text" id="accesorios" name="accesorios" placeholder="Cargador, base refrigerante, maleta, adaptadores." value={formData.accesorios} onChange={handleChange} required />
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="fecha_asignacion" className="required-field">Fecha de Asignación</label>
                                            <input type="date" id="fecha_asignacion" name="fecha_asignacion" value={formData.fecha_asignacion} onChange={handleChange} required />
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="fecha_devolucion">Fecha de Devolución</label>
                                            <input type="date" id="fecha_devolucion" name="fecha_devolucion" value={formData.fecha_devolucion} onChange={handleChange} />
                                            <p className="form-note">Complete solo si aplica</p>
                                        </div>
                                    </div>

                                    <div className="form-group" style={{ marginTop: '1.5rem' }}>
                                        <label htmlFor="observaciones">Observaciones</label>
                                        <textarea id="observaciones" name="observaciones" placeholder="Observaciones adicionales..." value={formData.observaciones} onChange={handleChange}></textarea>
                                    </div>
                                </div>

                                <div className="buttons">
                                    <button type="button" className="boton-cancelar" onClick={onClose}>
                                        <i className="bx bx-x"></i> Cancelar
                                    </button>
                                    <button type="submit" className="boton-confirmar">
                                        <i className="bx bx-save icono-guardar"></i> Guardar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    );
};

export default EditarAsignacionModal;
