<?php
session_start();
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    if (isset($_SESSION['carrito'][$id])) {
        unset($_SESSION['carrito'][$id]);
    }
}
header("Location: ../views/productos/ver_carrito.php");
exit();
