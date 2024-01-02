<?php

namespace app\controllers;
use app\models\mainModel;

class pedidoController extends mainModel {
    
    // Controlador para listar los productos
    public function listarProductosControlador () {

        $consulta = "SELECT * FROM producto ORDER BY producto_nombre ASC";

        $consulta = $this->ejecutarConsulta($consulta);

        $contenido = "";

        if ($consulta->rowCount() > 0) {
            while ($producto = $consulta->fetch()) {
                $contenido .= '
                            <form class="FormularioAjax" action="'.APP_URL.'app/ajax/pedidoAjax.php" method="POST">
                                <div class="card" style="width: 18rem;">
                                    <img class="card-img-top" src="'.APP_URL."app/views/img/productos/".$producto["producto_img"].'" alt="'.$producto["producto_nombre"].'">
                                    <div class="card-body">
                                        <h5 class="card-title">'.$producto["producto_nombre"].'</h5>
                                        <p class="card-text">'.$producto["producto_precio"].' €</p>

                                        <input type="hidden" name="producto" value="'.$producto["producto_id"].'"/>
                                        <button type="submit" class="btn btn-primary"">Añadir producto</button>
                                    </div>
                                </div>
                            </form>
                ';
            }
        }
        else {
            $contenido .= '
                    <div class="alert alert-warning" role="alert">
                        !No hay productos en este momento!
                    </div>
            ';
        }

        return $contenido;
    }

    // Controlador para anadir al carrito
    public function anadirProductoControlador () {
        
        if ($this->comprobarProductoControlador($_POST["producto"])) {
            $_SESSION["pedido_actual"][] = $_POST["producto"];
    
            $alerta = [
                "tipo" => "simple",
                "icono" => "success",
                "titulo" => "Producto añadido"
            ];
        
            return json_encode($alerta);
        }
        else {
    
            $alerta = [
                "tipo" => "simple",
                "icono" => "error",
                "titulo" => "No se ha podido añadir el producto"
            ];
        
            return json_encode($alerta);
        }
    }

    // Controlador para comprobar que existe el producto
    public function comprobarProductoControlador($id):bool {
        $producto = $this->limpiarCadena($id);

        $consulta = "SELECT producto_id FROM producto WHERE producto_id = $producto";
        
        $resultado = $this->ejecutarConsulta($consulta);

        if ($resultado->rowCount() == 1) {
            return true;
        }
        else {
            return false;
        }
        
    }

    // Controlador para cancelar pedido
    public function cancelarPedidoControlador() {
        if (isset($_SESSION["pedido_actual"])) {
            unset($_SESSION["pedido_actual"]);
        }

        $alerta = [
            "tipo" => "confirmar",
            "icono" => "warning",
            "titulo" => "¿Deseas cancelar el pedido actual?",
            "url" => APP_URL
        ];
    
        return json_encode($alerta);
    }

    // Controlador para listar carrito
    public function listarCarritoControlador() {
        
        $contenido = "";

        if ($_SESSION["datos_pedido"][0] !== null && !empty($_SESSION["datos_pedido"][0])) {
            $precioTotal = 0;
            $numProducto = 0;
        
            foreach ($_SESSION["datos_pedido"][0] as $producto) {
                $producto = $this->limpiarCadena($producto);
                $consulta = "SELECT * FROM producto WHERE producto_id = $producto";
    
                $consulta = $this->ejecutarConsulta($consulta);
    
                if ($consulta->rowCount() == 1) {
                    $consulta = $consulta->fetch();
                    $contenido .= '
                        <div class="card m-5">
                            <div class="card-body">
                                <h5 class="card-title">'.$consulta["producto_nombre"].'</h5>
                                <p class="card-text">'.$consulta["producto_precio"].' €</p>
                                <a href="'.APP_URL.'procesarPedido/'.$numProducto.'/" class="btn btn-primary">Eliminar</a>
                            </div>
                        </div>
                    ';
    
                    $precioTotal += $consulta["producto_precio"];
                }
                $numProducto++;
            }

            $contenido .= '<h4 class="m-5">Total: '.$precioTotal.' €</h4>';

            $contenido .= '
                    <form class="FormularioAjax d-grid gap-2" action="'.APP_URL.'app/ajax/pedidoAjax.php" method="POST">
                        <input type="hidden" name="realizar_pedido" value="true"/>
                        <button type="submit" class="btn btn-success btn-lg btn-block" style="border-radius: 0;">Realizar pedido</button>
                    </form>
        
            ';

        }
        else {
            $contenido .= '
                <div class="alert alert-danger" role="alert">
                    No hay ningún producto en el carrito
                </div>
                <div class="d-grid gap-2">
                    <a href="'.APP_URL.'" class="btn btn-dark btn-lg btn-block" style="border-radius: 0;">Volver al inicio</a>
                </div>
            ';
        }

        return $contenido;


    }

