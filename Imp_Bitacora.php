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
$pageIndex=2;
$num = 0;
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

        $q = $pdo->prepare($sql);
        $q->execute(array());
        $data = $q->fetchall(PDO::FETCH_ASSOC);
		llenar_bitacora($data, $objPHPExcel, $meses);

		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "SELECT * FROM recorrido	WHERE bitacora = $Solicitud;";
		$q = $pdo->prepare($sql);
		$q->execute(array());
		$data = $q->fetchall(PDO::FETCH_ASSOC);

		$n = 0;
		foreach($data as $MostrarFila):
			if($MostrarFila['vacio'] == 0){
				$aux = wordwrap($MostrarFila['recorrido'], 30, "\n");
				$aux2 = explode("\n",$aux);
				$n += count($aux2);
			}
		endforeach;
		
		if($n>14){
			$b=0;
			$a=0;
			while ($a < $n) {
				$b++;
				$a++;
				if($b==14){
					$tempSheet = $objPHPExcel->getSheet(0)->copy();
					$tempSheet->setTitle('Hoja'.$pageIndex);
					$objPHPExcel->addSheet($tempSheet);
					unset($tempSheet);
					$b=0;
					$pageIndex++;
					$b=0;
				}
			}
		}

		$i = 13;
		$km_total = 0;
		foreach($data as $MostrarFila):
			if($MostrarFila['vacio'] == 0){
				$objPHPExcel->setActiveSheetIndex($HojaIndex);
				$aux = wordwrap($MostrarFila['recorrido'], 30, "\n");
				$aux2 = explode("\n",$aux);

				if(count($aux2)>1){
					$a=0;
					while ($a < count($aux2)) {
						$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $aux2[$a], PHPExcel_Cell_DataType::TYPE_STRING);
						$i++;
						$a++;
						if($i == 27){
							$HojaIndex++;
							$objPHPExcel->setActiveSheetIndex($HojaIndex);
							$i=13;
						}
					}
					$i--;
					
				}else{
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $aux2[0], PHPExcel_Cell_DataType::TYPE_STRING);
				}
					
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

				$objPHPExcel->getActiveSheet()->getStyle('K'.$i.':'.'M'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objPHPExcel->getActiveSheet()->getStyle('K'.$i.':'.'M'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);					
				$i++;
			}
		endforeach;
		$objPHPExcel->getActiveSheet()->setCellValue('O27', $km_total);
		
		for ($x=0; $x < ($pageIndex-1); $x++) { 
			$objPHPExcel->setActiveSheetIndex($x);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setPrintArea("A1:O34");
			
			$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(1);
		}
		

		/*$objDrawing6 = new PHPExcel_Worksheet_HeaderFooterDrawing();//PARA PONER IMAGEN AL FORMATO
		$objDrawing6->setName('header');
		$objDrawing6->setPath('img/prueba.jpg');
		$objDrawing6->setWidth(700);
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->addImage($objDrawing6, PHPExcel_Worksheet_HeaderFooter::IMAGE_HEADER_LEFT);
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&G&');*/
    }catch(PDOException $e){
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

function llenar_bitacora($data, $objPHPExcel, $meses){
	foreach($data as $MostrarFila):
		$objPHPExcel->getActiveSheet()->setCellValue('C6', $MostrarFila['empleado']);
		$objPHPExcel->getActiveSheet()->setCellValue('A30', $MostrarFila['empleado']);
		$objPHPExcel->getActiveSheet()->setCellValue('I30', $MostrarFila['empleado']);
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

		if($MostrarFila['fecha_carga']){
			$fecha_carga = new DateTime($MostrarFila['fecha_carga']);
			$objPHPExcel->getActiveSheet()->setCellValue('E10', $fecha_carga->format('d').' de '.$meses[$fecha_carga->format('n')].' de '.$fecha_carga->format('Y'));
		}

		switch ($MostrarFila['tipo_combustible']) {
			case 'Gasolina':
				$objPHPExcel->getActiveSheet()->setCellValue('M10', "X");
				$objPHPExcel->getActiveSheet()->setCellValue('K10', $MostrarFila['combustible'] != null ? $MostrarFila['combustible']:"");
				break;
			case 'Diesel':
				$objPHPExcel->getActiveSheet()->setCellValue('O10', "X");
				$objPHPExcel->getActiveSheet()->setCellValue('K10', $MostrarFila['combustible'] != null ? $MostrarFila['combustible']:"");
			break;
			case 'Gas':
				$objPHPExcel->getActiveSheet()->setCellValue('K10', "GAS");
			break;
			case 'No aplica':
				$objPHPExcel->getActiveSheet()->setCellValue('K10', "N/A");
			break;
		}
	endforeach;
}

?>