<div class="container py-3 d-flex flex-wrap gap-5">


<?php

use app\controllers\pedidoController;

$insPedido = new pedidoController();

echo $insPedido->listarPedidosControlador();


?>

</div>


<div class="d-flex gap-3" style="position: fixed; top: 0; right: 0;">
    <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/pedidoAjax.php" method="POST" >
        <input type="hidden" name="reset_pedidos" value="true"/>
        <button type="submit" class="btn btn-dark" style="border-radius: 0; border-bottom-left-radius: 5px;">Reiniciar pedidos</button>
    </form>

</div>

