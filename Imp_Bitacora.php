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

$nomb = 'Solicitudes_'.date('d');

switch (date('m')){
		case 1: $nomb .= 'Ene'; break;
		case 2: $nomb .= 'Feb'; break;
		case 3: $nomb .= 'Mar'; break;
		case 4: $nomb .= 'Abr'; break;
		case 5: $nomb .= 'May'; break;
		case 6: $nomb .= 'Jun'; break;
		case 7: $nomb .= 'Jul'; break;
		case 8: $nomb .= 'Ago'; break;
		case 9: $nomb .= 'Sep'; break;
		case 10: $nomb .= 'Oct'; break;
		case 11: $nomb .= 'Nov'; break;
		case 12: $nomb .= 'Dic'; break;
		}

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

		$sql = "SELECT bitacora.*, recorrido.*, empleado.*,	CONCAT(Nombre,' ',ApellidoPaterno, ' ', ApellidoMaterno) AS empleado, vehiculo.*,
		CONCAT(marca,' ',modelo) AS marca
		FROM bitacora
		INNER JOIN recorrido ON bitacora.id_bitacora = recorrido.bitacora
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
				$objPHPExcel->getActiveSheet()->setCellValue('K6', $MostrarFila['operador']);
				$objPHPExcel->getActiveSheet()->setCellValue('N6', ($MostrarFila['marca']));
				$objPHPExcel->getActiveSheet()->setCellValue('F7', ($MostrarFila['NoUnidad']));
				$objPHPExcel->getActiveSheet()->setCellValue('N7', ($MostrarFila['placas']));
				
				/*if($MostrarFila['NivelUrgencia']==1) $objPHPExcel->getActiveSheet()->setCellValue('C15', 'X');
				elseif($MostrarFila['NivelUrgencia']==2) $objPHPExcel->getActiveSheet()->setCellValue('E15', 'X');
				else $objPHPExcel->getActiveSheet()->setCellValue('G15', 'X');
				
				if($MostrarFila['Red']==1) $objPHPExcel->getActiveSheet()->setCellValue('C26', 'X');
				if($MostrarFila['Mantenimiento']==1) $objPHPExcel->getActiveSheet()->setCellValue('C27', 'X');
				if($MostrarFila['Telefonia']==1) $objPHPExcel->getActiveSheet()->setCellValue('C28', 'X');
				if($MostrarFila['Formateo']==1) $objPHPExcel->getActiveSheet()->setCellValue('C29', 'X');
				if($MostrarFila['Comunicacion']==1) $objPHPExcel->getActiveSheet()->setCellValue('C30', 'X');
				if($MostrarFila['Impresora']==1) $objPHPExcel->getActiveSheet()->setCellValue('F26', 'X');
				if($MostrarFila['Asistencia']==1) $objPHPExcel->getActiveSheet()->setCellValue('F27', 'X');
				if($MostrarFila['Otro']==1) $objPHPExcel->getActiveSheet()->setCellValue('G27', 'X');
				
				$objPHPExcel->getActiveSheet()->setCellValue('F28', utf8_encode($MostrarFila['DescripcionProblema']));
				$objPHPExcel->getActiveSheet()->setCellValue('B49', utf8_encode($MostrarFila['Solicita']));

				$objPHPExcel->getActiveSheet()->setCellValue('F52', utf8_encode($MostrarFila['Recibe']));
				$objPHPExcel->getActiveSheet()->setCellValue('B52', utf8_encode($MostrarFila['Entrega']));*/
			endforeach;	
			$HojaIndex++;
			$num++;
		
		
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPrintArea("A1:O32");
		
		/*$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.39)->setBottom(0.39);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(1);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

		$objDrawing6 = new PHPExcel_Worksheet_HeaderFooterDrawing();
		$objDrawing6->setName('header');
		$objDrawing6->setPath('img/banner.jpg');
		$objDrawing6->setHeight(1100);
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->addImage($objDrawing6, PHPExcel_Worksheet_HeaderFooter::IMAGE_HEADER_LEFT);
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&G&');*/
    }
     catch(PDOException $e)
    {
       echo 'Error: ' . $e->getMessage();
    }
							   

	
	

//endforeach;
	
//$nomb .='.xlsx';
$nomb ='PRUEBA.xlsx';
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$nomb.'"');
header('Cache-Control: max-age=0');


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;

?>