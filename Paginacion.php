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
	
	
	if(isset($_POST["CmbFolio"]) and $_POST["CmbFolio"]==1) $condicion.=" AND Folio IS NULL";  else $condicion.=" AND Folio IS NOT NULL";
	if(isset($_POST['FechaDel'])) $condicion.=" AND FechaRecibida BETWEEN '".date('Y-m-d', strtotime($_POST['FechaDel']))."' AND '".date('Y-m-d', strtotime($_POST['FechaAl']))."'";
	if(isset($_POST["CmbDireccion"]) and $_POST["CmbDireccion"]<>0) $condicion.=" AND not_solicitud.CveEntDireccion = ".$_POST["CmbDireccion"];
	if(isset($_POST["CmbEstatus"]) and $_POST["CmbEstatus"]<>0) $condicion.=" AND not_solicitud.Estatus = ".$_POST["CmbEstatus"];
?>
<div align="right"><a href="javascript:;" onClick="FormatoSolicitud()" style="cursor:pointer">Solicitud de mantenimiento</a></div>
<table class="newspaper-b" width="100%">
	<thead>
		<tr>
			<th width="5%">FOLIO</th>
            <th width="7%">FECHA RECIBIDO</th>
            <th width="15%">DIRECCI&Oacute;N</th>
			<th width="19%">DEPARTAMENTO</th>
            <th width="8%">TIPO DE PROBLEMA</th>
			<th width="22%">DESCRIPCI&Oacute;N DEL PROBLEMA</th>
            <th width="12%">SOLICITA</th>
			<th width="7%">FECHA DE ATENCI&Oacute;N</th>
            <th width="5%">EDITAR</th>
			<th width="5%">ELIMINAR</th>
		</tr>
	</thead>
	<tbody>
<?php	
	try {
          $pdo = Database::connect();
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $sql = "SELECT not_solicitud.*, DATE_FORMAT(FechaRecibida,'%d-%m-%Y') AS FechaRecibido_, DATE_FORMAT(FechaAtendida,'%d-%m-%Y') AS FechaAtendida_,  
		  				 direccion_cat.Nombre AS Direccion, IFNULL(departamento_cat.Nombre,IFNULL(OtroDepartamento,'')) as Departamento,
						 CASE not_solicitud.Estatus WHEN 1 THEN 'RECIBIDO' ELSE 'ATENDIDO' END AS EstatusSolicitud, LPAD(Folio,4,'0') AS Folio_
		  			FROM not_solicitud
					INNER JOIN direccion_cat ON direccion_cat.ClaveEntidad = not_solicitud.CveEntDireccion
					LEFT OUTER JOIN departamento_cat ON departamento_cat.ClaveEntidad = not_solicitud.CveEntDepartamento
					WHERE 1 $condicion
					ORDER BY FechaRecibida DESC, Folio DESC LIMIT $RegistrosAEmpezar,$RegistrosAMostrar";
		  $q = $pdo->prepare($sql);
          $q->execute(array());
		  $filas = $q->rowCount();
		  $CveSolicitudes = '';
		  
		  if($filas==0): echo '<tr><td colspan="9" align="center"><strong>NINGUN REGISTRO</strong></td></tr>';
		  else:
		  	 
		  	 $data = $q->fetchall(PDO::FETCH_ASSOC);
			  foreach($data as $Solicitud):
			  	 if($CveSolicitudes=='') $CveSolicitudes = $Solicitud['ClaveEntidad'];
				 else $CveSolicitudes .= ','.$Solicitud['ClaveEntidad'];
			  	 $tipoproblema = '';
				 if($Solicitud['Red']==1): $tipoproblema = 'RED'; endif;
				 if($Solicitud['Mantenimiento']==1): if($tipoproblema =='') $tipoproblema = 'MANTENIMIENTO'; else $tipoproblema .= ', MANTENIMIENTO'; endif;
				 if($Solicitud['Telefonia']==1): if($tipoproblema =='') $tipoproblema = 'TELEFONIA'; else $tipoproblema .= ', TELEFONIA'; endif;
				 if($Solicitud['Formateo']==1): if($tipoproblema =='') $tipoproblema = 'FORMATEO'; else $tipoproblema .= ', FORMATEO'; endif;
				 if($Solicitud['Comunicacion']==1): if($tipoproblema =='') $tipoproblema = 'COMUNICACION'; else $tipoproblema .= ', COMUNICACION'; endif;
				 if($Solicitud['Impresora']==1): if($tipoproblema =='') $tipoproblema = 'IMPRESORA'; else $tipoproblema .= ', IMPRESORA'; endif;
				 if($Solicitud['Asistencia']==1): if($tipoproblema =='') $tipoproblema = 'ASISTENCIA'; else $tipoproblema .= ', ASISTENCIA'; endif;
				 if($Solicitud['Otro']==1): if($tipoproblema =='') $tipoproblema = 'OTRO'; else $tipoproblema .= ', OTRO'; endif;

				 echo '<tr>
					<td>'.$Solicitud['Folio_'].'</td>
					<td>'.$Solicitud['FechaRecibido_'].'</td>
					<td>'.utf8_encode($Solicitud['Direccion']).'</td>
					<td>'.($Solicitud['Departamento']).'</td>
					<td>'.utf8_encode($tipoproblema).'</td>
					<td>'.utf8_encode($Solicitud['DescripcionProblema']).'</td>
					<td>'.$Solicitud['Solicita'].'</td>
					<td>'.$Solicitud['FechaAtendida_'].'</td>
					<td><a href="editarSolicitud.php?id='.$Solicitud['ClaveEntidad'].'"><img src="img/lapiz.png" style="cursor:pointer" title="Editar Solicitud"></a></td>
					<td><a href="eliminarSolicitud.php?id='.$Solicitud['ClaveEntidad'].'"><img src="img/basura.png" style="cursor:pointer" title="Eliminar Solicitud"></a></td>

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
		  /*$pdo = Database::connect();
		  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		  $sql = "SELECT not_solicitud.* 
				FROM not_solicitud
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
					}*/
											?>
										</div>
				</center>
			</td>
			<td colspan="2"><?php echo "Página ".$PagAct." de ".$PagUlt; ?></td>
		</tr>
	</tfoot>
</table>
