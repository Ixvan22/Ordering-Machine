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
            <a class="nav-link active" href="<?php echo APP_URL ?>addUser/">Crear usuario</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="<?php echo APP_URL ?>delUser/">Eliminar usuario</a>
        </ul>
        </div>
    </div>
</nav>


<form class="FormularioAjax m-5 d-flex flex-column gap-3" method="POST" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php">
    <div class="form-group">
        <label for="usuario_usuario">Usuario</label>
        <input type="text" class="form-control" name="usuario_usuario" id="usuario_usuario" required>
    </div>
    <div class="form-group">
    <label for="usuario_clave1">Clave</label>
        <input type="password" class="form-control" name="usuario_clave1" id="usuario_clave1" required>
    </div>
    <div class="form-group">
        <label for="usuario_clave2">Repetir clave</label>
        <input type="password" class="form-control" name="usuario_clave2" id="usuario_clave2" required>
    </div>
    <div class="form-group">
        <label for="usuario_tipo">Tipo</label>
        <select class="form-control" name="usuario_tipo" id="usuario_tipo" required>
            <option value="null" selected="selected"></option>  
            <option value="user">User</option>    
            <option value="admin">Admin</option>
        </select>
    </div>

    <input type="hidden" name="anadir_usuario"/>

    <button type="submit" class="btn btn-primary mb-2">Añadir usuario</button>
</form>

<?php

}
else {
    header("Location: ".APP_URL."panel/");
}

?>