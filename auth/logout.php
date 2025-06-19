<?php
session_start();
session_unset();
session_destroy();
header('Location: ../index.php'); // redirige al inicio raíz
exit();
