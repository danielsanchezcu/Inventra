import React, { useState, useEffect } from 'react';
import axios from 'axios';
import Swal from 'sweetalert2';
import { FaUser } from 'react-icons/fa';
import { BsCpuFill } from "react-icons/bs";
import './EditarAsignacionModal.css';

const EditarAsignacionModal = ({ asignacion, onClose, onUpdate }) => {
    const [formData, setFormData] = useState({
        id_asignacion: asignacion?.id_asignacion || '',
        nombres: asignacion?.nombres || '',
        apellidos: asignacion?.apellidos || '',
        area: asignacion?.area || '',
        sede: asignacion?.sede || '',
        correo_electronico: asignacion?.correo_electronico || '',
        cargo: asignacion?.cargo || '',
        tipo_contrato: asignacion?.tipo_contrato || '',
        identificacion: asignacion?.identificacion || '',
        extension_telefono: asignacion?.extension_telefono || '',
        placa_inventario: asignacion?.placa_inventario || '',
        serial: asignacion?.serial || '',
        marca: asignacion?.marca || '',
        modelo: asignacion?.modelo || '',
        tipo_equipo: asignacion?.tipo_equipo || '',
        estado: asignacion?.estado || '',
        accesorios_adicionales: asignacion?.accesorios_adicionales || '',
        fecha_asignacion: asignacion?.fecha_asignacion || '',
        fecha_devolucion: asignacion?.fecha_devolucion || '',
        observaciones: asignacion?.observaciones || ''
    });

    useEffect(() => {
        if (asignacion?.id_asignacion) {
            axios.get(`http://localhost/Inventra-final/api/api_get_asignacion.php?id_asignacion=${asignacion.id_asignacion}`)
                .then(res => {
                    if (res.data.success && res.data.asignacion) {
                        setFormData(prev => ({
                            ...prev,
                            ...res.data.asignacion
                        }));
                    }
                })
                .catch(err => console.error('Error al obtener asignación:', err));
        }
    }, [asignacion?.id_asignacion]);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData((prev) => ({ ...prev, [name]: value }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();

        try {
            const response = await axios.post(
                'http://localhost/Inventra-final/api/api_editar_asignacion.php',
                JSON.stringify(formData),
                { headers: { 'Content-Type': 'application/json' } }
            );

            if (response.data.success) {
                onUpdate(formData);
                Swal.fire({
                icon: 'success',
                title: 'Asignación actualizada',
                text: 'La asignación fue actualizada correctamente.',
                timer: 5000,
                customClass: {
                popup: 'my-swal-popup',
                title: 'my-swal-title',
                htmlContainer: 'my-swal-text',
                confirmButton: 'my-swal-btn'
                
    }
});

            onUpdate({
                    ...formData,
                    fecha_asignacion: response.data.asignacion?.fecha_asignacion || formData.fecha_asignacion
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
                        <button className="close-button" aria-label="Cerrar modal" onClick={onClose}> X </button>
                        <h2>Editar Asignación de equipo</h2>
                    </div>

                    <div className="form-contenedor">
                        <div className="formulario">
                            <h3 className="titulo-seccion">
                                <FaUser className="icono-usuario" />
                                Información del usuario
                            </h3>

                            <form onSubmit={handleSubmit} id="asignacionForm">
                                <div className="form-usuario">
                                    <div className="form-group">
                                        <label htmlFor="nombres" className="required-field">Nombres</label>
                                        <input type="text" id="nombres" name="nombres" value={formData.nombres} onChange={handleChange} required />
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="apellidos" className="required-field">Apellidos</label>
                                        <input type="text" id="apellidos" name="apellidos" value={formData.apellidos} onChange={handleChange} required />
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="area" className="required-field">Área</label>
                                        <input type="text" id="area" name="area" value={formData.area} onChange={handleChange} required />
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="sede" className="required-field">Sede</label>
                                        <input type="text" id="sede" name="sede" value={formData.sede} onChange={handleChange} required />
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="correo_electronico" className="required-field">Correo</label>
                                        <input type="text" id="correo_electronico" name="correo_electronico" value={formData.correo_electronico} onChange={handleChange} required />
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="cargo" className="required-field">Cargo</label>
                                        <input type="text" id="cargo" name="cargo" value={formData.cargo} onChange={handleChange} required />
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="tipo_contrato" className="required-field">Contrato</label>
                                        <select id="tipo_contrato" name="tipo_contrato" value={formData.tipo_contrato} onChange={handleChange} required>
                                            <option value="" disabled>Seleccione una opción</option>
                                            <option value="Planta">Planta</option>
                                            <option value="Prestación de servicios">Prestación de servicios</option>
                                            <option value="Pasante">Pasante</option>
                                            <option value="Temporal">Temporal</option>
                                        </select>
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="identificacion" className="required-field">Identificación</label>
                                        <input type="text" id="identificacion" name="identificacion" value={formData.identificacion} onChange={handleChange} required />
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="extension_telefono">Extensión / Teléfono</label>
                                        <input type="text" id="extension_telefono" name="extension_telefono" value={formData.extension_telefono} onChange={handleChange} />
                                    </div>
                                </div>  
                                <div className="form_equipo">
                                    <h3 className="titulo-seccion">
                                        <BsCpuFill className="icono-equipo" />
                                        Información del equipo
                                    </h3>
                                    <div className="form-grid">
                                        <div className="form-group">
                                            <label htmlFor="placa_inventario" className="required-field">Placa Inventario</label>
                                            <input type="text" id="placa_inventario" name="placa_inventario" value={formData.placa_inventario} onChange={handleChange} readOnly/>
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="serial" className="required-field">Serial</label>
                                            <input type="text" id="serial" name="serial" value={formData.serial} onChange={handleChange} readOnly />
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="marca" className="required-field">Marca</label>
                                            <input type="text" id="marca" name="marca" value={formData.marca} onChange={handleChange} readOnly />
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="modelo" className="required-field">Modelo</label>
                                            <input type="text" id="modelo" name="modelo" value={formData.modelo} onChange={handleChange} readOnly />
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="tipo_equipo" className="required-field">Tipo</label>
                                            <input type="text" id="tipo_equipo" name="tipo_equipo" value={formData.tipo_equipo} onChange={handleChange} readOnly />
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="estado" className="required-field">Estado</label>
                                            <select id="estado" name="estado" value={formData.estado} onChange={handleChange} required>
                                                <option value="" disabled>Seleccione una opción</option>
                                                <option value="Disponible">Disponible</option>
                                                <option value="En mantenimiento">En mantenimiento</option>
                                                <option value="Dado de baja">Dado de baja</option>
                                                <option value="Asignado">Asignado</option>
                                            </select>
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="accesorios_adicionales" className="required-field">Accesorios</label>
                                            <input type="text" id="accesorios_adicionales" name="accesorios_adicionales" value={formData.accesorios_adicionales} onChange={handleChange} required />
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="fecha_asignacion" className="required-field">Fecha Asignación</label>
                                            <input type="date" id="fecha_asignacion" name="fecha_asignacion" value={formData.fecha_asignacion} onChange={handleChange} required />
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="fecha_devolucion">Fecha Devolución</label>
                                            <input type="date" id="fecha_devolucion" name="fecha_devolucion" value={formData.fecha_devolucion} onChange={handleChange} />
                                        </div>
                                    </div>

                                    <div className="form-group" style={{ marginTop: '1.5rem' }}>
                                        <label htmlFor="observaciones">Observaciones</label>
                                        <textarea id="observaciones" name="observaciones" value={formData.observaciones} onChange={handleChange}></textarea>
                                    </div>
                                </div>
                                <div className="buttons">
                                    <button type="submit" className="boton-confirmar">Guardar</button>
                                    <button type="button" className="boton-cancelar" onClick={onClose}>Cancelar</button>
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
