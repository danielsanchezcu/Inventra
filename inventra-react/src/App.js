import React from 'react';
import Sidebar from './componentes/Sidebar';
import Topbar from './componentes/Topbar';
import ConsultarInventario from './componentes/ConsultarInventario';

function App() {
  return (
    <div className="app">
      <Sidebar />
      <Topbar />
      <div className="contenido">
        <ConsultarInventario />
      </div>
    </div>
  );
}

export default App;