    // Controlador para procesar pedido
    public function realizarPedidoControlador () {

        // Establece un numero de pedido

        $pedidoId = mt_rand(1, 999999);

        $consultaId = "SELECT pedido_id from pedido where pedido_id = $pedidoId";
        $pedidoRepetido = $this->ejecutarConsulta($consultaId);

        while ($pedidoRepetido->rowCount() > 0) {
            $pedidoId = mt_rand(1, 999999);

            $consultaId = "SELECT pedido_id from pedido where pedido_id = $pedidoId";
            $pedidoRepetido = $this->ejecutarConsulta($consultaId);
        }

        $insercionPedido = [
            [
                "campo_nombre" => "pedido_id",
                "campo_marcador" => ":PedidoId",
                "campo_valor" => $pedidoId
            ],
            [
                "campo_nombre" => "pedido_tipo",
                "campo_marcador" => ":PedidoTipo",
                "campo_valor" => $_SESSION["datos_pedido"][1]
            ]
        ];

        $insercionPedido = $this->guardarDatos("pedido", $insercionPedido);

        if ($insercionPedido -> rowCount() == 0) {
            
            $alerta = [
                "tipo" => "recargar",
                "icono" => "error",
                "titulo" => "Ha ocurrido un error al realizar el pedido",
                "url" => APP_URL
            ];
            
            return json_encode($alerta);
        }

        $precioTotal = 0;

        $pedidoProductos = [];
        
        foreach ($_SESSION["datos_pedido"][0] as $producto) {
            $producto = $this->limpiarCadena($producto);
            $consulta = "SELECT * FROM producto WHERE producto_id = $producto";

            $consulta = $this->ejecutarConsulta($consulta);

            if ($consulta->rowCount() == 1) {
                $consulta = $consulta->fetch();

                $consultaProductoRepetido = "SELECT * FROM pedido_producto where pedido_id = $pedidoId
                AND producto_id = ".$consulta["producto_id"];
                $consultaProductoRepetido = $this->ejecutarConsulta($consultaProductoRepetido);

                if ($consultaProductoRepetido->rowCount() > 0) {
                    $consultaProductoRepetido = $consultaProductoRepetido->fetch();
                    $cantidad = $consultaProductoRepetido["producto_cantidad"] + 1;
                    $insercionPedidoProducto = "UPDATE pedido_producto SET producto_cantidad = $cantidad WHERE pedido_id = $pedidoId
                    AND producto_id = ".$consulta["producto_id"];

                    $insercionPedidoProducto = $this->ejecutarConsulta($insercionPedidoProducto);
                }
                else {
                    $pedidoProductos[] = [
                        "campo_nombre" => "pedido_id",
                        "campo_marcador" => ":PedidoId",
                        "campo_valor" => $pedidoId
                    ];
                    $pedidoProductos[] = [
                        "campo_nombre" => "producto_id",
                        "campo_marcador" => ":ProductoId",
                        "campo_valor" => $consulta["producto_id"]
                    ];
    
                    $insercionPedidoProducto = $this->guardarDatos("pedido_producto", $pedidoProductos);
                }


                if ($insercionPedidoProducto->rowCount() == 0) {
                    $alerta = [
                        "tipo" => "recargar",
                        "icono" => "error",
                        "titulo" => "Ha ocurrido un error al realizar el pedido",
                        "url" => APP_URL
                    ];
                    
                    return json_encode($alerta);
                }
                
                $precioTotal += $consulta["producto_precio"];
            }

            $pedidoProductos = [];
        }

        $consultaUpdate = "UPDATE pedido SET pedido_total = $precioTotal WHERE pedido_id = $pedidoId";
        $consultaUpdate = $this->ejecutarConsulta($consultaUpdate);

        if ($consultaUpdate->rowCount() == 0) {
            $alerta = [
                "tipo" => "recargar",
                "icono" => "warning",
                "titulo" => "Se ha realizado el pedido, pero no podemos estimar el precio total",
                "url" => APP_URL
            ];
            
            return json_encode($alerta);
        }

        session_destroy();

        $alerta = [
            "tipo" => "recargar",
            "icono" => "success",
            "titulo" => "Se ha realizado el pedido con éxito. Número: ".$pedidoId,
            "url" => APP_URL
        ];
        
        return json_encode($alerta);

    }

