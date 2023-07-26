<?php
header("Content-Type: text/html;charset=utf-8");
include '../functions/funciones.php';
if(empty($_POST))
{
	echo 'error';
	exit();
}

if (isset($_POST['id']))
    $vehiculo = $_POST['id'];

if (isset($_POST['lugar']))
    $lugar = $_POST['lugar'];


$funcion = $_POST['funcion'];
switch ($funcion) 
{
    case 'nuevo_usuario':
        $respuesta = nuevo_usuario();
        echo $respuesta;
	break;
    case 'vehiculo':
        $respuesta = datos_vehiculo($vehiculo);
        if ($respuesta == false) echo '0';
        else echo json_encode(array('modelo' => $respuesta[0], 'placas' => $respuesta[1], 'km' => $respuesta[2], 'comb' => $respuesta[3]));
    break;
    case 'mostrar_vehiculos':
        $respuesta=mostrar_vehiculos();
        echo $respuesta;
	break;
    case 'get_vehiculo':
        $respuesta=get_vehiculo($vehiculo);
        echo $respuesta;
	break;
    case 'editar_auto':
        $respuesta=editar_auto();
        echo $respuesta;
	break;
    case 'eliminar_auto':
        $respuesta=eliminar_auto();
        echo $respuesta;
	break;
    case 'nuevo_auto':
        $respuesta=nuevo_auto();
        echo $respuesta;
	break;
	case 'login':
		$respuesta=login();
        echo $respuesta;
	break;
    case 'guardar_bitacora':   
        $respuesta=guardar_bitacora();
        echo $respuesta;
	break;
    case 'guardar_recorrido':   
        $respuesta=guardar_recorrido();
        echo $respuesta;
	break;
    case 'eliminar_bitacora':   
        $respuesta=eliminar_bitacora();
        echo $respuesta;
	break;
    case 'nuevo_recorrido':
        $respuesta=nuevo_recorrido($lugar);
        echo $respuesta;
	break;
    case 'nombre_empleado':
        $respuesta=nombreempl();
        if ($respuesta == false) echo '0';
        else echo json_encode($respuesta);
	break;
    case 'editar_solicitudTest':
        $respuesta=editar_solicitudTest();
        echo $respuesta;
	break;       
}
?> 
