<?php
include '../config/conexion.php';
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
	$sql = "SELECT id,nombre_completo,usuario,password FROM catalogo_usuarios WHERE usuario = ?";
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
	$_SESSION['catalogo_id_usuario'] = $data['id'];
	$_SESSION['usuario'] = $data['usuario'];
	$_SESSION['nombres']=$data['nombre_completo'];
	Database::disconnect();
	return true;
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
function nombreempl($cve)
{
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT CONCAT(nombre,' ',ApellidoPaterno,' ',ApellidoMaterno) as nombre_empleado  FROM EMPLEADO WHERE ClaveEntidad=?";
    $q = $pdo->prepare($sql);
    try {
        $q->execute(array($cve));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        if ($data == null) {
            Database::disconnect();
            return false;
        }
        Database::disconnect();
        return $data['nombre_empleado'];
    } catch (PDOException $e) {
        Database::disconnect();
        return "Error: " . $e;
    }
}

function datos_vehiculo($idvehiculo){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT num_unidad, tipo_combustible FROM vehiculo WHERE num_unidad=?";
    $q = $pdo->prepare($sql);
    try {
        $q->execute(array($idvehiculo));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        if ($data == null) {
            Database::disconnect();
            return false;
        }
        Database::disconnect();
        $datos = [$data['num_unidad'], $data['tipo_combustible']]; 
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
        array('db' => 'tipo_combustible', 'dt' => 2)
    );
    $salida = array();
    $pdo = new PDO('mysql:host=localhost;dbname=controlvehicular', 'root', 'DIFinformatica.03', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
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
    $sql = "INSERT INTO bitacora (vehiculo, empleado, periodo_de, periodo_al";
    $params = array($idVehiculo, $empleado, $fechalDel, $fechalAl);

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
            $resultentrega = nombreempl($entrega);
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

function editar_solicitud()
{
    $clave = null;
    $descripcion = null;
    $fecha_a = null;
    $sql_ = null;

    if (isset($_POST['clave_editar'])) {
        $clave = $_POST['clave_editar'];
    }
    if (isset($_POST['e_descripcion_s'])) {
        $descripcion = conver($_POST['e_descripcion_s']);
    }
    if ($descripcion != "") {
        $sql_ = "DescripcionServicio='" . $descripcion . "'";
    }
    if (isset($_POST['fecha_a'])) {
        $fecha_a = $_POST['fecha_a'];
    }
    if ($fecha_a == "no") {
        $sql_ = "FechaAtendida=NULL, Estatus=1";
    } else {
        $sql_ = "FechaAtendida='" . $fecha_a . "', Estatus=2";
    }

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE not_solicitud SET " . $sql_ . " WHERE ClaveEntidad=?";
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

function eliminar_solicitud () {
    $clave = null;

    if (isset($_POST['clave_borrar'])) {
            $clave = $_POST['clave_borrar'];
    }

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DELETE FROM not_solicitud WHERE ClaveEntidad=?";
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
