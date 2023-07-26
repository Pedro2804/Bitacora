<?php
include 'config/conexion.php';

if(isset($_POST['CmbMostrar'])) $RegistrosAMostrar = $_POST['CmbMostrar'];
else $RegistrosAMostrar = 10;

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
	
	
	//if(isset($_POST["CmbFolio"]) and $_POST["CmbFolio"]==1) $condicion.=" AND Folio IS NULL";  else $condicion.=" AND Folio IS NOT NULL";
	if(isset($_POST['FechaDel'])) $condicion.=" AND fecha_recibido BETWEEN '".date('Y-m-d', strtotime($_POST['FechaDel']))."' AND '".date('Y-m-d', strtotime($_POST['FechaAl']))."'";
	//if(isset($_POST["CmbDireccion"]) and $_POST["CmbDireccion"]<>0) $condicion.=" AND not_solicitud.CveEntDireccion = ".$_POST["CmbDireccion"];
	//if(isset($_POST["CmbEstatus"]) and $_POST["CmbEstatus"]<>0) $condicion.=" AND not_solicitud.Estatus = ".$_POST["CmbEstatus"];
?>
<!--<div align="right"><a href="javascript:;" onClick="FormatoSolicitud()" style="cursor:pointer">Solicitud de mantenimiento</a></div>-->
<table class="newspaper-b" width="100%">
	<thead>
		<tr>
			<!--<th width="5%">FOLIO</th>-->
			<th width="5%">ID</th>
            <th width="10%">FECHA RECIBIDO</th>
            <th width="40%">OPERADOR</th>
			<th width="15%">NUMERO DE CONTROL</th>
            <th width="15%">UNIDAD DE RESGUARDO</th>
			<th width="5%">IMPRIMIR</th>
            <th width="5%">EDITAR</th>
			<th width="5%">ELIMINAR</th>
		</tr>
	</thead>
	<tbody>
<?php	
	try {
          $pdo = Database::connect();
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		  $sql="SELECT bitacora.*, DATE_FORMAT(fecha_recibido,'%d-%m-%Y') AS FechaRecibida, empleado.*, CONCAT(Nombre,' ',ApellidoPaterno, ' ', ApellidoMaterno)
		  		AS empleado FROM bitacora INNER JOIN empleado ON bitacora.operador = empleado.NumeroControl
				WHERE 1 $condicion
				ORDER BY fecha_recibido DESC, Folio DESC LIMIT $RegistrosAEmpezar,$RegistrosAMostrar";

		  $q = $pdo->prepare($sql);
          $q->execute(array());
		  $filas = $q->rowCount();
		  $CveSolicitudes = '';
		  
		  if($filas==0): echo '<tr><td colspan="9" align="center"><strong>NINGUN REGISTRO</strong></td></tr>';
		  else:
		  	 
		  	 $data = $q->fetchall(PDO::FETCH_ASSOC);
			  foreach($data as $Solicitud):
				 echo '<tr>
					<td>'.$Solicitud['id_bitacora'].'</td>
					<td>'.$Solicitud['FechaRecibida'].'</td>
					<td>'.$Solicitud['empleado'].'</td>
					<td>'.$Solicitud['operador'].'</td>
					<td>'.$Solicitud['NoUnidad'].'</td>
					<td><a href="Imp_Bitacora.php?id='.$Solicitud['id_bitacora'].'"><img src="img/impresora.png" width="45" height="45" style="cursor:pointer" title="Imprimir Solicitud"></a></td>
					<td><a href="editarBitacora.php?id='.$Solicitud['id_bitacora'].'"><img src="img/lapiz.png" style="cursor:pointer" title="Editar Solicitud"></a></td>
					<td><img src="img/basura.png" style="cursor:pointer" onclick="eliminar_bitacora('.$Solicitud['id_bitacora'].');" title="Eliminar Bitacora"></td>

				</tr>';
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
		  $sql = "SELECT bitacora.* 
				FROM bitacora
				WHERE 1 $condicion";
		  $q = $pdo->prepare($sql);
		  $q->execute(array());
		  $NroRegistros = $q->rowCount();
		?>
			<td colspan="2"><?php echo "Total: ".$NroRegistros;?></td>
            <td colspan="4" style="vertical-align:top;">
            <input name="TxtConsec" id="TxtConsec" type="hidden" value="<?php echo $CveSolicitudes;?>" />
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
