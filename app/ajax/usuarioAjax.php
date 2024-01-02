<?php

require_once "../../config/app.php";
require_once "../../autoload.php";
require_once "../views/inc/session_start.php";

use app\controllers\userController;


if (isset($_POST["form_submit"])) {

    $insUsusario = new userController();

}
else {
    session_destroy();
    header("Location: ".APP_URL);
}


?>