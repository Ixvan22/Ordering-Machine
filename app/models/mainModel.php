<?php

namespace app\models;
use \PDO;

if (file_exists(__DIR__."/../../config/server.php")) {
    require_once __DIR__."/../../config/server.php";
}

class mainModel {
    private string $server = DB_SERVER;
    private string $db = DB_NAME;
    private string $user = DB_USER;
    private string $pass = DB_PASS;

    protected function conectar():PDO {
        $conexion = new PDO("mysql:host=".$this->server.";dbname=".$this->db, $this->user, $this->pass);
        $conexion->exec("SET CHARACTER SET utf8");
        return $conexion;
    }

    public function ejecutarConsulta(string $consulta) {
        $sql = $this->conectar()->prepare($consulta);
        $sql->execute();
        return $sql;
    }

    public function limpiarCadena($cadena) {
        $palabras = ["<script>","</script>","<script src","<script type=",
        "SELECT * FROM","SELECT "," SELECT ","DELETE FROM","INSERT INTO","DROP TABLE",
        "DROP DATABASE","TRUNCATE TABLE","SHOW TABLES","SHOW DATABASES","<?php","?>",
        "--","^","<",">","==","=",";","::"];
        
        $cadena = trim($cadena);
        $cadena = stripslashes($cadena);

        foreach($palabras as $palabra) {
            $cadena = str_ireplace($palabra, "", $cadena);
        }

        $cadena = trim($cadena);
        $cadena = stripslashes($cadena);
        
        return $cadena;
    }

    protected function verificarDatos($filtro, $cadena) {
        if (preg_match("/^".$filtro."$/", $cadena)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function guardarDatos($tabla, $datos) {
        $consulta = "INSERT INTO $tabla (";

        $C = 0;
        foreach ($datos as $dato) {
            if ($C >= 1) { $consulta .= ","; }
            $consulta .= $dato["campo_nombre"];
            $C++;
        }

        $consulta .= ") VALUES (";

        $C = 0;
        foreach ($datos as $dato) {
        if ($C >= 1) { $consulta .= ","; }
            $consulta .= $dato["campo_marcador"];
            $C++;
        }

        $consulta .= ")";
        $sql = $this->conectar()->prepare($consulta);
        
        foreach ($datos as $dato) {
            $sql->bindParam($dato["campo_marcador"], $dato["campo_valor"]);
        }

        $sql->execute();
        
        return $sql;
    }


}

?>