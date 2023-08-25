<?php
include '../config/conexion.php';
session_start();
if (empty($_POST)) {
    echo 'error_post';
    exit();
}
function login(){
    $usuario=$_POST['usuario'];
    $password=$_POST['pass'];
    $pass_sn=$password;
	$password=sha1($password);
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT usuario,password FROM Catalogo_usuario WHERE usuario = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($usuario));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	if($data == null)	{
		Database::disconnect(); 
		return 0;
	}
	if($usuario != $data['usuario']){
		Database::disconnect();
		return 0;
	}
	if($data['password'] != $password){
		Database::disconnect();
		return 2;
	}
    $_SESSION['usuario'] = $data['usuario'];
	/*$_SESSION['catalogo_id_usuario'] = $data['id'];
	$_SESSION['usuario'] = $data['usuario'];
	$_SESSION['nombres']=$data['nombre_completo'];*/
	Database::disconnect();
	return true;
}

function nuevo_usuario(){
    $usuario=$_POST['usuario'];
    $password=sha1($_POST['pass']);

    if(repetido("Catalogo_usuario", "usuario", $usuario) == false){
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO Catalogo_usuario (usuario, password) VALUES (?,?);";
        $q = $pdo->prepare($sql);
        try {
            $q->execute(array($usuario,$password));
            Database::disconnect();
            return true;
        } catch (PDOException $e) {
            Database::disconnect();
            return "Error: " . $e;
        }
    }else return false;
}

function data_output($columns, $data){
    $out = array();
    for ($i = 0, $ien = count($data); $i < $ien; $i++) {
        $row = array();
        for ($j = 0, $jen = count($columns); $j < $jen; $j++) {
            $column = $columns[$j];
            if (isset($column['formatter'])) {
                $row[$column['dt']] = $column['formatter']($data[$i][$column['db']], $data[$i]);
            } else {
                $row[$column['dt']] = $data[$i][$columns[$j]['db']];
            }
        }
        $out[] = $row;
    }
    return $out;
}

function normaliza($cadena){
    $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞ
ßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
    $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuy
bsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
    $cadena = utf8_decode($cadena);
    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
    $cadena = strtolower($cadena);
    return utf8_encode($cadena);
}
function conver($var){
    $no_permitidas = array(">", "<", '"', "|", "°", "¬", "!", "#", "$", "%", "&", "=", "?", "¡", "'", "¿", "¨", "*", "~", "{", "[", "^", "]", "}", "`", "_", "'", "´", "’", "‘", "á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹");
    $permitidas = array("", "", '', "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "Ñ", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E");
    $texto = trim(strtoupper(str_replace($no_permitidas, $permitidas, $var)));
    $texto = iconv("UTF-8", "ISO-8859-1//IGNORE", $texto);
    return $texto;
}
function nombreempl(){
    $control = $_POST['control'];
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT CONCAT(nombre,' ',ApellidoPaterno,' ',ApellidoMaterno) as nombre_empleado  FROM empleado WHERE NumeroControl=?";
    $q = $pdo->prepare($sql);
    try {
        $q->execute(array($control));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        if ($data == null) {
            Database::disconnect();
            return false;
        }
        Database::disconnect();
        return $data['nombre_empleado'];
    } catch (PDOException $e) {
        Database::disconnect();
        return false;
        //return "Error: " . $e;
    }
}

function datos_vehiculo($idvehiculo){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
    $sql = "SELECT CONCAT(marca,' ', modelo) AS marca, placas, kilometraje, tipo_combustible FROM vehiculo WHERE num_unidad = ?";
    $q = $pdo->prepare($sql);
    try {
        $q->execute(array($idvehiculo));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        if ($data == null) {
            Database::disconnect();
            return false;
        }
        Database::disconnect();
        $datos = [$data['marca'], $data['placas'], $data['kilometraje'], $data['tipo_combustible']]; 
        return $datos;
    } catch (PDOException $e) {
        Database::disconnect();
        return false;
            //return "Error: " . $e;
    }
}

