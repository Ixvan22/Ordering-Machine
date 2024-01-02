<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
<form class="d-flex flex-column gap-3" action="" method="POST" autocomplete="off">
    
    <input type="hidden" name="form_submit" value="login"/>

    <div class="form-group">
        <label for="usuario_usuario">Usuario</label>
        <input type="text" class="form-control" id="usuario_usuario" 
        name="usuario_usuario" aria-describedby="emailHelp" 
        placeholder="Usuario">
    </div>
    <div class="form-group">
        <label for="usuario_clave">Contraseña</label>
        <input type="password" class="form-control" 
        id="usuario_clave" name="usuario_clave" 
        placeholder="Contraseña">
    </div>
    <button type="submit" class="btn btn-primary">Enviar</button>
</form>
</div>

<?php

if (isset($_POST["form_submit"])) {
    $insLogin->loginUsuarioControlador();
}

?>
