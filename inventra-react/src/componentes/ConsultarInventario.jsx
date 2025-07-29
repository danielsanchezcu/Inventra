import React, { useState, useEffect } from 'react';
import './ConsultarInventario.css';

const ConsultarInventario = () => {
  const [busqueda, setBusqueda] = useState('');
  const [filtro, setFiltro] = useState('placa');
  const [datos, setDatos] = useState([]);

  useEffect(() => {
    // Aquí podrías hacer una llamada a tu API o archivo PHP que retorne el inventario
    // Por ahora dejamos un array simulado
    setDatos([
      {
        id: 1,
        placa: 'INV-001',
        usuario: 'Carlos Pérez',
        area: 'Sistemas',
        estado: 'Activo'
      },
      // otros datos...
    ]);
  }, []);

  const manejarCambio = (e) => {
    setBusqueda(e.target.value);
  };

  const filtrarDatos = () => {
    return datos.filter(item =>
      item[filtro].toLowerCase().includes(busqueda.toLowerCase())
    );
  };

  return (
    <div className="contenedor-inventario">
      <h2>Consultar Inventario</h2>

      <div className="filtros">
        <select value={filtro} onChange={(e) => setFiltro(e.target.value)}>
          <option value="placa">Placa</option>
          <option value="usuario">Usuario</option>
          <option value="area">Área</option>
        </select>
        <input
          type="text"
          placeholder={`Buscar por ${filtro}`}
          value={busqueda}
          onChange={manejarCambio}
        />
      </div>

      <table className="tabla-inventario">
        <thead>
          <tr>
            <th>Placa</th>
            <th>Usuario</th>
            <th>Área</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          {filtrarDatos().map((item) => (
            <tr key={item.id}>
              <td>{item.placa}</td>
              <td>{item.usuario}</td>
              <td>{item.area}</td>
              <td>{item.estado}</td>
              <td>
                <button className="btn-editar">Editar</button>
                <button className="btn-eliminar">Eliminar</button>
                <button className="btn-estado">Cambiar estado</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default ConsultarInventario;