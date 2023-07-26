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
				$objPHPExcel->getActiveSheet()->setCellValue('E10', $fecha_carga->format('d').' de '.$meses[$fecha_carga->format('n')].' de '.$fecha_carga->format('Y'));
				
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

			$i = 13;
			$km_total = 0;
			foreach($data as $MostrarFila):
				$dia = explode(" ",$MostrarFila['dia_semana']);
				if($dia[1]<10) (string)$dia[1] = '0'.(string)$dia[1];
				switch ($dia[0]) {
					case 'Lunes':
						$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$i, $dia[1]);
					break;
					case 'Martes':
						$objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$i, $dia[1]);
					break;
					case 'Miércoles':
						$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$i, $dia[1]);
					break;
					case 'Jueves':
						$objPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$i, $dia[1]);
					break;
					case 'Viernes':
						$objPHPExcel->getActiveSheet()->setCellValueExplicit('E'.$i, $dia[1]);
					break;
					case 'Sábado':
						$objPHPExcel->getActiveSheet()->setCellValueExplicit('F'.$i, $dia[1]);
					break;
					case 'Domingo':
						$objPHPExcel->getActiveSheet()->setCellValueExplicit('G'.$i, $dia[1]);
					break;
					
					default:
						# code...
						break;
				}
				$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':'.'O'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':'.'O'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $MostrarFila['salida']);
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $MostrarFila['km_inicial']);
								
				$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, $MostrarFila['km_final']);
				$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, $MostrarFila['km_final']-$MostrarFila['km_inicial']);
				
				$km_total += $MostrarFila['km_final']-$MostrarFila['km_inicial'];

				$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $MostrarFila['recorrido']);
				
				$objPHPExcel->getActiveSheet()->getStyle('K'.$i.':'.'M'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objPHPExcel->getActiveSheet()->getStyle('K'.$i.':'.'M'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				if(strlen($MostrarFila['recorrido'])>35){
					$objPHPExcel->getActiveSheet()->unmergeCells('H'.$i.':I'.$i);
					$objPHPExcel->getActiveSheet()->unmergeCells('K'.$i.':M'.$i);
					$objPHPExcel->getActiveSheet()->unmergeCells('H'.($i+1).':I'.($i+1));
					$objPHPExcel->getActiveSheet()->unmergeCells('K'.($i+1).':M'.($i+1));

					for($j=65; $j<72; $j++)
						$objPHPExcel->getActiveSheet()->mergeCells(chr($j).$i.':'.chr($j).($i+1));
					
					$objPHPExcel->getActiveSheet()->mergeCells('H'.$i.':I'.($i+1));
					$objPHPExcel->getActiveSheet()->mergeCells('J'.$i.':J'.($i+1));	
					$objPHPExcel->getActiveSheet()->mergeCells('K'.$i.':M'.($i+1));
					$objPHPExcel->getActiveSheet()->mergeCells('N'.$i.':N'.($i+1));
					$objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':O'.($i+1));

					$objPHPExcel->getActiveSheet()->getStyle('K'.$i.':'.'M'.$i)->getAlignment()->setWrapText(true);
					//$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
					
					$i+=2;
					//$objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(26);
				}else
					$i++;
			endforeach;

			$objPHPExcel->getActiveSheet()->setCellValue('O27', $km_total);

			$HojaIndex++;
			$num++;
		
		
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPrintArea("A1:O34");
		
		//$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(2.00)->setBottom(2.00);
		//$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.80)->setRight(0.70);
		
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(1);

		/*$objDrawing6 = new PHPExcel_Worksheet_HeaderFooterDrawing();
		$objDrawing6->setName('header');
		$objDrawing6->setPath('img/prueba.jpg');
		$objDrawing6->setWidth(700);
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->addImage($objDrawing6, PHPExcel_Worksheet_HeaderFooter::IMAGE_HEADER_LEFT);
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&G&');*/
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