<?php

namespace app\models;

class viewsModel {
    protected function obtenerVistasModelo(string $vista):string {
        $listaBlanca = ["realizarPedido", "procesarPedido", "panel"];

        if (in_array($vista, $listaBlanca)) {
            if (is_file("./app/views/content/".$vista."-view.php")) {
                $contenido = "./app/views/content/".$vista."-view.php";
            }
            else {
                $contenido = "404";
            }
        }
        elseif ($vista == "seleccionPrincipal" || $vista == "index") {
            $contenido = "seleccionPrincipal";
        }
        elseif ($vista == "dashboard") {
            $contenido = "dashboard";
        }
        else {
            $contenido = "404";
        }

        return $contenido;
    }
}

?>