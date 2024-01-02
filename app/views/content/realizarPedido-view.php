<div class="container py-3 d-flex flex-wrap gap-5">

<?php

use app\controllers\pedidoController;

$insPedido = new pedidoController();

echo $insPedido->listarProductosControlador();

?>

</div>

<div class="d-flex gap-3" style="position: fixed; bottom: 0; right: 0;">
    <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/pedidoAjax.php" method="POST" >
        <input type="hidden" name="cancelar_pedido" value="true"/>
        <button type="submit" class="btn btn-danger" style="border-radius: 0; border-top-left-radius: 5px; border-top-right-radius: 5px;">Cancelar</button>
    </form>

    <form action="" method="POST">
        <input type="hidden" name="continuar_pedido" value="true"/>
        <input type="hidden" name="pedido_tipo" value="<?php echo $url[1] ?>"/>
        <button type="submit" class="btn btn-success" style="border-radius: 0; border-top-left-radius: 5px">Continuar con el pedido</button>
    </form>
</div>

<?php

if (isset($_POST["continuar_pedido"]) && isset($_POST["pedido_tipo"])) {
    
    $_SESSION["datos_pedido"] = [$_SESSION["pedido_actual"], $_POST["pedido_tipo"]];
    unset($_SESSION["pedido_actual"]);

    header("Location: ".APP_URL."procesarPedido/");
}

?>

