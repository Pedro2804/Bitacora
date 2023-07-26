<?php
include 'config/conexion.php';
  //OBTENER ID DEL URL PARA LA EDICIÓN
$id = $_GET['id'];
?>
<?php

//DECLARACIÓN DE VALORES PARA AUTOCOMPLETADO
$combustible='';
$vale='';
$folio='';
$monto='';

//CONEXIÓN BD
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT bitacora.*, DATE_FORMAT(periodo_de,'%d-%m-%Y') AS fecha_de,DATE_FORMAT(periodo_al,'%d-%m-%Y') AS fecha_al,
empleado.*, CONCAT(nombre,' ',ApellidoPaterno,' ',ApellidoMaterno) as nom_empleado, vehiculo.*,CONCAT(marca,' ', modelo) AS marca FROM bitacora
INNER JOIN empleado ON bitacora.operador
INNER JOIN vehiculo ON bitacora.NoUnidad
where id_bitacora=?";
$q = $pdo->prepare($sql);
//OBTENCIÓN DE VALORES PARA AUTOCOMPLETADO DE TIPO
try
{
    $q->execute(array($id));
    $data = $q->fetchAll(PDO::FETCH_ASSOC);
    foreach($data as $Solicitud):
    $num_control=$Solicitud['operador'];
    $nombre=$Solicitud['nom_empleado'];
    $fecha_del=$Solicitud['fecha_de'];
    $fecha_al=$Solicitud['fecha_al'];
    $NoUnidad=$Solicitud['NoUnidad'];
    $marca=$Solicitud['marca'];
    $placas=$Solicitud['placas'];
    $tipo_comb=$Solicitud['tipo_combustible'];

    if($Solicitud['combustible'])
      $combustible=$Solicitud['combustible'];
    if($Solicitud['cada_vale'])
      $vale=$Solicitud['cada_vale'];
    if($Solicitud['folio'])
      $folio=$Solicitud['folio'];
    if($Solicitud['monto'])
      $monto=$Solicitud['monto'];
    endforeach;

} 
catch (PDOException $e)
{
echo 'Error: ' . $e->getMessage();

}
?>

