<?php

use app\controllers\pedidoController;

if (isset($_SESSION["datos_pedido"])) {
        
    if (isset($url[1]) && $url[1] != "") {
        array_splice($_SESSION["datos_pedido"][0], $url[1], 1);
    }

    $insPedido = new pedidoController();
    
    echo $insPedido->listarCarritoControlador();
        
}
else {
    header("Location: ".APP_URL);
}

?>
