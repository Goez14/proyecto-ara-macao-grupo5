let escena, camara, render, esfera, controls;

function init() {
  const canvasDiv = document.getElementById("grageaCanvas");
  const width = 400;
  const height = 400;

  escena = new THREE.Scene();
  camara = new THREE.PerspectiveCamera(75, width / height, 0.1, 1000);
  camara.position.set(0, 0, 3);

  render = new THREE.WebGLRenderer({ antialias: true, alpha: true });
  render.setSize(width, height);
  canvasDiv.appendChild(render.domElement);

  const luz = new THREE.PointLight(0xffffff, 1);
  luz.position.set(5, 5, 5);
  escena.add(luz);

  const luzAmbiente = new THREE.AmbientLight(0xffffff, 0.5);
  escena.add(luzAmbiente);

  const geometria = new THREE.SphereGeometry(1, 32, 32);
  const material = new THREE.MeshStandardMaterial({ color: 0xff0000 });
  esfera = new THREE.Mesh(geometria, material);
  escena.add(esfera);

  controls = new THREE.OrbitControls(camara, render.domElement);
  controls.enableDamping = true;

  animate();
}

function animate() {
  requestAnimationFrame(animate);
  controls.update();
  render.render(escena, camara);
}

function cambiarColor() {
  const color = document.getElementById("color").value;
  esfera.material.color.set(color);
}

function cambiarTamano() {
  const tamano = parseFloat(document.getElementById("tamano").value);
  esfera.scale.set(tamano, tamano, tamano);
}
function guardarCambios() {
  const color = document.getElementById("color").value;
  const tamano = parseFloat(document.getElementById("tamano").value);

  fetch("../../controllers/guardar_personalizacion.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `color=${encodeURIComponent(color)}&tamano=${tamano}`,
  })
    .then((res) => res.text())
    .then((mensaje) => {
      document.getElementById("mensaje").innerText = mensaje;
    })
    .catch((err) => {
      document.getElementById("mensaje").innerText = "Error al guardar.";
    });
}

window.addEventListener("DOMContentLoaded", init);

fetch("../../controllers/obtener_personalizacion.php")
  .then((response) => response.json())
  .then((data) => {
    if (data.error) {
      console.log(data.error);
      return;
    }

    document.getElementById("color").value = data.color;
    document.getElementById("tamano").value = data.tamano;

    if (esfera) {
      esfera.material.color.set(data.color);
      esfera.scale.set(data.tamano, data.tamano, data.tamano);
    }
  })
  .catch((error) =>
    console.error("Error al obtener la personalización:", error)
  );
