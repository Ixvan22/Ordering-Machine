<?php

require_once "../../config/app.php";
require_once "../../autoload.php";
require_once "../views/inc/session_start.php";

use app\controllers\pedidoController;

$insPedido = new pedidoController();

if (isset($_POST["producto"])) {
    echo $insPedido->anadirProductoControlador();    
}
elseif (isset($_POST["cancelar_pedido"])) {
    echo $insPedido->cancelarPedidoControlador();
}
elseif (isset($_POST["realizar_pedido"])) {
    echo $insPedido->realizarPedidoControlador();
}
elseif (isset($_POST["reset_pedidos"])) {
    echo $insPedido->resetPedidosControlador();
}

?>