    // Controlador para listar pedidos en el dashboard
    public function listarPedidosControlador () {
        $contenido = "";
        $precioTotal = 0;

        $consultaPedido = "SELECT * FROM pedido ORDER BY pedido_fecha";
        $consultaPedido = $this->ejecutarConsulta($consultaPedido);

        if ($consultaPedido->rowCount() > 0) {

            while($pedido = $consultaPedido->fetch()) {

                $consultaPedidoExacto = "SELECT * FROM pedido_producto WHERE pedido_id = ".$pedido["pedido_id"];
                $consultaPedidoExacto = $this->ejecutarConsulta($consultaPedidoExacto);

                if ($consultaPedidoExacto->rowCount() > 0) {
                    $pedidoTipo = $pedido["pedido_tipo"] == 1 ? "Tomar" : "Llevar";

                    $contenido .= '
                            <div class="card" style="width: 18rem;">
                                <div class="card-header">
                                Nº: '.$pedido["pedido_id"].' -> '.$pedidoTipo.'
                                </div>
                                <ul class="list-group list-group-flush">
                    ';

                    while ($pedidoExacto = $consultaPedidoExacto->fetch()) {

                        $consultaProducto = "SELECT * FROM producto WHERE producto_id = ".$pedidoExacto["producto_id"];
                        $consultaProducto = $this->ejecutarConsulta($consultaProducto);

                        if ($consultaProducto->rowCount() == 1) {

                            $consultaProducto = $consultaProducto->fetch();

                            for ($i = 0; $i < $pedidoExacto["producto_cantidad"]; $i++) {

                                $precioTotal += $consultaProducto["producto_precio"];

                                $contenido .= '
                                <li class="list-group-item">'.$consultaProducto["producto_nombre"].' | '.$consultaProducto["producto_precio"].'</li>
                                ';
                            }

                        }
                    }
                    $contenido .= '
                        <li class="list-group-item">Total: '.$precioTotal.' €</li>
                    </ul></div>';
                    $precioTotal = 0;
                }


            }

        }
        else {
            $contenido .= '
            <div class="alert alert-warning" role="alert">
                !No hay pedidos en este momento!
            </div>
            ';
        }

        return $contenido;



    }

    // Controlador para eliminar los pedidos
    public function resetPedidosControlador() {
        $eliminar = "DELETE FROM pedido";
        $eliminar = $this->ejecutarConsulta($eliminar);

        if ($eliminar->rowCount() > 0) {
            $alerta = [
                "tipo" => "recargar",
                "icono" => "success",
                "titulo" => "Se han eliminado los pedidos",
                "url" => APP_URL."dashboard/"
            ];
        }
        else {
            $alerta = [
                "tipo" => "simple",
                "icono" => "error",
                "titulo" => "Error al eliminar los pedidos"
            ];
        }

        return json_encode($alerta);
    }
}

?>