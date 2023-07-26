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
  $fechainicio=null;
  $fechafin=null;

  //CONEXIÓN BD
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT bitacora.*, DATE_FORMAT(periodo_de,'%d-%m-%Y') AS fecha_de, DATE_FORMAT(periodo_al,'%d-%m-%Y') AS fecha_al,
  empleado.*, CONCAT(nombre,' ',ApellidoPaterno,' ',ApellidoMaterno) as nom_empleado, vehiculo.*,CONCAT(marca,' ', modelo) AS marca FROM bitacora
  INNER JOIN bitacora ON empleado.NumeroControl
  INNER JOIN bitacora ON vehiculo.num_unidad
  where id_bitacora=?";
  $q = $pdo->prepare($sql);
  //OBTENCIÓN DE VALORES PARA AUTOCOMPLETADO DE TIPO
    try{
      $q->execute(array($id));
      $data = $q->fetchAll(PDO::FETCH_ASSOC);
      foreach($data as $Solicitud):
        $num_control=$Solicitud['operador'];
        $nombre=$Solicitud['nom_empleado'];
        $fechainicio=$Solicitud['fecha_de'];
        $fechafin=$Solicitud['fecha_al'];
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

  }catch (PDOException $e){
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
      <li><a href='admin/autos.php'>ADMINISTRADOR</a></li>
      <li class='active'><a href='index.php'>Editar Bitacora</a></li>
    </ul>
  </div>

  <div class="col-md-10" style="width: 100%;">
            <div class="col-md-12 panel">
                <div class="col-md-12 panel-heading">
                    <h4 class="Titulos">BITACORA</h4>
                </div>
                <div class="col-md-12 panel-body" style="padding-bottom:30px;">
                    <div class="col-md-12">
                        <form class="cmxform" id="form_solicitud" method="get" action="">
                            <input type="hidden" value="guardar_bitacora" id="funcion" name="funcion">
                            <div class="col-md-12" style="margin-top:40px !important;"><!--CENTRAL 0-->
                                <!--Numero de control 1-->
                                <div class="col-md-6" style="width: 18%;">
                                    <div class="form-group form-animate-text" id="N_C">
                                        <input list="idE" type="text" class="form-text" id="numero_control" name="numero_control" required>
                                        <datalist id="idE">
                                            <?php
                                                try {
                                                    $pdo = Database::connect();
                                                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                                    $sql = "SELECT NumeroControl  FROM empleado";
                                                    $q = $pdo->prepare($sql);
                                                    $q->execute(array());
                                                    $data = $q->fetchall(PDO::FETCH_ASSOC);
                                                    foreach($data as $row)
                                                        echo '<option value="'.$row['NumeroControl'].'"></option>';
                                                }catch(PDOException $e){
                                                    echo 'Error: ' . $e->getMessage();
                                                }
                                            ?>
                                        </datalist>
                                        <span class="bar"></span><label>Numero de control*</label>
                                    </div>
                                </div>
                                <div class="col-md-6" style="width: 32%;"><!--IZQUIERDA 1-->
                                    <!--OPERADOR-->
                                    <div class="form-group form-animate-text" id="N_O" style="opacity: 0;">
                                        <input type="text" class="form-text" id="N_operador" name="N_operador">
                                        <span class="bar"></span>
                                        <label>Operador</label>
                                    </div>
                                </div><!--IZQUIERDA 1-->
                                
                                <!--PERIODO DEL-->
                                <div class="col-md-6" style="width: 25%;">
                                    <label>Del: </label>
                                    <input type="text" class="form-text" id="FechaDel" name="FechaDel" value="<?php echo $fechainicio;?>">
                                </div>
                                <!--PERIODO AL-->
                                <div class="col-md-6" style="width: 25%;">
                                    <label>Al: </label>
                                    <input type="text" class="form-text" id="FechaAl" name="FechaAl" value="<?php echo $fechafin;?>">
                                </div>
                            </div><!--END CENTRAL 0-->
                            
                            <div class="col-md-12" style="margin-top:40px !important;"><!--CENTRAL 1-->
                                <!--UNIDAD DE RESGUARDO-->
                                <div class="col-md-6" style="width: 25%;">
                                    <div class="form-group form-animate-text" id="U_r">
                                        <input list="idV" type="text" class="form-text" id="idVehiculo" name="idVehiculo" required>
                                        <datalist id="idV">
                                            <?php
                                                try {
                                                    $pdo = Database::connect();
                                                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                                    $sql = "SELECT num_unidad  FROM vehiculo";
                                                    $q = $pdo->prepare($sql);
                                                    $q->execute(array());
                                                    $data = $q->fetchall(PDO::FETCH_ASSOC);
                                                        
                                                    foreach($data as $row)
                                                        echo '<option value="'.$row['num_unidad'].'"></option>';
                                                    
                                                }catch(PDOException $e){
                                                    echo 'Error: ' . $e->getMessage();
                                                }
                                            ?>
                                        </datalist>
                                        <span class="bar"></span><label>Unidad de resguardo*</label>
                                    </div>
                                </div>

                                <!--MARCA/MODELO-->
                                <div class="col-md-6" style="width: 25%;">
                                    <div class="form-group form-animate-text" id="M_m" style="opacity: 0;">
                                        <input type="text" class="form-text" id="marca_modelo" name="marca_modelo">
                                        <span class="bar"></span><label>Marca/Modelo</label>
                                    </div>
                                </div>
                                <!--PLACAS-->
                                <div class="col-md-6" style="width: 25%;">
                                    <div class="form-group form-animate-text" id="p" style="opacity: 0;">
                                        <input type="text" class="form-text" id="placas" name="placas">
                                        <span class="bar"></span><label>Placas</label>
                                    </div>
                                </div>
                                <!--TIPO DE COMBUSTIBLE-->
                                <div class="col-md-6" style="width: 25%;">
                                    <div class="form-group form-animate-text" id="comb" style="opacity: 0;">
                                        <input type="text" class="form-text" id="combustible" name="combustible">
                                        <span class="bar"></span><label>Tipo de combustible</label>
                                    </div>
                                </div>
                            </div><!--END CENTRAL 1-->
                                
                            <div class="col-md-12" style="margin-top:40px !important;"> <!--CENTRAL 2-->
                                <!--TIPO DE COMBUSTIBLE-->
                                <div class="col-md-6" style="width: 20%;">
                                    <div class="form-group form-animate-text" id="T_c">
                                        <input type="text" class="form-text" oninput="this.value = this.value.toUpperCase()" id="tipo_comb" name="tipo_combustible">
                                        <span class="bar"></span>
                                        <label>Combustible</label>
                                    </div>
                                </div>

                                <!--CADA VALE-->
                                <div class="col-md-6" style="width: 20%;">
                                    <div class="form-group form-animate-text" id="C_v">
                                        <input type="text" class="form-text" id="vale" name="cada_vale">
                                        <span class="bar"></span>
                                        <label>Cada vale</label>
                                    </div>
                                </div>

                                <!--FECHA CARGA-->
                                <div class="col-md-6" style="width: 20%;">
                                    <div class="form-group form-animate-text" id="F_c">
                                        <input type="text" class="form-text" id="fecha_r" name="fecha_carga">
                                        <span class="bar"></span>
                                        <label>Fecha de carga</label>
                                    </div>
                                </div>

                                <!--FOLIO-->
                                <div class="col-md-6" style="width: 20%;">
                                    <div class="form-group form-animate-text" id="fol">
                                        <input type="text" class="form-text" id="folio" name="folio">
                                        <span class="bar"></span><label>FOLIO</label>
                                    </div>
                                </div>

                                <!--MONTO-->
                                <div class="col-md-6" style="width: 20%;">
                                    <div class="form-group form-animate-text" id="mont">
                                        <input type="text" class="form-text" oninput="this.value = this.value.replace(/\D/g, '');" id="monto" name="monto">
                                        <span class="bar"></span><label>MONTO</label>
                                    </div>
                                </div>
                            </div><!--END CENTRAL 2-->

                            <div class="col-md-12"><!--Boton siguiente-->
                                <div id="sig_bitacora" class="btn-guardar" style="user-select: none; background: #172e5c; width: 75px; height: 35px; text-align: center; padding-top: 8px; cursor: pointer;">Siguiente</div>
                            </div>

                            <div class="col-md-12" id="resultados"></div>

                        </form>
                    </div>        
                </div>

                <!-- inicio de formularios para recorrido -->
                <div class="col-md-12 panel-body">
                    <div id="recorridos" style="display: none;"></div>
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