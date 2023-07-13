<?php
include 'config/conexion.php';
$fechainicio = $_POST['FechaDel'];
$fechafin = $_POST['FechaAl'];

// Calcula los días totales
$fechaInicio = new DateTime($fechainicio);
$fechaFin = new DateTime($fechafin);
$fechaFin->add(new DateInterval('P1D'));

$diasTotales = ($fechaInicio->diff($fechaFin)->days);
   // Obtiene los números de día de la semana seleccionados
    $diaSemanaInicio = $fechaInicio->format('N');
    $diaSemanaFin = $fechaFin->format('N');

    // Mapear los nombres de los días de la semana en español
    $diasSemana = array(
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
        7 => 'Domingo'
    );

    // Generar la barra de navegación con pestañas
    include 'formRecorrido.php';
?>