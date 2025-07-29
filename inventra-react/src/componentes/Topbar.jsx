import React from 'react';
import './Topbar.css'; 
import { FiSearch, FiBell, FiUser } from 'react-icons/fi';

const Topbar = () => {
  return (
    <div className="barrasuperior">
      <div className="busqueda">
        <FiSearch className="icono-busqueda" />
        <input type="text" placeholder="Buscar..." />
      </div>

      <div className="acciones">
        <FiBell className="icono" />
        <FiUser className="icono" />
      </div>
    </div>
  );
};

export default Topbar;