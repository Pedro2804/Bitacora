<?php
set_time_limit(550);

/** Incluir la libreria PHPExcel */
include ('libexportar/PHPExcel.php');
require_once 'libexportar/PHPExcel/IOFactory.php';
require_once 'libexportar/PHPExcel/Cell/AdvancedValueBinder.php';

// $objReader = PHPExcel_IOFactory::createReader('Excel2007');
// $objPHPExcel = $objReader->load("PortadaNoticia.xlsx");

$objPHPExcel = PHPExcel_IOFactory::load('PortadaNoticia.xlsx');

include 'config/conexion.php';

if(isset($_GET['Reporte'])) $Reporte = $_GET['Reporte'];

/********************************************************
*														*
*	PROCESO DE EXPORTAR A EXCEL				XD			*
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


	$HojaIndex = 0;
	$r = 16;
	$c = 1;
	$Atendidas = 0;
	$Recibidas = 0;
	
	$objPHPExcel->setActiveSheetIndex($HojaIndex);
	try {
   
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$sqlReporte = "SELECT *,CONCAT(DAY(PeriodoDel),'-',
			CASE MONTH(PeriodoDel) WHEN 1 THEN 'ENERO' WHEN 2 THEN 'FEBRERO' WHEN 3 THEN 'MARZO' WHEN 4 THEN 'ABRIL' 
				WHEN 5 THEN 'MAYO' WHEN 6 THEN 'JUNIO' WHEN 7 THEN 'JULIO' WHEN 8 THEN 'AGOSTO' WHEN 9 THEN 'SEPTIEMBRE' 
				WHEN 10 THEN 'OCTUBRE' WHEN 11 THEN 'NOVIEMBRE' WHEN 12 THEN 'DICIEMBRE' END,'-',YEAR(PeriodoDel),' AL ',
				DAY(PeriodoAl),'-',
			CASE MONTH(PeriodoAl) WHEN 1 THEN 'ENERO' WHEN 2 THEN 'FEBRERO' WHEN 3 THEN 'MARZO' WHEN 4 THEN 'ABRIL' 
				WHEN 5 THEN 'MAYO' WHEN 6 THEN 'JUNIO' WHEN 7 THEN 'JULIO' WHEN 8 THEN 'AGOSTO' WHEN 9 THEN 'SEPTIEMBRE' 
				WHEN 10 THEN 'OCTUBRE' WHEN 11 THEN 'NOVIEMBRE' WHEN 12 THEN 'DICIEMBRE' END,'-',YEAR(PeriodoAl)) AS Periodo FROM not_reporte WHERE ClaveEntidad = ".$Reporte;
		$queryR = $pdo->prepare($sqlReporte);
		$queryR->execute(array());
		$ReporteDatos = $queryR->fetch(PDO::FETCH_ASSOC);
		$objPHPExcel->getActiveSheet()->setCellValue('D13', $ReporteDatos['DocumentoRespalda']);
		$objRichText_ = new PHPExcel_RichText();
		$objRichText_->createText(' ');
		$objBold = $objRichText_->createTextRun('PERIODO: ');
		$objBold->getFont()->setBold(true);
		$objRichText_->createText($ReporteDatos['Periodo']);
		$objPHPExcel->getActiveSheet()->getCell('A9')->setValue($objRichText_);
###  ATENDIDAS
		$sql = "SELECT DATE_FORMAT(FechaRecibida,'%d-%m-%Y') AS FechaRecibida_, DATE_FORMAT(FechaAtendida,'%d-%m-%Y') AS FechaAtendida_, DescripcionServicio,
						LPAD(Folio,4,'0') AS Folio_, CASE Estatus WHEN 1 THEN '0' ELSE '1' END AS Recibida_, Estatus, LENGTH(DescripcionServicio) AS LDescServ
				FROM not_solicitud 
				WHERE not_solicitud.CveEntReporteAtendida = ".$Reporte." ORDER BY Estatus DESC, Folio ";
	    $q = $pdo->prepare($sql);
        $q->execute(array());
        $data = $q->fetchall(PDO::FETCH_ASSOC);		   
		   foreach($data as $MostrarFila):
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$r, $c);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$r, '="'.$MostrarFila['Folio_'].'"');
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$r, utf8_encode($MostrarFila['DescripcionServicio']));
				if($MostrarFila['LDescServ']>43)
				$objPHPExcel->getActiveSheet()->getStyle('C'.$r)->getFont()->setSize('8');
				
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$r, "0");
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$r, $MostrarFila['FechaRecibida_']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$r, $MostrarFila['Recibida_']);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$r, $MostrarFila['FechaAtendida_']);
				$c++;
				$r++;
				if($MostrarFila['Estatus']==2) $Atendidas++;	
			endforeach;
###	 RECIBIDAS	  
        $sql = "SELECT DATE_FORMAT(FechaRecibida,'%d-%m-%Y') AS FechaRecibida_, DATE_FORMAT(FechaAtendida,'%d-%m-%Y') AS FechaAtendida_, DescripcionServicio,
						LPAD(Folio,4,'0') AS Folio_, CASE Estatus WHEN 1 THEN '0' ELSE '1' END AS Recibida_, Estatus, LENGTH(DescripcionServicio) AS LDescServ
				FROM not_solicitud
				WHERE not_solicitud.CveEntReporte = ".$Reporte." ORDER BY Estatus DESC, Folio ";
	    $q = $pdo->prepare($sql);
        $q->execute(array());
        $data = $q->fetchall(PDO::FETCH_ASSOC);		   
		   foreach($data as $MostrarFila):
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$r, $c);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$r, '="'.$MostrarFila['Folio_'].'"');
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$r, utf8_encode($MostrarFila['DescripcionServicio']));
				if($MostrarFila['LDescServ']>43)
				$objPHPExcel->getActiveSheet()->getStyle('C'.$r)->getFont()->setSize('8');
				
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$r, "1");
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$r, $MostrarFila['FechaRecibida_']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$r, $MostrarFila['Recibida_']);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$r, $MostrarFila['FechaAtendida_']);
				$c++;
				$r++;
				$Recibidas++;
				if($MostrarFila['Estatus']==2) $Atendidas++;	
			endforeach;
		$objPHPExcel->getActiveSheet()->getStyle('A15'.':G'.($r-1))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		
###
		$objPHPExcel->getActiveSheet()->setCellValue('D11', $Recibidas);
		$objPHPExcel->getActiveSheet()->setCellValue('D12', $Atendidas);
###
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$r, 'TOTALES');
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$r, $Recibidas);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$r)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFD9D9D9');
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$r, $Atendidas);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$r)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFD9D9D9');
		$objPHPExcel->getActiveSheet()->getStyle('C'.$r.':G'.$r)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
		$objPHPExcel->getActiveSheet()->getStyle('C'.$r.':G'.$r)->getFont()->setBold(true);
###
		$r++;
		$objPHPExcel->getActiveSheet()->getRowDimension($r)->setRowHeight(8);
		$r++;
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$r.':C'.$r);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$r)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$r, 'OBSERVACIONES');
		$r++;
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$r.':G'.$r);
		$objPHPExcel->getActiveSheet()->getRowDimension($r)->setRowHeight(30);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$r, $ReporteDatos['Observaciones']);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$r.':G'.$r)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
###
		$r +=2;
		$objPHPExcel->getActiveSheet()->mergeCells('C'.$r.':E'.$r);
		
		$objRichText = new PHPExcel_RichText();
		$objRichText->createText(' ');
		$objBold = $objRichText->createTextRun('ELABORÓ: ');
		$objBold->getFont()->setBold(true);
		$objRichText->createText($ReporteDatos['Elaboro']);
		$objPHPExcel->getActiveSheet()->getCell('C'.$r)->setValue($objRichText);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$r, 'FIRMA:');
		$objPHPExcel->getActiveSheet()->getStyle('F'.$r)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$r)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$r++;
		$objPHPExcel->getActiveSheet()->getRowDimension($r)->setRowHeight(8);
		$r++;
		
		
		//$objDrawing5 = new PHPExcel_Worksheet_HeaderFooterDrawing();
		//$objDrawing5->setName('watermark');
		//$objDrawing5->setPath('img/imagenBien.jpg');
		//$objPHPExcel->getActiveSheet()->getHeaderFooter()->addImage($objDrawing5, PHPExcel_Worksheet_HeaderFooter::IMAGE_FOOTER_LEFT);
		//$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&G&');


		$objDrawing6 = new PHPExcel_Worksheet_HeaderFooterDrawing();
		$objDrawing6->setName('header');
		$objDrawing6->setPath('img/imagenBien.jpg');
		$objDrawing6->setHeight(950);
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->addImage($objDrawing6, PHPExcel_Worksheet_HeaderFooter::IMAGE_HEADER_CENTER);
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&C&G&');

		$objDrawing7 = new PHPExcel_Worksheet_HeaderFooterDrawing();
		$objDrawing7->setName('footer');
		$objDrawing7->setPath('img/pienoticia.jpg');
		$objDrawing7->setHeight(83);
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->addImage($objDrawing7, PHPExcel_Worksheet_HeaderFooter::IMAGE_FOOTER_CENTER);
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&C&G&');


		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(1.25)->setBottom(1.25);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);


		$fila = $r-2;
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPrintArea("A1:G{$fila}");
		
    }
     catch(PDOException $e)
    {
       echo 'Error: ' . $e->getMessage();
    }
							   
	
$nomb .='.xlsx';
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$nomb.'"');
header('Cache-Control: max-age=0');


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;

?>
