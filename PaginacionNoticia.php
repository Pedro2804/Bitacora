<?php
include 'config/conexion.php';

if(isset($_POST['CmbMostrar'])) $RegistrosAMostrar = $_POST['CmbMostrar'];
else $RegistrosAMostrar = 8;

$msj='';
$condicion ='';
if(isset($_POST['txtPagina'])){
	$RegistrosAEmpezar=( $_POST['txtPagina']-1) * $RegistrosAMostrar;
	$PagAct=$_POST['txtPagina'];
}
else{
	$msj='NO DEFINIDA';
	$RegistrosAEmpezar=0;
	$PagAct=1;
}
	$condicion = '';

	if(isset($_POST['FechaDel'])) $condicion.=" AND PeriodoDel BETWEEN '".date('Y-m-d', strtotime($_POST['FechaDel']))."' AND '".date('Y-m-d', strtotime($_POST['FechaAl']))."'";
?>
<table class="newspaper-b" width="100%">
	<thead>
		<tr>
			<th width="11%">DEL</th>
            <th width="11%">AL</th>
            <th width="18%">FOLIOS</th>
			<th width="11%">RECIBIDAS</th>
            <th width="11%">ATENDIDAS</th>
            <th width="11%">TOTAL</th>
            <th width="19%">OBSERVACIONES</th>
<!--			<th width="8%">EDITAR</th>-->
            <th width="8%">REPORTE</th>
		</tr>
	</thead>
	<tbody>
<?php	
	try {
          $pdo = Database::connect();
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $sql = "SELECT *, DATE_FORMAT(PeriodoDel,'%d-%m-%Y') AS PeriodoDel_, DATE_FORMAT(PeriodoAl,'%d-%m-%Y') AS PeriodoAl_
		  			FROM not_reporte
					WHERE 1 $condicion
					ORDER BY PeriodoDel DESC LIMIT $RegistrosAEmpezar,$RegistrosAMostrar";
		  $q = $pdo->prepare($sql);
          $q->execute(array());
		  $filas = $q->rowCount();
		  
		  if($filas==0): echo '<tr><td colspan="8" align="center"><strong>NINGUN REGISTRO</strong></td></tr>';
		  else:
		  	 $cadena = '';
		  	 $data = $q->fetchall(PDO::FETCH_ASSOC);
			  foreach($data as $Solicitud):
			  	  $Recibidas = 0;
				  $Atendidas = 0;
				  $NumTotal = 0;

			  	  $sqlsolicitud = "SELECT COUNT(*) AS Reg, 1 AS Estatus, 1 AS Sumar FROM not_solicitud WHERE CveEntReporte = ".$Solicitud['ClaveEntidad']."
				  					UNION
								   SELECT COUNT(*) AS Reg, 2 AS Estatus, 0 AS Sumar FROM not_solicitud WHERE Estatus = 2 AND CveEntReporte = ".$Solicitud['ClaveEntidad']."
				  					UNION 
								   SELECT COUNT(*) AS Reg, 2 AS Estatus, 1 AS Sumar FROM not_solicitud WHERE Estatus = 2 AND CveEntReporteAtendida = ".$Solicitud['ClaveEntidad'];
				  $query = $pdo->prepare($sqlsolicitud);
				  $query->execute(array());
			  	  $Totales = $query->fetchall(PDO::FETCH_ASSOC);
				  foreach($Totales as $Total):
					if($Total['Sumar']==1)  $NumTotal += $Total['Reg'];
				  	if($Total['Estatus']==1) $Recibidas += $Total['Reg'];
					else $Atendidas += $Total['Reg'];
				  endforeach;
				  
				  $sqlFolio = "SELECT LPAD(MIN(Folio),4,'0') AS Del, LPAD(MAX(Folio),4,'0') AS Al FROM not_solicitud 
				  				WHERE CveEntReporte = ".$Solicitud['ClaveEntidad'];
				  $queryF = $pdo->prepare($sqlFolio);
				  $queryF->execute(array());
			  	  $Folio_ = $queryF->fetch(PDO::FETCH_ASSOC);
				  
				 echo '<tr>
					<td>'.$Solicitud['PeriodoDel_'].'</td>
					<td>'.$Solicitud['PeriodoAl_'].'</td>
					<td>DEL: <strong>'.$Folio_['Del'].'</strong> AL: <strong>'.$Folio_['Al'].'</strong></td>
					<td>'.$Recibidas.'</td>
					<td>'.$Atendidas.'</td>
					<td>'.$NumTotal.'</td>
					<td>'.utf8_encode($Solicitud['Observaciones']).'</td>
					<td><a href="PortadaNoticia.php?Reporte='.$Solicitud['ClaveEntidad'].'"><img src="img/formato.png" style="cursor:pointer" title="Reporte actividades"></a></td>
				</tr>';
				// 	<td><img src="img/lapiz.png" style="cursor:pointer" title="Editar Solicitud"></td>
		  endforeach;
        endif;
       }
    catch(PDOException $e)
       {
          echo 'Error: ' . $e->getMessage();
       }

