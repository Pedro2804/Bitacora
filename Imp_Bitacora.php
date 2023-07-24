<?php
set_time_limit(550);

/** Incluir la libreria PHPExcel */
include ('libexportar/PHPExcel.php');
require_once 'libexportar/PHPExcel/IOFactory.php';
require_once 'libexportar/PHPExcel/Cell/AdvancedValueBinder.php';

$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load("Bitacora.xlsx");


include 'config/conexion.php';

if(!empty($_GET['id'])) $Solicitud =$_GET['id'];

/********************************************************
*														*
*	PROCESO DE EXPORTAR A EXCEL							*
*														*
*********************************************************/	

date_default_timezone_set('America/Mexico_City');
$nomb = 'Bitacora_'.date('d');

switch (date('m')){
		case 1: $nomb .= 'Ene_'; break;
		case 2: $nomb .= 'Feb_'; break;
		case 3: $nomb .= 'Mar_'; break;
		case 4: $nomb .= 'Abr_'; break;
		case 5: $nomb .= 'May_'; break;
		case 6: $nomb .= 'Jun_'; break;
		case 7: $nomb .= 'Jul_'; break;
		case 8: $nomb .= 'Ago_'; break;
		case 9: $nomb .= 'Sep_'; break;
		case 10: $nomb .= 'Oct_'; break;
		case 11: $nomb .= 'Nov_'; break;
		case 12: $nomb .= 'Dic_'; break;
}

$meses = array(
	1 => 'Enero',
	2 =>'Febrero',
	3 =>'Marzo',
	4 =>'Abril',
	5 =>'Mayo',
	6 =>'Junio',
	7 =>'Julio',
	8 =>'Agosto',
	9 =>'Septiembre',
	10 =>'Octubre',
	11 =>'Noviembre',
	12 =>'Diciembre');

//temp sheet copy 2 times

/*for ($pageIndex=2; $pageIndex <= count($Solicitudes); $pageIndex++) {
   $tempSheet = $objPHPExcel->getSheet(0)->copy();
   $tempSheet->setTitle('' . $pageIndex);

   $objPHPExcel->addSheet($tempSheet);
   unset($tempSheet);
}*/

