<?php

require_once "../../config/app.php";
require_once "../../autoload.php";
require_once "../views/inc/session_start.php";

use app\controllers\userController;



$insUsusario = new userController();

if (isset($_POST["anadir_usuario"])) {
    echo $insUsusario->anadirUsuarioControlador();
}
elseif (isset($_POST["eliminar_usuario"])) {
    echo $insUsusario->eliminarUsuarioControlador();
}


?>