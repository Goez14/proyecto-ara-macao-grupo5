<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ara Macao </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/index.css?v=1">
  <link rel="stylesheet" href="">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="assets/img/logo.png" alt="Logo" width="40" class="me-2">
      <strong>ARA MACAO</strong>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Productos</a></li>
        <li class="nav-item"><a class="nav-link" href="#">¿Quiénes somos?</a></li>
        <?php if (isset($_SESSION['usuario'])): ?>
          <li class="nav-item"><a class="nav-link" href="views/usuario/perfil/perfil.php">Perfil</a></li>
          <li class="nav-item"><a class="btn btn-secondary ms-2" href="auth/logout.php">Cerrar sesión</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="btn btn-warning ms-2" href="auth/login.php">Iniciar sesión</a></li>
          <li class="nav-item"><a class="btn btn-danger ms-2" href="auth/registro.php">Regístrate</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section class="hero py-5 text-center text-white">
    <h1 class="titulo-principal">COLORES VIBRANTES,<br>DULCES DELICIOSOS</h1>
    <p class="lead descripcion-hero">
      Bienvenido a nuestra zona de personalización. Aquí podrás elegir el color de tu gragea, ajustar su tamaño,
      rotarla en tiempo real en un entorno 3D y visualizar cada detalle antes de hacer tu pedido. 
      Crea diseños únicos, tan dulces y vibrantes como tú. ¡Tu imaginación pone el límite, nosotros lo hacemos realidad!
      <br><br>
      Diseña tus grageas como quieras: para regalar, para compartir o simplemente para darte un gusto.
    </p>
    <a href="auth/login.php" class="btn btn-warning btn-lg mt-3">Iniciar sesión</a>
  </div>
</section>

<!-- Carrusel Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<div id="carouselExample" class="carousel slide container my-5" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="assets/img/gomitas.png" class="d-block w-100" alt="Gomitas">
    </div>
    <div class="carousel-item">
      <img src="assets/img/gragea.png" class="d-block w-100" alt="Grageas">
    </div>
    <div class="carousel-item">
      <img src="assets/img/mejores grageas.png" class="d-block w-100" alt="Grageas 3">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>


<!-- Información -->
<section class="container info-card bg-white rounded-4 p-4 shadow-lg mb-5">
  <div class="row">
    <div class="col-md-6 d-grid gap-4">
      <div class="d-flex align-items-start">
        <img src="assets/img/libro.png" width="40" class="me-3">
        <div>
          <h5 class="text-danger">Nuestra historia</h5>
          <p>Ara Macao nació con una chispa de dulzura y el deseo de transformar la experiencia del dulce. Desde una pequeña fábrica en el corazón del barrio 20 de Julio, crecimos con una misión: innovar en cada gragea, personalizar cada color de tu dulce y llevar alegría a cada tienda.</p>
        </div>
      </div>
      <div class="d-flex align-items-start">
        <img src="assets/img/escudo.png" width="40" class="me-3">
        <div>
          <h5 class="text-danger">Calidad garantizada</h5>
          <p>No solo elaboramos dulces: garantizamos calidad en cada detalle. Desde la elección de ingredientes hasta el proceso de producción, cuidamos cada paso para asegurar productos seguros, deliciosos y con estándares de calidad certificados.</p>
        </div>
      </div>
      <div class="d-flex align-items-start">
        <img src="assets/img/ojo.png" width="40" class="me-3">
        <div>
          <h5 class="text-danger">Diseño personalizado</h5>
          <p>Porque cada cliente es único, ofrecemos personalización de color y tamaño. Nuestra nueva plataforma permite a los usuarios visualizar en 3D sus grageas, eligiendo colores y Tamaño para lograr un producto verdaderamente suyo..</p>
        </div>
      </div>
      <div class="d-flex align-items-start">
        <img src="assets/img/color.png" width="40" class="me-3">
        <div>
          <h5 class="text-danger">Colores que siguen tu intuición</h5>
          <p>¿Te gustan los tonos pastel para primavera? ¿Colores intensos para fiestas? ¡Tú decides! En Ara Macao, los colores cambian con las temporadas, pero el control lo tienes tú. Cada estación te ofrece una gama exclusiva, pero puedes personalizar libremente si sientes que tu estilo va más allá. Porque cuando se trata de  color… tu intuición es la mejor guía.</p>
        </div>
      </div>
    </div>
    <div class="col-md-6 text-center">
      <img src="assets/img/gelatinas.png" alt="Grageas" class="img-fluid rounded-4">
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="bg-white text-center py-4 border-top">
  <div class="mb-2">
    <a href="#"><img src="assets/img/facebook.png" width="20" class="mx-2" alt="Facebook"></a>
    <a href="#"><img src="assets/img/twitter.png" width="20" class="mx-2" alt="Twitter"></a>
    <a href="#"><img src="assets/img/instagram.png" width="20" class="mx-2" alt="Instagram"></a>
  </div>
  <small>© 2025 Ara Macao. Todos los derechos reservados.</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