function mostrar_vehiculos(){
    $columns = array(
        array('db' => 'id_vehiculo', 'dt' => 0),
        array('db' => 'num_unidad', 'dt' => 1),
        array('db' => 'marca', 'dt' => 2),
        array('db' => 'modelo', 'dt' => 3),
        array('db' => 'tipo', 'dt' => 4),
        array('db' => 'placas', 'dt' => 5),
        array('db' => 'NoSerie', 'dt' => 6),
        array('db' => 'tipo_combustible', 'dt' => 7),
        array('db' => 'kilometraje', 'dt' => 8),
        array('db' => 'transmision', 'dt' => 9),
        /*array('db' => 'auto_direccion', 'dt' => 10),
        array('db' => 'direccion', 'dt' => 11),
        array('db' => 'estado', 'dt' => 13),
        array('db' => 'logo', 'dt' => 14),*/
        array('db' => 'ubicacion', 'dt' => 10),
        array('db' => 'resguardante', 'dt' => 11)
    );
    $salida = array();
    $pdo = new PDO('mysql:host=localhost;dbname=Bitacora', 'root', 'DIFinformatica.03', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT * FROM vehiculo";
    $q = $pdo->prepare($query);
    try {
        $q->execute();
        $data = $q->fetchAll(PDO::FETCH_ASSOC);
        
        $salida['data'] = data_output($columns, $data);
        $json = json_encode($salida, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
        $pdo = null;
        return $json;
    } catch (PDOException $e) {
        $pdo = null;
        return "Error: " . $e;
    }
}

function get_vehiculo($id){
    //$id = $_POST['id'];
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT * FROM vehiculo WHERE id_vehiculo=?";
    $q = $pdo->prepare($query);
    try {
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        return json_encode($data);
    } catch (PDOException $e) {
        Database::disconnect();
        return "Error: " . $e;
    }
}

function num_bitacoras(){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT MAX(id_bitacora) AS bitacoras FROM bitacora;";
    $q = $pdo->prepare($sql);
    try {
        $q->execute(array());
        $data = $q->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        if($data["bitacoras"])
            return $data["bitacoras"]+1;
        else return 1;
    } catch (PDOException $e) {
        Database::disconnect();
        return "Error: " . $e;
    }    
}

function repetido($tabla, $columna, $lugar){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM $tabla WHERE $columna = ?;";
    $q = $pdo->prepare($sql);
    try {
        $q->execute(array($lugar));
        $res = $q->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        if($res) return true;
        else return false;
    } catch (PDOException $e) {
        Database::disconnect();
        return "Error: " . $e;
    }
}

function editar_km(){
    $unidad = $_POST['unidad'];
    $km = $_POST['km'];
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE vehiculo SET kilometraje=? where num_unidad = ?;";
    $q = $pdo->prepare($sql);
    try {
        $q->execute(array($km, $unidad));
        //$res = $q->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        return true;
    } catch (PDOException $e) {
        Database::disconnect();
        return "Error: " . $e;
    }
}

function guardar_bitacora(){

    $empleado = null;
    $idVehiculo = null;
    $fechalDel = null;
    $fechalAl = null;
    $combustible = null;
    $sig = null;
    $fechaCarga = null;
    $monto = null;
    $folio = null;

    if (isset($_POST['sig'])) {
        $sig = $_POST['sig'];
    }
    if (isset($_POST['numero_control'])) {
        $empleado = $_POST['numero_control'];
    }
    if (isset($_POST['idVehiculo'])) {
        $idVehiculo = $_POST['idVehiculo'];
    }
    if (isset($_POST['FechaDel'])) {
        $fechalDel = date('Y-m-d', strtotime($_POST['FechaDel']));
    }
    if (isset($_POST['FechaAl'])) {
        $fechalAl = date('Y-m-d', strtotime($_POST['FechaAl']));
    }
    if (isset($_POST['tipo_combustible']) && !empty($_POST['tipo_combustible'])) {
        $combustible = $_POST['tipo_combustible'];
    }
    if (isset($_POST['fecha_carga']) && !empty($_POST['fecha_carga'])) {
        $fechaCarga = date('Y-m-d', strtotime($_POST['fecha_carga']));
    }
    if (isset($_POST['monto']) && !empty($_POST['monto'])) {
        $monto = $_POST['monto'];
    }
    if (isset($_POST['folio']) && !empty($_POST['folio'])) {
        $folio = $_POST['folio'];
    }

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Construir la consulta SQL y el array de parámetros dinámicamente
    $sql = "INSERT INTO bitacora (id_bitacora, operador, NoUnidad, periodo_de, periodo_al";
    $params = array($sig, $empleado, $idVehiculo, $fechalDel, $fechalAl);

    if (!empty($combustible)) {
        $sql .= ", combustible";
        $params[] = $combustible;
    }
    if (!empty($fechaCarga)) {
        $sql .= ", fecha_carga";
        $params[] = $fechaCarga;
    }
    if (!empty($folio)) {
        $sql .= ", folio";
        $params[] = $folio;
    }
    if (!empty($monto)) {
        $sql .= ", monto";
        $params[] = $monto;
    }
    date_default_timezone_set('America/Mexico_City');
    $params[] = date('Y-m-d',  strtotime(date('d-m-Y')));
    $sql .= ",fecha_recibido) VALUES (?, ?, ?, ?";

    for ($i = 0; $i < count($params) - 4; $i++) {
        $sql .= ", ?";
    }

    $sql .= ");";

    $q = $pdo->prepare($sql);

    try {
        $q->execute($params);
        Database::disconnect();
        return true;
    } catch (PDOException $e) {
        Database::disconnect();
        return "Error: " . $e;
    }
}

function editar_bitacora(){
    $id = $_POST['id_bitacora_form'];
    $empleado = null;
    $idVehiculo = null;
    $fechalDel = null;
    $fechalAl = null;
    $combustible = null;
    $fechaCarga = null;
    $monto = null;
    $folio = null;

    if (isset($_POST['numero_control_e'])) {
        $empleado = $_POST['numero_control_e'];
        //echo $empleado;
    }
    if (isset($_POST['idVehiculo_e'])) {
        $idVehiculo = $_POST['idVehiculo_e'];
    }
    if (isset($_POST['FechaDel_e'])) {
        $fechalDel = date('Y-m-d', strtotime($_POST['FechaDel_e']));
    }
    if (isset($_POST['FechaAl_e'])) {
        $fechalAl = date('Y-m-d', strtotime($_POST['FechaAl_e']));
    }
    if (isset($_POST['tipo_combustible_e']) && !empty($_POST['tipo_combustible_e'])) {
        $combustible = $_POST['tipo_combustible_e'];
    }
    if (isset($_POST['fecha_carga_e']) && !empty($_POST['fecha_carga_e'])) {
        $fechaCarga = date('Y-m-d', strtotime($_POST['fecha_carga_e']));
    }
    if (isset($_POST['monto_e']) && !empty($_POST['monto_e'])) {
        $monto = $_POST['monto_e'];
    }
    if (isset($_POST['folio_e']) && !empty($_POST['folio_e'])) {
        $folio = $_POST['folio_e'];
    }

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Construir la consulta SQL y el array de parámetros dinámicamente
    $sql = "UPDATE bitacora SET operador=?, NoUnidad=?, periodo_de=?, periodo_al=?";
    $params = array($empleado, $idVehiculo, $fechalDel, $fechalAl);

        $sql .= ", combustible=?";
        $params[] = $combustible;

        $sql .= ", fecha_carga=?";
        $params[] = $fechaCarga;

        $sql .= ", folio=?";
        $params[] = $folio;

        $sql .= ", monto=?";
        $params[] = $monto;

    $sql .=" WHERE id_bitacora = $id";
    
    $q = $pdo->prepare($sql);

    try {
        $q->execute($params);
        Database::disconnect();
        return true;
    } catch (PDOException $e) {
        Database::disconnect();
        return "Error: " . $e;
    }
}

function guardar_recorrido(){
    $vacio = null;
    $dia = null;
    $k_i = null;
    $k_f = null;
    $salida = null;
    $lista_rec = null;

    if (!isset($_POST['vacio'])) {
        if (isset($_POST['dia_semana'])) {
            $dia = $_POST['dia_semana'];
        }
        if (isset($_POST['salida'])) {
            $salida = $_POST['salida'];
        }
        if (isset($_POST['listaRecorridos'])) {
            $lista_rec = $_POST['listaRecorridos'];
        }
        if (isset($_POST['km_inicial'])) {
            $k_i = $_POST['km_inicial'];
        }
        if (isset($_POST['km_final'])) {
            $k_f = $_POST['km_final'];
        }
        
        $vacio = 0;
        $id_bitacora = $_POST['id_bitacora'];
        
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO recorrido (dia_semana, salida, recorrido, km_inicial, km_final, vacio, bitacora) VALUES (?, ?, ?, ?, ?, ?, ?);";
        $params = array($dia, $salida, $lista_rec, $k_i, $k_f, $vacio, $id_bitacora);
    
        $q = $pdo->prepare($sql);
    
        try {
            $q->execute($params);
            Database::disconnect();
            return true;
        } catch (PDOException $e) {
            Database::disconnect();
            return "Error: " . $e;
        }
    }else{
        $vacio = 1;
        $dia = $_POST['dia_semana'];
        $id_bitacora = $_POST['id_bitacora'];
        
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        $sql = "INSERT INTO recorrido (dia_semana, vacio, bitacora) VALUES (?,?,?);";
        $params = array($dia, $vacio, $id_bitacora);
    
        $q = $pdo->prepare($sql);
    
        try {
            $q->execute($params);
            Database::disconnect();
            return true;
        } catch (PDOException $e) {
            Database::disconnect();
            return "Error: " . $e;
        }
    }
}

function editar_recorrido(){
    $id_recorrido = $_POST['id_recorrido'];
    $vacio = null;
    $dia = null;
    $k_i = null;
    $k_f = null;
    $salida = null;
    $lista_rec = null;

    if (!isset($_POST['vacio_e'])) {
        if (isset($_POST['dia_semana_e'])) {
            $dia = $_POST['dia_semana_e'];
        }
        if (isset($_POST['salida_e'])) {
            $salida = $_POST['salida_e'];
        }
        if (isset($_POST['listaRecorridos_e'])) {
            $lista_rec = $_POST['listaRecorridos_e'];
        }
        if (isset($_POST['km_inicial_e'])) {
            $k_i = $_POST['km_inicial_e'];
        }
        if (isset($_POST['km_final_e'])) {
            $k_f = $_POST['km_final_e'];
        }
    
        try {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $vacio = 0;
            $sql = "UPDATE recorrido SET dia_semana=?, salida=?, recorrido=?, km_inicial=?, km_final=?, vacio=? WHERE id_recorrido=$id_recorrido;";
            $params = array($dia, $salida, $lista_rec, $k_i, $k_f, $vacio);
        
            $q = $pdo->prepare($sql);
            $q->execute($params);
            Database::disconnect();
            return true;
        } catch (PDOException $e) {
            Database::disconnect();
            return "Error: " . $e;
        }
    }else{
        $dia = $_POST['dia_semana_e'];
        $vacio = 1;

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        try {
            $sql = "UPDATE recorrido SET dia_semana=?, salida=NULL, recorrido=NULL, km_inicial=NULL, km_final=NULL, vacio=? WHERE id_recorrido=$id_recorrido;";
            $params = array($dia, $vacio);
        
            $q = $pdo->prepare($sql);
            $q->execute($params);
            Database::disconnect();
            return true;
        } catch (PDOException $e) {
            Database::disconnect();
            return "Error: " . $e;
        }
    }
}

function nuevo_auto(){
    $unidad = $_POST['num_unidad'];
    $marca = strtoupper($_POST['marca']);
    $modelo = strtoupper($_POST['modelo']);
    $placas = strtoupper($_POST['placas']);
    $combustible = strtoupper($_POST['tipo_combustible']);
    $kilometraje = $_POST['kilometraje'];
    $sql_ = array($unidad, $marca, $modelo, $placas, $combustible, $kilometraje);

    $aux = repetido("vehiculo", "num_unidad", $unidad);
    if($aux == false){
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO vehiculo (num_unidad, marca, modelo, placas, tipo_combustible, kilometraje) VALUES (?,?,?,?,?,?);";
        $q = $pdo->prepare($sql);
        try {
            $q->execute($sql_);
            Database::disconnect();
            return true;
        } catch (PDOException $e) {
            Database::disconnect();
            return "Error: " . $e;
        }
    }else return false;
}

function editar_auto(){
    $id = $_POST['id'];
    $unidad = $_POST['num_unidad'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $placas = $_POST['placas'];
    $combustible = $_POST['tipo_combustible'];
    $kilometraje = $_POST['kilometraje'];
    $sql_ = "num_unidad='$unidad', marca='$marca', modelo='$modelo', placas='$placas', tipo_combustible='$combustible', kilometraje='$kilometraje'";

    if(repetido("vehiculo", "num_unidad", $unidad) == false){
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE vehiculo SET " . $sql_ . " WHERE id_vehiculo=?";
        $q = $pdo->prepare($sql);
        try {
            $q->execute(array($id));
            Database::disconnect();
            return true;
        } catch (PDOException $e) {
            Database::disconnect();
            return "Error: " . $e;
        }
    }else return false;
}

function eliminar_auto() {
    $unidad = $_POST['num_unidad'];

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DELETE FROM vehiculo WHERE id_vehiculo=?";
    $q = $pdo->prepare($sql);
    try {
        $q->execute(array($unidad));
        Database::disconnect();
        return true;
    } catch (PDOException $e) {
        Database::disconnect();
        return "Error: " . $e;
    }
}

function eliminar_bitacora() {
    $id = $_POST['id_bitacora'];

    try {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM bitacora WHERE id_bitacora=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        Database::disconnect();
        return true; 
    } catch (PDOException $e) {
        Database::disconnect();
        return "Error: " . $e;
    }     
}

function bitacora_existente(){
    $id = $_POST['id_bitacora'];
    $unidad = $_POST['unidad'];


    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT MAX(id_bitacora) AS bitacoras FROM bitacora WHERE NoUnidad = ?;";
    $q = $pdo->prepare($sql);
    try {
        $q->execute(array($unidad));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        if($data["bitacoras"] && $id < $data["bitacoras"])
            return false;
        else return true;
    } catch (PDOException $e) {
        Database::disconnect();
        return "Error: " . $e;
    } 
}