<?php 
//QUE DIOS ME PERDONE POR ESTE SPAGHETTI DE CÓDIGO
//OBTENER VALORES DE :(
  try {
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM recorrido WHERE bitacora = ?";
    //POR SI TE LLEGA A SERVIR ESTE TIPO DE CONSULTAS :) soy el nuevo que lo quito :)
    /*$sql = "SELECT not_solicitud.*, DATE_FORMAT(FechaRecibida,'%d-%m-%Y') AS FechaRecibido_, DATE_FORMAT(FechaAtendida,'%d-%m-%Y') AS FechaAtendida_,  
                direccion_cat.Nombre AS Direccion, IFNULL(departamento_cat.Nombre,IFNULL(OtroDepartamento,'')) as Departamento,
                CASE not_solicitud.Estatus WHEN 1 THEN 'RECIBIDO' ELSE 'ATENDIDO' END AS EstatusSolicitud, LPAD(Folio,4,'0') AS Folio_
                FROM not_solicitud
              INNER JOIN direccion_cat ON direccion_cat.ClaveEntidad = not_solicitud.CveEntDireccion
              LEFT OUTER JOIN departamento_cat ON departamento_cat.ClaveEntidad = not_solicitud.CveEntDepartamento
              WHERE 1 AND not_solicitud.ClaveEntidad=".$id."";*/ //POR SI TE LLEGA A SERVIR ESTE TIPO DE CONSULTAS :)
      $q = $pdo->prepare($sql);
      $q->execute(array($id));
      $filas = $q->rowCount();
      $data = $q->fetchall(PDO::FETCH_ASSOC);
			/*foreach($data as $Solicitud):
        $urgencia=$Solicitud['NivelUrgencia'];
        $nombreDireccion=$Solicitud['Direccion'] ;
        $numeroDireccion=$Solicitud['CveEntDireccion'];
        $nombreDepartamento=($Solicitud['Departamento']);
        $numeroDepartamento=$Solicitud['CveEntDepartamento'];
      endforeach;*/
    }catch(PDOException $e)
    {
       echo 'Error: ' . $e->getMessage();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  
  <meta charset="utf-8">
  <meta name="keyword" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Editar Bitacora</title>

  <!-- start: Css -->
  <link rel="stylesheet" type="text/css" href="asset/css/bootstrap.min.css">

  <!-- plugins -->
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/font-awesome.min.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/animate.min.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/nouislider.min.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/select2.min.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/ionrangeslider/ion.rangeSlider.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/ionrangeslider/ion.rangeSlider.skinFlat.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/bootstrap-material-datetimepicker.css"/>
  <link href="asset/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="css/flexselect.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/estilos.css" />
  <!-- end: Css -->
        <!-- start: sweetalert2 -->
    <script src="scriprts/sweetalert2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style/sweetalert2.min.css">


  <link rel="shortcut icon" href="img/logodifblanco.png">
</head>

<body id="mimin" class="dashboard">
     <div class="jumbotron">
        <img src="img/logodifblanco.png" class="logo_dif">
    </div>
    <div id='cssmenu'>  
            <ul>
                <li><a href='index.php'>Nueva Bitacora</a></li>
                <li><a href='Busqueda.php'>Ver Bitacoras</a></li>
                <!--<li><a href='busquedanoticia.php'>Ver Noticia</a></li>
                <li><a href='nueva_noticia.php'>NUEVA NOTICIA</a></li>-->
                <li><a href='admin/autos.php'>ADMINISTRADOR</a></li>
                <li class='active'><a href='index.php'>Editar Bitacora</a></li>
            </ul>
        </div> 
    </div> 
                <div class="col-md-10" style="width: 100%;">
                  <div class="col-md-12 panel">
                    <div class="col-md-12 panel-heading">
                      <h4 class="Titulos">EDITAR BITACORA</h4>
                    </div>
                    <div class="col-md-12 panel-body" style="padding-bottom:30px;">
                      <div class="col-md-12">

                        <form class="cmxform" id="form_solicitudEditar" method="get" action="">
                            <input type="hidden" value="editar_solicitudTest" id="funcion" name="funcion">
                        <?php echo '<input type="hidden" id="clave_editar" name="clave_editar" value="'.$id.'">' ?> 
                          <div class="col-md-6">
                            <div class="form-group form-animate-text" style="margin-top:40px !important;">
                          
                            <!-- EL SARGAZO ESTÁ ACABANDO CON LAS COSTAS MEXICANAS  --> <!--EQUISDE-->
                            <?php
                                    try {
                                            $pdo = Database::connect();
                                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                            $sql = "SELECT FechaRecibida,
                                            DATE_FORMAT(FechaRecibida,'%d-%m-%Y') AS FechaRecibido_
                                            FROM not_solicitud
                                            WHERE ClaveEntidad=$id ";
                                            $q = $pdo->prepare($sql);
                                            $q->execute(array());
                                            $data = $q->fetchall(PDO::FETCH_ASSOC);
                                            $CveSolicitudes = '';
                                            
                                            foreach($data as $Solicitud):
                                              echo'<input type="text" class="form-text" id="fecha_r" name="fecha_r" value ="'.$Solicitud['FechaRecibida'].'"required>';
                                            endforeach;
                                          }
                                    catch(PDOException $e)
                                    {
                                        echo 'Error: ' . $e->getMessage();
                                    }
                                      ?>

                              <span class="bar"></span>
                              <label>*Fecha recibido</label>
                            </div>
                            <div class="form-group form-animate-text" style="margin-top:40px !important;">
                                <select class="form-text" id="direccion" name="direccion">
                                    <option value="0"> *- Dirección -</option>
                                    <option value="8">PRESIDENCIA</option>
                                    <option value="<?php echo ($numeroDireccion)?>" selected hidden><?php echo($nombreDireccion)?></option>
                                    <?php
                                    try {
                                            $pdo = Database::connect();
                                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                            $sql = "select ClaveEntidad, Nombre from direccion_cat where Estatus = 1 order by Nombre";
                                            $q = $pdo->prepare($sql);
                                            $q->execute(array());
                                            $data = $q->fetchall(PDO::FETCH_ASSOC);
                                              foreach($data as $row)
                                              
                                                echo '<option value="'.$row['ClaveEntidad'].'">'.$row['Nombre'].'</option>';
                                        }
                                    catch(PDOException $e)
                                    {
                                        echo 'Error: ' . $e->getMessage();
                                    }
                                      ?>
                                </select>
                                <span class="bar"></span>
                            </div>
                            <div class="form-group form-animate-text" style="margin-top:40px !important;">
                                <div class="form-text">*Nivel de urgencia:</div> 
                              <span class="bar"></span>
                              <label></label>
                            </div>
                              
                            <div class="form-group form-animate-text" style="margin-top:40px !important; display:none;" >
                              <input type="text" class="form-text" id="contacto" name="contacto">
                              <span class="bar"></span>
                              <label>Nombre de Contacto</label>
                            </div>
                                <div class="col-md-6 panel" style="padding:20px;padding-bottom:0px;">
                                <div class="form-group form-animate-checkbox">
                                  <input type="checkbox" class="checkbox" id="red" name="red" <?php echo($categoria[0]=="1")? "checked" : "" ;  ?>>
                                  <label>Red</label>
                                </div>
                                </div>
                                <div class="col-md-6 panel" style="padding:20px;padding-bottom:0px;">
                                <div class="form-group form-animate-checkbox">
                                  <input type="checkbox" class="checkbox" id="mantenimiento" name="mantenimiento" <?php echo($categoria[1]=="1")? "checked" : "" ;  ?>>
                                  <label>Mantenimiento</label>
                                </div>
                                </div>
                               <div class="col-md-6 panel" style="padding:20px;padding-bottom:0px;">
                                <div class="form-group form-animate-checkbox">
                                  <input type="checkbox" class="checkbox" id="comunicacion" name="comunicacion" <?php echo($categoria[4]=="1")? "checked" : "" ;  ?>>
                                  <label>Comunicación</label>
                                </div>
                                </div>
                                <div class="col-md-6 panel" style="padding:20px;padding-bottom:0px;">
                                <div class="form-group form-animate-checkbox">
                                  <input type="checkbox" class="checkbox" id="impresora" name="impresora" <?php echo($categoria[5]=="1")? "checked" : "" ;  ?>>
                                    <label>Impresora</label>
                                </div>
                                </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group form-animate-text" style="margin-top:40px !important;">
                              
                              <!-- CARGAR LOS VALORES DE FECHA RECIBIDO  -->
                              <?php
                                try {
                                  $pdo = Database::connect();
                                  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                  $sql = "SELECT FechaDocumento,
                                  DATE_FORMAT(FechaDocumento,'%d-%m-%Y') AS FechaDocumento_
                                  FROM not_solicitud
                                  WHERE ClaveEntidad=$id ";
                                  $q = $pdo->prepare($sql);
                                  $q->execute(array());
                                  $data = $q->fetchall(PDO::FETCH_ASSOC);
                                  $CveSolicitudes = '';
                                  
                                  foreach($data as $Solicitud):
                                    echo'<input type="text" class="form-text" id="fecha_d" name="fecha_d" value ="'.$Solicitud['FechaDocumento'].'"required>';
                                  endforeach;
                                }
                                catch(PDOException $e)
                                {
                                    echo 'Error: ' . $e->getMessage();
                                }
                              ?>
                              
                              
                              <span class="bar"></span>
                              <label>*Fecha Documento</label>
                            </div>
                            <div class="form-group form-animate-text" style="margin-top:40px !important;">
                              <input list="dep" type="text" class="form-text" id="departamento" name="departamento" placeholder="<?php echo $nombreDepartamento?>"  >
                                <datalist id="dep">
                                
                                </datalist>
                                
                              <span class="bar"></span>
                              <label active>Departamento</label>
                            </div>
                        <div class="form-animate-radio">
                          
                          <label class="radio">
                            <input id="urgencia" name="urgencia" value="1" type="radio" <?php echo($urgencia==1)? "checked" : "" ;  ?>/>
                            <span class="outer">
                              <span class="inner"></span></span> Alta 
                            </label>
                             <label class="radio">
                            <input id="urgencia" name="urgencia" value="2" type="radio" <?php echo($urgencia==2)? "checked" : "" ;  ?> />
                            <span class="outer">
                              <span class="inner"></span></span> Media
                            </label>
                            <label class="radio">
                            <input id="urgencia" name="urgencia" value="3" type="radio" <?php echo($urgencia==3)? "checked" : "" ;  ?>/>
                            <span class="outer">
                              <span class="inner"></span></span> Baja
                            </label>
                          </div>
                             <div class="form-group form-animate-text" style="margin-top:40px !important; display:none;">
                              <input type="text" class="form-text" id="telefono" name="telefono">
                              <span class="bar"></span>
                              <label>Telefono y Extención</label>
                            </div>
                               <div class="col-md-6 panel" style="padding:20px;padding-bottom:0px;">
                                <div class="form-group form-animate-checkbox">
                                  <input type="checkbox" class="checkbox" id="telefonia" name="telefonia" <?php echo($categoria[2]=="1")? "checked" : "" ;  ?>>
                                  <label>Telefonía</label>
                                </div>
                                </div>
                              <div class="col-md-6 panel" style="padding:20px;padding-bottom:0px;">
                                <div class="form-group form-animate-checkbox">
                                  <input type="checkbox" class="checkbox" id="formateo" name="formateo" <?php echo($categoria[3]=="1")? "checked" : "" ;  ?>>
                                  <label>Formateo</label>
                                </div>
                                </div>
                               <div class="col-md-6 panel" style="padding:20px;padding-bottom:0px;">
                                <div class="form-group form-animate-checkbox">
                                  <input type="checkbox" class="checkbox" id="asistencia_t" name="asistencia_t" <?php echo($categoria[6]=="1")? "checked" : "" ;  ?>>
                                  <label>Asistencia Tecnica</label>
                                </div>
                                </div>
                              <div class="col-md-6 panel" style="padding:20px;padding-bottom:0px;">
                                <div class="form-group form-animate-checkbox">
                                  <input type="checkbox" class="checkbox" id="otro" name="otro" <?php echo($categoria[7]=="1")? "checked" : "" ;  ?>>
                                  <label>Otro</label>
                                </div>
                                </div>
                          </div>
                            
                          <div class="col-md-12">
                            <div class="form-group form-animate-text" style="margin-top:40px !important;">
                              <input type="text" class="form-text" id="descripcion" name="descripcion"  <?php echo('value="'.$descripcion.'"'); ?> required>
                              <span class="bar"></span>
                              <label>*Descripcion del problema</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-animate-text" style="margin-top:40px !important;">
                               <input list="soli" type="text" class="form-text" id="solicita" name="solicita"  <?php echo('value="'.$solicita.'"'); ?> >
                                <datalist id="soli">
                                
                                    <?php
                                    try {

                                    
                                            $pdo = Database::connect();
                                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                            $sql = "SELECT CONCAT(nombre,' ',ApellidoPaterno,' ',ApellidoMaterno) as nombre_empleado  FROM EMPLEADO";
                                            $q = $pdo->prepare($sql);
                                            $q->execute(array());
                                            $data = $q->fetchall(PDO::FETCH_ASSOC);
                                              foreach($data as $row)
                                                echo '<option value="'.$row['nombre_empleado'].'"></option>';
                                        }
                                    catch(PDOException $e)
                                    {
                                        echo 'Error: ' . $e->getMessage();
                                    }
                                      ?>
                                </datalist>
                              <span class="bar"></span>
                              <label>Solicita</label> 
                            </div>
                             <div class="form-group form-animate-text" style="margin-top:40px !important;">
                             <select class="form-text" id="entrega" name="entrega">
                                    <option value="0"><?php echo($entrega); ?> </option>
                                    <?php
                                    try {
                                            $pdo = Database::connect();
                                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                            $sql = "SELECT ClaveEntidad,CONCAT(nombre,' ',ApellidoPaterno,' ',ApellidoMaterno) as nombre_empleado  FROM EMPLEADO WHERE ClaveEntidad=380 OR ClaveEntidad=138 OR ClaveEntidad=134 OR ClaveEntidad=435";
                                            $q = $pdo->prepare($sql);
                                            $q->execute(array());
                                            $data = $q->fetchall(PDO::FETCH_ASSOC);
                                              foreach($data as $row)
                                                echo '<option value="'.$row['ClaveEntidad'].'">'.$row['nombre_empleado'].'</option>';
                                        }
                                    catch(PDOException $e)
                                    {
                                        echo 'Error: ' . $e->getMessage();
                                    }
                                    ?>
                                </select>
                              <span class="bar"></span>
                              <label>Entrega</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-animate-text" style="margin-top:40px !important;">
                              <input list="vis" type="text" class="form-text" id="visto" name="visto" <?php echo('value="'.$vobo.'"'); ?> >
                                <datalist id="vis">
                                    <?php
                                    try {
                                            $pdo = Database::connect();
                                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                            $sql = "SELECT CONCAT(nombre,' ',ApellidoPaterno,' ',ApellidoMaterno) as nombre_empleado  FROM EMPLEADO";
                                            $q = $pdo->prepare($sql);
                                            $q->execute(array());
                                            $data = $q->fetchall(PDO::FETCH_ASSOC);
                                              foreach($data as $row)
                                                echo '<option value="'.$row['nombre_empleado'].'"></option>';
                                        }
                                    catch(PDOException $e)
                                    {
                                        echo 'Error: ' . $e->getMessage();
                                    }
                                      ?>
                                </datalist>
                              <span class="bar"></span>
                              <label>VO. BO.</label>
                            </div>
                           <div class="form-group form-animate-text" style="margin-top:40px !important;">
                              <input list="reci" type="text" class="form-text" id="recibe" name="recibe" <?php echo('value="'.$recibe.'"'); ?>>
                                <datalist id="reci">
                                    <?php
                                    try {
                                            $pdo = Database::connect();
                                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                            $sql = "SELECT CONCAT(nombre,' ',ApellidoPaterno,' ',ApellidoMaterno) as nombre_empleado  FROM EMPLEADO";
                                            $q = $pdo->prepare($sql);
                                            $q->execute(array());
                                            $data = $q->fetchall(PDO::FETCH_ASSOC);
                                              foreach($data as $row)
                                                echo '<option value="'.$row['nombre_empleado'].'"></option>';
                                        }
                                    catch(PDOException $e)
                                    {
                                        echo 'Error: ' . $e->getMessage();
                                    }
                                      ?>
                                </datalist>
                              <span class="bar"></span>
                              <label>Recibe de Conformidad</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                             <input class="btn-guardar" type="submit" value="Editar" >
                        </div>
                      </form>

                    </div>
                  </div>
                </div>
              </div>
<!-- start: Javascript -->
<script src="asset/js/jquery.min.js"></script>
<script src="asset/js/jquery.ui.min.js"></script>
<script src="asset/js/bootstrap.min.js"></script>

<!-- plugins -->
<script src="asset/js/plugins/moment.min.js"></script>
<script src="asset/js/plugins/jquery.knob.js"></script>
<script src="asset/js/plugins/ion.rangeSlider.min.js"></script>
<script src="asset/js/plugins/bootstrap-material-datetimepicker.js"></script>
<script src="asset/js/plugins/jquery.nicescroll.js"></script>
<script src="asset/js/plugins/jquery.mask.min.js"></script>
<script src="asset/js/plugins/select2.full.min.js"></script>
<script src="asset/js/plugins/nouislider.min.js"></script>
<script src="asset/js/plugins/jquery.validate.min.js"></script>
<script src="js/liquidmetal.js" type="text/javascript"></script>
<script src="js/jquery.flexselect.js" type="text/javascript"></script>

<!-- custom -->
<script src="scriprts/index.js"></script>
<script>
$( document ).ready(function()
{
    
});
</script>
</body>
</html>
