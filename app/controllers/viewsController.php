<?php

namespace app\controllers;
use app\models\viewsModel;

class viewsController extends viewsModel {
    public function obtenerVistasControlador (string $vista):string {
        if ($vista != "") {
            $respuesta = $this->obtenerVistasModelo($vista);
        }
        else {
            $respuesta = "seleccionPrincipal";
        }

        return $respuesta;
    }
}

?>