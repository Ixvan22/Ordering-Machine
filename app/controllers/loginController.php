<?php

namespace app\controllers;
use app\models\mainModel;

class loginController extends mainModel {

    // Controlador para loguear usuario
    public function loginUsuarioControlador () {
        $usuario = $this->limpiarCadena($_POST["usuario_usuario"]);
        $clave = $this->limpiarCadena($_POST["usuario_clave"]);

        if ($usuario == "" || $clave == "") {
            echo '<script>
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: "Debes rellenar todos los datos",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    </script>';
        }

        // Verificar que el usuario exista
        $check_usuario = $this->ejecutarConsulta("SELECT * FROM usuario WHERE usuario_usuario = '$usuario'");

        if ($check_usuario->rowCount() == 1) {
            $check_usuario = $check_usuario->fetch();

            if ($check_usuario["usuario_usuario"] == $usuario && password_verify($clave, $check_usuario["usuario_clave"])) {
                
                $_SESSION["usuario_usuario"] = $check_usuario["usuario_usuario"];

                if ($check_usuario["usuario_tipo"] == "admin") {
                    $_SESSION["usuario_tipo"] = "admin";
                }
                else {
                    $_SESSION["usuario_tipo"] = "user";
                }

                // Enviar al dashboard de pedidos si los datos son correctos
                if (headers_sent()) {
                    echo "<script>window.location.href = '".APP_URL."dashboard/';</script>";
                }
                else {
                    header("Location: ".APP_URL."dashboard/");
                }
            }
            else {
                echo '<script>
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: "Usuario o clave incorrectos",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    </script>';
            }
        }
        else {
            echo '<script>
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: "Usuario incorrecto",
                        showConfirmButton: false,
                        timer: 1500
                    });
                </script>';
        }

    }

}



?>