?>
	</tbody>
	<tfoot>
		<tr>
        <?php
		  $pdo = Database::connect();
		  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		  $sql = "SELECT * 
				FROM not_reporte
				WHERE 1 $condicion";
		  $q = $pdo->prepare($sql);
		  $q->execute(array());
		  $NroRegistros = $q->rowCount();
		?>
			<td colspan="2"><?php echo "Total: ".$NroRegistros;?></td>
            <td colspan="4" style="vertical-align:top;">
				<center>
					<div id="Paginas">
					<?php
					if($NroRegistros==0){
						exit;
					}
					else{
						$PagAnt=$PagAct-1;
						$PagSig= $PagAct+1;
						$PagUlt=$NroRegistros/$RegistrosAMostrar;
						$Res=$NroRegistros%$RegistrosAMostrar;
						if($Res>0)
							$PagUlt=floor($PagUlt)+1;

						if($PagAct>=7){
							$inicio = $PagAct-6;
							$fin = $PagAct + 3;
							if($fin>$PagUlt){
								$fin = $PagUlt;
								$inicio = $PagUlt-9;
							}
							if($inicio<=0) 
								$inicio = 1;	
						}
						else{
							$inicio = 1;
							if($PagUlt>10) 
								$fin =10;
							else 
								$fin = $PagUlt; 
						}
						
//						if($PagAct >= 2) $LigaInicio = '<a href="javascript:;" onClick="Paginacion(1)"><img src="img/prev.png" /></a>'; else $LigaInicio = '<img src="img/prev.png" />';
						if($PagAct > 1) $LigaAnterior = '<a href="javascript:;" onClick="Paginacion('.$PagAnt.')">Anterior&nbsp;&nbsp;&nbsp;</a>'; else $LigaAnterior = '';
						$cadena = '';
						for($c=$inicio; $c<= $fin; $c++) {
							if($c== $PagAct)	$cadena .= '<strong style="padding:8px;">'.$c.'</strong>';
							else $cadena .= '<span class="Azul" onclick="Paginacion('.$c.')">'.$c.'</span>';
						}
						if($PagAct < $PagUlt):
							$LigaSiguiente = '<a href="javascript:;" onClick="Paginacion('.$PagSig.')">&nbsp;&nbsp;&nbsp;Siguiente</a>';
//							$LigaFin = '<a href="javascript:;" onClick="Paginacion('.$PagUlt.')"><img src="img/next.png" /></a>';
						else: 
							$LigaSiguiente = '';
//							$LigaFin = '<img src="img/next.png" />';
						endif;
						if($PagAct < $PagUlt) $dis5 = '';
						
						//echo $LigaInicio;
						echo $LigaAnterior;
						echo $cadena;
						echo $LigaSiguiente;
//						echo $LigaFin;
					}
											?>
										</div>
				</center>
			</td>
			<td colspan="2"><?php echo "Página ".$PagAct." de ".$PagUlt; ?></td>
		</tr>
	</tfoot>
</table>