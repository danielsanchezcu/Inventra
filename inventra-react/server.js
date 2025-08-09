// server.js
const express = require('express');
const mysql = require('mysql2');
const cors = require('cors');
const app = express();

// Configuración de CORS y middleware
app.use(cors());
app.use(express.json());

// Conexión a MySQL (XAMPP)
const db = mysql.createConnection({
  host: 'localhost',
  user: 'root', // Usuario XAMPP por defecto
  password: '', // Contraseña XAMPP (vacía por defecto)
  database: 'inventrabd' // Nombre de tu base de datos
});

// Ruta para obtener asignaciones de la tabla asignacion_equipo
app.get('/api/asignaciones', (req, res) => {
  const { busqueda, departamento, tipo } = req.query;
  
  let query = `
    SELECT ae.*, e.tipo AS tipo_equipo 
    FROM asignacion_equipo ae 
    JOIN registro_equipos e ON ae.placa_inventario = e.placa_inventario 
    WHERE 1=1`;
  
  const params = [];
  
  if (busqueda) {
    query += ` AND (ae.nombres LIKE ? OR ae.apellidos LIKE ? OR ae.identificacion LIKE ?)`;
    params.push(`%${busqueda}%`, `%${busqueda}%`, `%${busqueda}%`);
  }
  
  if (departamento && departamento !== 'Todos') {
    query += ` AND ae.area = ?`;
    params.push(departamento);
  }
  
  if (tipo && tipo !== 'Todos') {
    query += ` AND e.tipo_equipo = ?`;
    params.push(tipo);
  }
  
  db.query(query, params, (err, results) => {
    if (err) throw err;
    res.json(results);
  });
});

// Iniciar servidor
app.listen(3001, () => {
  console.log('Servidor corriendo en http://localhost:3001');
});
