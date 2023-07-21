<?php
include '../config/conexion.php';
include '../config/conexion2.php';
session_start();
if (empty($_POST)) {
    echo 'error_post';
    exit();
}
function login()
{
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
	if($data == null)
	{
		Database::disconnect(); 
		return 0;
	}
	if($usuario != $data['usuario'])
	{
		Database::disconnect();
		return 0;
	}
	if($data['password'] != $password)
	{
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
}

function data_output($columns, $data)
{
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
function direcciones()
{
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "select ClaveEntidad, Nombre from direccion_cat where Estatus = 1 order by Nombre";
    $q = $pdo->prepare($sql);
    try {
        $q->execute(array());
        $data = $q->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $row) {
            $nw['id'] = $row['ClaveEntidad'];
            $nw['nomb'] = $row['Nombre'];
            $nombres[] = $nw;
        }
        $json = json_encode($nombres);
        Database::disconnect();
        return $json;
    } catch (PDOException $e) {
        Database::disconnect();
        return "Erro: : " . $e;
    }
}
function normaliza($cadena)
{
    $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞ
ßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
    $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuy
bsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
    $cadena = utf8_decode($cadena);
    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
    $cadena = strtolower($cadena);
    return utf8_encode($cadena);
}
function conver($var)
{
    $no_permitidas = array(">", "<", '"', "|", "°", "¬", "!", "#", "$", "%", "&", "=", "?", "¡", "'", "¿", "¨", "*", "~", "{", "[", "^", "]", "}", "`", "_", "'", "´", "’", "‘", "á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹");
    $permitidas = array("", "", '', "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "Ñ", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E");
    $texto = trim(strtoupper(str_replace($no_permitidas, $permitidas, $var)));
    $texto = iconv("UTF-8", "ISO-8859-1//IGNORE", $texto);
    return $texto;
}
function clavedepto($depto)
{
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT ClaveEntidad FROM departamento_cat WHERE Nombre = '" . $depto . "'";
    $q = $pdo->prepare($sql);
    try {
        $q->execute(array());
        $data = $q->fetch(PDO::FETCH_ASSOC);
        if ($data == null) {
            Database::disconnect();
            return false;
        }
        Database::disconnect();
        return $data['ClaveEntidad'];
    } catch (PDOException $e) {
        Database::disconnect();
        return "Error: " . $e;
    }
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

/*function datos_vehiculo($idvehiculo){
	$con = new conexion("localhost", "root", "Bitacora", "DIFinformatica.03");
	$con->conectar();
    $result = $con->consultas("marca, modelo, placas, kilometraje", "vehiculo", "WHERE num_unidad = $idvehiculo");
    $data = mysqli_fetch_array($result);
    $data = mysqli_fetch_array($result);
    if($data != null){
        $datos = [$data['marca']." ".$data['modelo'], $data['placas'], $data['kilometraje']]; 
        $con->cerrar();
        return $datos;
    }else { $con->cerrar(); return false;}
}*/

function datos_vehiculo($idvehiculo){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
    $sql = "SELECT CONCAT(marca,' ', modelo) AS marca, placas, kilometraje FROM vehiculo WHERE num_unidad = ?";
    $q = $pdo->prepare($sql);
    try {
        $q->execute(array($idvehiculo));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        if ($data == null) {
            Database::disconnect();
            return false;
        }
        Database::disconnect();
        $datos = [$data['marca'], $data['placas'], $data['kilometraje']]; 
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

function nuevo_recorrido($lugar){
    $aux = repetido("Destino", "lugar", $lugar);
    if($aux == false){
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO Destino (lugar) VALUES (?);";
        $q = $pdo->prepare($sql);
        try {
            $q->execute(array($lugar));
            Database::disconnect();
            return true;
        } catch (PDOException $e) {
            Database::disconnect();
            return "Error: " . $e;
        }
    }else return false;
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

function guardar_solicitud()
{

    $empleado = null;
    $idVehiculo = null;
    $fechalDel = null;
    $fechalAl = null;
    $fechaCarga = null;
    $monto = null;
    $folio = null;


    if (isset($_POST['empleado'])) {
        $empleado = $_POST['empleado'];
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
    if (isset($_POST['fecha_carga']) && !empty($_POST['fecha_carga'])) {
        $fechaCarga = $_POST['fecha_carga'];
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
    $sql = "INSERT INTO bitacora (operador, NoUnidad, periodo_de, periodo_al";
    $params = array($empleado, $idVehiculo, $fechalDel, $fechalAl);

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

    $sql .= ") VALUES (?, ?, ?, ?";

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
function filtrar_deptos()
{
    $direccion = $_POST['direccion'];
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "select ClaveEntidad, Nombre from departamento_cat where Estatus = 1 AND CveEntDireccion=? order by Nombre";
    $q = $pdo->prepare($sql);
    try {
        $q->execute(array($direccion));
        $data = $q->fetchall(PDO::FETCH_ASSOC);
        if ($data == null) {
            Database::disconnect();
            return false;
        }
        $cadena = "";
        foreach ($data as $row) {
            $cadena .= '<option value="' . $row['Nombre'] . '"></option>';
        }
        Database::disconnect();
        return $cadena;
    } catch (PDOException $e) {
        Database::disconnect();
        return "Error: " . $e;
    }
}
function mostrar_solicitudes()
{
    $f_inicio = $_POST['f_inicio'];
    $f_final = $_POST['f_final'];
    $columns = array(
        array('db' => 'Folio', 'dt' => 0),
        array('db' => 'DescripcionProblema', 'dt' => 1),
        array('db' => 'DescripcionServicio',  'dt' => 2),
        array('db' => 'area',  'dt' => 3),
        array('db' => 'FechaRecibida', 'dt' => 4),
        array('db' => 'FechaAtendida', 'dt' => 5),
        array('db' => 'Estatus', 'dt' => 6),
        array('db' => 'ClaveEntidad', 'dt' => 7)
    );
    $salida = array();
    $pdo = database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT Folio,DescripcionProblema,DescripcionServicio,IFNULL((SELECT Nombre FROM departamento_cat WHERE ClaveEntidad=CveEntDepartamento),IFNULL(OtroDepartamento,(SELECT Nombre FROM direccion_cat WHERE ClaveEntidad=CveEntDireccion))) as area,FechaRecibida,FechaAtendida,CASE WHEN Estatus = 1 THEN 'RECIBIDO' WHEN Estatus = 2 THEN 'ATENDIDO' END AS Estatus,ClaveEntidad FROM not_solicitud WHERE FechaRecibida BETWEEN ? AND ? AND Folio IS NULL ORDER BY FechaRecibida";
    $q = $pdo->prepare($query);
    try {
        $q->execute(array($f_inicio, $f_final));
        $data = $q->fetchAll(PDO::FETCH_ASSOC);
        $salida['data'] = data_output($columns, $data);
        $json = json_encode($salida, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
        Database::disconnect();
        return $json;
    } catch (PDOException $e) {
        Database::disconnect();
        return "Error: " . $e;
    }
}
function editar_solicitudTest()
{

    $clave = null;
    $fecha_recibo = null;
    $fecha_doc = null;
    $direccion = null;
    $departamento = null;
    $Nivel_urgencia = null;
    $contacto = null;
    $telefono = null;
    $red = null;
    $mantenimiento = null;
    $telefonia = null;
    $formateo = null;
    $comunicacion = null;
    $impresora = null;
    $asistencia_t = null;
    $otro = null;
    $descripcion_p = null;
    $solicita = null;
    $vobo = null;
    $entrega = null;
    $recibe = null;
    $cvedepto = null;
    $otrodepto = null;
    $sql_ = "";

    if (isset($_POST['clave_editar'])) {
        $clave = $_POST['clave_editar'];
    }
    // ----
    if (isset($_POST['fecha_r'])) {
        $fecha_recibo = $_POST['fecha_r'];
    }
    if ($fecha_recibo != null) {
        $sql_ .= "FechaRecibida='" . $fecha_recibo . "', ";
    }
    // ----
    if (isset($_POST['fecha_d'])) {
        $fecha_doc = $_POST['fecha_d'];
    }
    if ($fecha_doc != null) {
        $sql_ .= "FechaDocumento='" . $fecha_doc . "', ";
    }
    // ----
    if (isset($_POST['direccion'])) {
        $direccion = $_POST['direccion'];
    }
    if ($direccion != null) {
        $sql_ .= "CveEntDireccion=" . $direccion . ", ";
    }
    // ----
    if (isset($_POST['departamento'])) {
        $departamento = $_POST['departamento'];
        $resultdepto = clavedepto($departamento);
        if ($resultdepto == null) {
            $otrodepto = $_POST['departamento'];
            $otrodepto = conver($otrodepto);
        } else {
            $cvedepto = $resultdepto;
        }
    }
    if ($cvedepto != null) {
        $sql_ .= "CveEntDepartamento=" . $cvedepto . ", ";
    }
    // ----
    if (isset($_POST['urgencia'])) {
        $Nivel_urgencia = $_POST['urgencia'];
    }
    if ($Nivel_urgencia != null) {
        $sql_ .= "NivelUrgencia=" . $Nivel_urgencia . ", ";
    }
    /* // ----
    if (isset($_POST['contacto'])) {
        $contacto = $_POST['contacto'];
        $contacto = conver($contacto);
    }
    // ----
    if (isset($_POST['telefono'])) {
        $telefono = $_POST['telefono'];
    }
    // ---- */
    if (isset($_POST['red'])) {
        $red = 1;
    }
    if ($red == null) {
        $sql_ .= "Red=NULL, ";
    } else {
        $sql_ .= "Red=" . $red . ", ";
    }
    // ----
    if (isset($_POST['mantenimiento'])) {
        $mantenimiento = 1;
    }
    if ($mantenimiento == null) {
        $sql_ .= "Mantenimiento=NULL, ";
    } else {
        $sql_ .= "Mantenimiento=" . $mantenimiento . ", ";
    }

     // ----
    if (isset($_POST['telefonia'])) {
        $telefonia = 1;
    }
    if ($telefonia == null) {
        $sql_ .= "Telefonia=NULL, ";
    } else {
        $sql_ .= "Telefonia=" . $telefonia . ", ";
    }

    // ----
    if (isset($_POST['formateo'])) {
        $formateo = 1;
    }
    if ($formateo == null) {
        $sql_ .= "Formateo=NULL, ";
    } else {
        $sql_ .= "Formateo=" . $formateo . ", ";
    }

    // ----
    if (isset($_POST['comunicacion'])) {
        $comunicacion = 1;
    }
    if ($comunicacion == null) {
        $sql_ .= "Comunicacion=NULL, ";
    } else {
        $sql_ .= "Comunicacion=" . $comunicacion . ", ";
    }

    // ----
    if (isset($_POST['impresora'])) {
        $impresora = 1;
    }
    if ($impresora == null) {
        $sql_ .= "Impresora=NULL, ";
    } else {
        $sql_ .= "Impresora=" . $impresora . ", ";
    }

    // ----
    if (isset($_POST['asistencia_t'])) {
        $asistencia_t = 1;
    }
    if ($asistencia_t == null) {
        $sql_ .= "Asistencia=NULL, ";
    } else {
        $sql_ .= "Asistencia=" . $asistencia_t . ", ";
    }

   // ----
    if (isset($_POST['otro'])) {
        $otro = 1;
    }
    if ($otro == null) {
        $sql_ .= "Otro=NULL, ";
    } else {
        $sql_ .= "Otro=" . $otro . ", ";
    }

    // ----
    if (isset($_POST['descripcion'])) {
        $descripcion_p = $_POST['descripcion'];
        $descripcion_p = conver($descripcion_p);
    }
    if ($descripcion_p != "") {
        $sql_ .= "DescripcionProblema='" . $descripcion_p . "', ";
        $sql_ .= "DescripcionServicio='" . $descripcion_p . "', ";
    }
     // ----
    if (isset($_POST['solicita'])) {
        $solicita = $_POST['solicita'];
        if ($solicita == "") {
            $sql_ .= "Solicita=NULL, ";
        } else {
            $solicita = conver($solicita);
            $sql_ .= "Solicita='" . $solicita . "', ";
        }
    }
    // ----
    if (isset($_POST['visto'])) {
        $vobo = $_POST['visto'];
        if ($vobo == "") {
            $sql_ .= "VoBo=NULL, ";
        } else {
            $vobo = conver($vobo);
            $sql_ .= "VoBo='".$vobo."', ";
        }
    }
    // ----
    if (isset($_POST['entrega'])) {
        $entrega = $_POST['entrega'];
        if ($entrega == 0) {
            $entrega = null;
        } else {
            $resultentrega = nombreempl();
            $entrega = conver($resultentrega);
            $sql_ .= "Entrega='".$entrega."', ";
        }
    }
    // ----
    if (isset($_POST['recibe'])) {
        $recibe = $_POST['recibe'];
        if ($recibe == "") {
            $sql_ .= "Recibe=NULL ";
        } else {
            $recibe = conver($recibe);
            $sql_ .= "Recibe='".$recibe."' ";
        }
    }


    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE not_solicitud SET ".$sql_." WHERE ClaveEntidad=?";
    $q = $pdo->prepare($sql);
    try {
        $q->execute(array($clave));
        Database::disconnect();
        return true;
    } catch (PDOException $e) {
        Database::disconnect();
        return "Error: " . $e;
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

function editar_auto()
{
    $id = $_POST['id'];
    $unidad = $_POST['num_unidad'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $placas = $_POST['placas'];
    $combustible = $_POST['tipo_combustible'];
    $kilometraje = $_POST['kilometraje'];
    $sql_ = "num_unidad='$unidad', marca='$marca', modelo='$modelo', placas='$placas', tipo_combustible='$combustible', kilometraje='$kilometraje'";


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


function mostrar_solicitudes_sin_atender()
{
    $f_inicio = $_POST['f_inicio'];
    $f_final = $_POST['f_final'];
    $columns = array(
        array('db' => 'Folio', 'dt' => 0),
        array('db' => 'DescripcionProblema', 'dt' => 1),
        array('db' => 'DescripcionServicio',  'dt' => 2),
        array('db' => 'area',  'dt' => 3),
        array('db' => 'FechaRecibida', 'dt' => 4),
        array('db' => 'FechaAtendida', 'dt' => 5),
        array('db' => 'Estatus', 'dt' => 6),
        array('db' => 'ClaveEntidad', 'dt' => 7)
    );
    $salida = array();
    $pdo = database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT Folio,DescripcionProblema,DescripcionServicio,IFNULL((SELECT Nombre FROM departamento_cat WHERE ClaveEntidad=CveEntDepartamento),IFNULL(OtroDepartamento,(SELECT Nombre FROM direccion_cat WHERE ClaveEntidad=CveEntDireccion))) as area,FechaRecibida,FechaAtendida,CASE WHEN Estatus = 1 THEN 'RECIBIDO' WHEN Estatus = 2 THEN 'ATENDIDO' END AS Estatus,ClaveEntidad 
FROM not_solicitud 
WHERE (Folio IS NOT NULL AND FechaAtendida BETWEEN ? AND ? AND Estatus=1) OR (Folio IS NOT NULL AND FechaAtendida IS NULL AND Estatus=1) ORDER BY FechaRecibida";

    $q = $pdo->prepare($query);
    try {
        $q->execute(array($f_inicio, $f_final));
        $data = $q->fetchAll(PDO::FETCH_ASSOC);
        $salida['data'] = data_output($columns, $data);
        $json = json_encode($salida, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
        Database::disconnect();
        return $json;
    } catch (PDOException $e) {
        Database::disconnect();
        return "Error: " . $e;
    }
}
function guardar_reporte()
{
    $id_reporte = 0;
    $f_inicio = $_POST['fecha_inicio'];
    $f_final = $_POST['fecha_final'];
    $claves_solicitud = $_POST['claves_solicitud'];
    $claves_solicitud_sa = $_POST['claves_solicitud_sa'];
    $folio_inicial = $_POST['folios_g'];
    $doc_respalda = $_POST['doc_respalda'];
    $observaciones = null;
    $elaboro = 'elaboro';

    $array_solicitudes = explode(",", $claves_solicitud);
    $total_solicitudes = sizeof($array_solicitudes);
    unset($array_solicitudes[$total_solicitudes - 1]);
    $array_solicitudes = array_unique($array_solicitudes);
    $total_solicitudes = sizeof($array_solicitudes);

    $array_solicitudes_sa = explode(",", $claves_solicitud_sa);
    $total_solicitudes_sa = sizeof($array_solicitudes_sa);
    unset($array_solicitudes_sa[$total_solicitudes_sa - 1]);
    $total_solicitudes_sa = sizeof($array_solicitudes_sa);


    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO not_reporte (PeriodoDel,PeriodoAl,DocumentoRespalda,Observaciones,Elaboro) VALUES (?,?,?,?,?);";
    $q = $pdo->prepare($sql);
    try {
        $q->execute(array($f_inicio, $f_final, $doc_respalda, $observaciones, 'MARCO ANTONIO SANCHEZ RAMIREZ'));
        $id_reporte = $pdo->lastInsertId();
        Database::disconnect();
    } catch (PDOException $e) {
        Database::disconnect();
        return "Error: " . $e;
    }
    foreach ($array_solicitudes as $solicitud) {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE not_solicitud SET Folio=?,CveEntReporte=? WHERE ClaveEntidad=?;";
        $q = $pdo->prepare($sql);
        try {
            $q->execute(array($folio_inicial, $id_reporte, $solicitud));
            Database::disconnect();
        } catch (PDOException $e) {
            Database::disconnect();
            return "Error: " . $e;
        }
        $folio_inicial++;
    }
    foreach ($array_solicitudes_sa as $solicitud_sa) {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE not_solicitud SET CveEntReporteAtendida=? WHERE ClaveEntidad=?;";
        $q = $pdo->prepare($sql);
        try {
            $q->execute(array($id_reporte, $solicitud_sa));
            Database::disconnect();
        } catch (PDOException $e) {
            Database::disconnect();
            return "Error: " . $e;
        }
    }
    return true;
}