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
            <a class="nav-link" aria-current="page" href="<?php echo APP_URL ?>dashboard/">Pedidos</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="<?php echo APP_URL ?>addUser/">Crear usuario</a>
            </li>
            <li class="nav-item">
            <a class="nav-link active" href="<?php echo APP_URL ?>delUser/">Eliminar usuario</a>
        </ul>
        </div>
    </div>
</nav>
    
<?php

}
else {
    header("Location: ".APP_URL."panel/");
}

echo $insUsuarios->listarUsuariosControlador();

?>
