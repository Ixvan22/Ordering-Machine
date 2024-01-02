<?php

require_once "./config/app.php";
require_once "./autoload.php";


if (isset($_GET["views"])) {
    $url = explode("/", $_GET["views"]);
}
else {
    $url = ["seleccionPrincipal"];
}

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "./app/views/inc/head.php"; ?>
    
</head>
<body>
    <?php
    use app\controllers\viewsController;
    use app\controllers\loginController;
    use app\controllers\userController;

    require_once "./app/views/inc/session_start.php";

    $insUsuarios = new userController();
    $insLogin = new loginController();

    $viewsController = new viewsController();
    $vista = $viewsController->obtenerVistasControlador($url[0]);

    if ($vista == "seleccionPrincipal" || $vista == "404") {
        require_once "./app/views/content/".$vista."-view.php";
    }
    elseif ($vista == "dashboard") {

        // No permitir accesos de usuarios que no han iniciado sesion
        if (isset($_SESSION["usuario_usuario"])) {
            require_once "./app/views/content/".$vista."-view.php";
        }
        else {
            session_destroy();
            header("Location: ".APP_URL."panel/");
        }
    }
    else {
        require_once $vista;
    }

    ?>

    <?php require_once "./app/views/inc/script.php"; ?>
</body>
</html>