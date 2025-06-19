<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Personalización de Gragea 3D</title>
  <link rel="stylesheet" href="../../assets/css/personalizacion.css" />
  <script src="https://cdn.jsdelivr.net/npm/three@0.148.0/build/three.min.js"></script>
  <script src="../../assets/js/OrbitControls.js"></script>
  <script src="../../assets/js/personalizacion.js" defer></script>
</head>
<body>
  <div class="contenedor">
    <h1>Personaliza tu Gragea</h1>
    <div id="grageaCanvas" style="margin: 0 auto; width: 400px; height: 400px;"></div>
    <div class="controles">
      <label for="color">Color:</label>
      <input type="color" id="color" value="#ff0000" onchange="cambiarColor()" />
      <label for="tamano">Tamaño:</label>
      <select id="tamano" onchange="cambiarTamano()">
        <option value="0.5">Pequeño</option>
        <option value="1" selected>Mediano</option>
        <option value="1.5">Grande</option>
      </select>

      <button onclick="guardarCambios()">Guardar cambios</button>
       <div id="mensaje"></div>

    </div>
  </div>
</body>
</html>
