<?php if (isset($_SESSION["usuario_tipo"]) && $_SESSION["usuario_tipo"] == "admin") { ?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo APP_URL ?>dashboard/">Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="<?php echo APP_URL ?>dashboard/">Pedidos</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="<?php echo APP_URL ?>addUser/">Crear usuario</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="<?php echo APP_URL ?>delUser/">Eliminar usuario</a>
        </ul>
        </div>
    </div>
</nav>

<?php } ?>



<div class="container py-3 d-flex flex-wrap gap-5">


<?php

use app\controllers\pedidoController;

$insPedido = new pedidoController();

echo $insPedido->listarPedidosControlador();


?>

</div>


<div class="d-flex gap-3" style="position: fixed; bottom: 0; right: 0;">
    <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/pedidoAjax.php" method="POST" >
        <input type="hidden" name="reset_pedidos" value="true"/>
        <button type="submit" class="btn btn-dark" style="border-radius: 0; border-top-left-radius: 5px;">Reiniciar pedidos</button>
    </form>

</div>

