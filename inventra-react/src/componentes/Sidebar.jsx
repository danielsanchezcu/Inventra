import React from 'react';
import './Sidebar.css';
import logo from '../imagenes/Logo inventra.png';
import {
  FaHome,
  FaChartBar,
  FaDesktop,
  FaChevronRight,
  FaCog,
  FaFileAlt,
  FaSignOutAlt
} from 'react-icons/fa';

const Sidebar = () => (
  <aside className="lateral">
    <div className="logo">
      <img src={logo} alt="Logo Inventra" />
    </div>

    <nav className="menu">
      <a href="index.php" className="menu-item">
        <FaHome className="icono-sidebar" />
        <span>Inicio</span>
      </a>

      <a href="panelcontrol.php" className="menu-item">
        <FaChartBar className="icono-sidebar" />
        <span>Dashboard</span>
      </a>

      <details className="menu-group">
        <summary>
          <FaDesktop className="icono-sidebar" />
          <span>Dispositivos</span>
          <FaChevronRight className="flecha arrow" />
        </summary>
        <div className="submenu">
          <a href="registro.php">Registrar Equipo</a>
          <a href="asignar.php">Asignar Equipo</a>
          <a href="consultar.php">Consultar Inventario</a>
        </div>
      </details>

      <a href="mantenimiento.php" className="menu-item">
        <FaCog className="icono-sidebar" />
        <span>Mantenimiento</span>
      </a>

      <a href="informes.php" className="menu-item">
        <FaFileAlt className="icono-sidebar" />
        <span>Informes</span>
      </a>

      <a href="inicio.html" className="menu-item">
        <FaSignOutAlt className="icono-sidebar" />
        <span>Salir</span>
      </a>
    </nav>
  </aside>
);

export default Sidebar; 