$HojaIndex = 0;
$num = 1;
//foreach ($Solicitud as $Bitacora):
	$objPHPExcel->setActiveSheetIndex($HojaIndex);
	try {
   
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "SELECT bitacora.*,CONCAT(folio,' $',monto) AS folio,empleado.*,CONCAT(Nombre,' ',ApellidoPaterno, ' ', ApellidoMaterno) AS empleado,vehiculo.*,
		CONCAT(marca,' ',modelo) AS marca
		FROM bitacora
		INNER JOIN empleado ON bitacora.operador = empleado.NumeroControl
		INNER JOIN vehiculo ON bitacora.NoUnidad = vehiculo.Num_Unidad
		WHERE id_bitacora = $Solicitud;";

        /*$sql = "SELECT DATE_FORMAT(FechaDocumento,'%d-%m-%Y') AS FechaDocumento, direccion_cat.Nombre as Direccion, IFNULL(REPLACE(departamento_cat.Nombre,'DEPARTAMENTO DE ',''),IFNULL(OtroDepartamento,'')) AS Depto, NivelUrgencia, Red, 
						Mantenimiento, Telefonia, Formateo, Comunicacion, Impresora, Asistencia, Otro, DescripcionProblema,
						IFNULL(Solicita, 'NOMBRE Y FIRMA') AS Solicita, IFNULL(VoBo, 'NOMBRE Y FIRMA') AS VoBo, IFNULL(Recibe, 'NOMBRE Y FIRMA') AS Recibe, IFNULL(Entrega , 'NOMBRE Y FIRMA') AS Entrega,
						LPAD(Folio,4,'0') AS Folio_
				FROM not_solicitud
				INNER JOIN direccion_cat ON direccion_cat.ClaveEntidad = not_solicitud.CveEntDireccion
				LEFT OUTER JOIN departamento_cat ON departamento_cat.ClaveEntidad = not_solicitud.CveEntDepartamento
				WHERE not_solicitud.ClaveEntidad = ".$Solicitud. " ORDER BY FechaRecibida DESC, Folio DESC ";*/
        $q = $pdo->prepare($sql);
        $q->execute(array());
        $data = $q->fetchall(PDO::FETCH_ASSOC);		   
		   foreach($data as $MostrarFila):
				$objPHPExcel->getActiveSheet()->setCellValue('C6', $MostrarFila['empleado']);
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('K6', $MostrarFila['operador']);
				$objPHPExcel->getActiveSheet()->setCellValue('N6', $MostrarFila['marca']);
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('F7', $MostrarFila['NoUnidad']);
				$objPHPExcel->getActiveSheet()->setCellValue('N7', $MostrarFila['placas']);

				$de = new DateTime($MostrarFila['periodo_de']);
				$al = new DateTime($MostrarFila['periodo_al']);
				if(date('n', strtotime($MostrarFila['periodo_de'])) == date('n', strtotime($MostrarFila['periodo_al'])))
					$objPHPExcel->getActiveSheet()->setCellValue('B8', $meses[$de->format('n')]);
				else
					$objPHPExcel->getActiveSheet()->setCellValue('B8', $meses[$de->format('n')].'-'.$meses[$al->format('n')]);
				

				$objPHPExcel->getActiveSheet()->setCellValueExplicit('L8', $MostrarFila['folio']);
				$objPHPExcel->getActiveSheet()->setCellValue('D9', $de->format('d'));
				$objPHPExcel->getActiveSheet()->setCellValue('F9', $al->format('d'));

				if(date('Y', strtotime($MostrarFila['periodo_de'])) == date('Y', strtotime($MostrarFila['periodo_al'])))
					$objPHPExcel->getActiveSheet()->setCellValue('H9', $de->format('Y'));
				else
					$objPHPExcel->getActiveSheet()->setCellValue('H9', $de->format('Y').'-'.$al->format('Y'));
				
				$objPHPExcel->getActiveSheet()->setCellValue('L9', $MostrarFila['cada_vale']);

				$fecha_carga = new DateTime($MostrarFila['fecha_carga']);
				$objPHPExcel->getActiveSheet()->setCellValue('D10', $fecha_carga->format('d').' de '.$meses[$fecha_carga->format('n')].' de '.$fecha_carga->format('Y'));
				
				switch ($MostrarFila['tipo_combustible']) {
					case 'gasolina':
						$objPHPExcel->getActiveSheet()->setCellValue('M10', "X");
						break;
					case 'diesel':
						$objPHPExcel->getActiveSheet()->setCellValue('O10', "X");
					break;
					case 'gas':
						$objPHPExcel->getActiveSheet()->setCellValue('K10', "GAS");
					break;
					case 'no aplica':
						$objPHPExcel->getActiveSheet()->setCellValue('K10', "N/A");
					break;
				}
			endforeach;

			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$sql = "SELECT * FROM recorrido	WHERE bitacora = $Solicitud;";
			$q = $pdo->prepare($sql);
			$q->execute(array());
			$data = $q->fetchall(PDO::FETCH_ASSOC);

			foreach($data as $MostrarFila):

			endforeach;

			$HojaIndex++;
			$num++;
		
		
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPrintArea("A1:O32");
		
		/*$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.39)->setBottom(0.39);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(1);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);*/

		$objDrawing6 = new PHPExcel_Worksheet_HeaderFooterDrawing();
		$objDrawing6->setName('header');
		$objDrawing6->setPath('img/banner.jpg');
		$objDrawing6->setHeight(1100);
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->addImage($objDrawing6, PHPExcel_Worksheet_HeaderFooter::IMAGE_HEADER_LEFT);
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&G&');
    }
     catch(PDOException $e)
    {
       echo 'Error: ' . $e->getMessage();
    }
							   

	
	

//endforeach;
	
$nomb .='.xlsx';
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$nomb.'"');
header('Cache-Control: max-age=0');


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;

?>