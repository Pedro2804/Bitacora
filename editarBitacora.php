<?php
include 'config/conexion.php';
  //OBTENER ID DEL URL PARA LA EDICIÓN
$id = $_GET['id'];
?>
<?php
  //DECLARACIÓN DE VALORES PARA AUTOCOMPLETADO
  $combustible='';
  $folio='';
  $monto='';
  $fecha_carga=null;
  $fechainicio=null;
  $fechafin=null;



  //CONEXIÓN BD
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT bitacora.*, DATE_FORMAT(periodo_de,'%d-%m-%Y') AS fecha_de, DATE_FORMAT(periodo_al,'%d-%m-%Y') AS fecha_al,
  empleado.*, CONCAT(nombre,' ',ApellidoPaterno,' ',ApellidoMaterno) as nom_empleado, vehiculo.*,CONCAT(marca,' ', modelo) AS marca FROM bitacora
  INNER JOIN empleado ON empleado.NumeroControl = bitacora.operador
  INNER JOIN vehiculo ON vehiculo.num_unidad = bitacora.NoUnidad
  WHERE id_bitacora=?";
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

        if($Solicitud['fecha_carga'])
          $fecha_carga=$Solicitud['fecha_carga'];
        if($Solicitud['combustible'])
          $combustible=$Solicitud['combustible'];
        if($Solicitud['folio'])
          $folio=$Solicitud['folio'];
        if($Solicitud['monto'])
          $monto=$Solicitud['monto'];
      endforeach;
      $llenar = true;
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
      $datos = array();
	    foreach($data as $Solicitud):
            $id_recorrido=$Solicitud['id_recorrido'];
            $dia = $Solicitud['dia_semana'];
            $km_inicial=$Solicitud['km_inicial'];
            $km_final=$Solicitud['km_final'] ;
            $salida=$Solicitud['salida'];
            $recorrido=$Solicitud['recorrido'];
            
            // verificamos si aun no existe el dia en el arreglo y creamos un subarreglo de ese dia
            if (!isset($datos[$dia]))
                $datos[$dia] = array();

            // Despues agregamos la informacion de ese dia
            $datos[$dia] = array(
                "id_recorrido" => $id_recorrido,
                "km_inicial" => $km_inicial,
                "km_final" => $km_final,
                "salida" => $salida,
                "recorrido" => $recorrido
            );
        endforeach;
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

  <link rel="shortcut&#x20;icon" href="img/favicon.ico" type="image/ico" />
    <link rel="icon" href="img/favicon.ico" type="image/ico" />
    <!-- Apple Touch Icons -->    
    <link rel="apple-touch-icon" href="img/favicon.ico">
    <link rel="apple-touch-icon" sizes="72x72" href="img/favicon.ico" />
    <link rel="apple-touch-icon" sizes="114x114" href="img/favicon.ico" />
    <link rel="apple-touch-icon" sizes="144x144" href="img/favicon.ico" />
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
                        <form class="cmxform" id="form_editar_bitacora" method="get" action="">
                            <input type="hidden" value="editar_bitacora" id="funcion" name="funcion">
                            <input type="hidden" value="<?php echo $id; ?>" id="id_bitacora" name="id_bitacora_form">
                            <div class="col-md-12" style="margin-top:40px !important;"><!--CENTRAL 0-->
                                <!--Numero de control 1-->
                                <div class="col-md-6" style="width: 16%;">
                                    <div class="form-group form-animate-text" id="N_C">
                                        <input list="idE_e" type="text" class="form-text" id="numero_control_e" name="numero_control_e" required>
                                        <datalist id="idE_e">
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
                                <div class="col-md-6" style="width: 30%;"><!--IZQUIERDA 1-->
                                    <!--OPERADOR-->
                                    <div class="form-group form-animate-text" id="N_O_e" style="opacity: 1;">
                                        <input type="text" class="form-text" id="N_operador_e" name="N_operador_e" value="<?php echo $nombre;?>" onclick="this.blur();" >
                                        <span class="bar"></span>
                                        <label>Operador</label>
                                    </div>
                                </div><!--IZQUIERDA 1-->
                                
                                <!--PERIODO DEL-->
                                <div class="col-md-6" style="width: 30%;">
                                    <label>Fecha de Recorrido Del: </label>
                                    <input type="text" class="form-text" id="FechaDel_e" name="FechaDel_e" value="<?php echo $fechainicio;?>" disabled>
                                </div>
                                <!--PERIODO AL-->
                                <div class="col-md-6" style="width: 24%;">
                                    <label>Al: </label>
                                    <input type="text" class="form-text" id="FechaAl_e" name="FechaAl_e" value="<?php echo $fechafin;?>" disabled>
                                </div>
                            </div><!--END CENTRAL 0-->
                            
                            <div class="col-md-12" style="margin-top:40px !important;"><!--CENTRAL 1-->
                                <!--UNIDAD DE RESGUARDO-->
                                <div class="col-md-6" style="width: 25%;">
                                    <div class="form-group form-animate-text" id="U_r_e">
                                        <input list="idV_e" type="text" class="form-text" id="idVehiculo_e" onclick="this.blur();" name="idVehiculo_e" value="<?php echo $NoUnidad;?>" required>
                                        <datalist id="idV_e">
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
                                    <div class="form-group form-animate-text" id="M_m_e" style="opacity: 1;">
                                        <input type="text" class="form-text" id="marca_modelo_e" name="marca_modelo_e" value="<?php echo $marca;?>" onclick="this.blur();">
                                        <span class="bar"></span><label>Marca/Modelo</label>
                                    </div>
                                </div>
                                <!--PLACAS-->
                                <div class="col-md-6" style="width: 25%;">
                                    <div class="form-group form-animate-text" id="p_e" style="opacity: 1;">
                                        <input type="text" class="form-text" id="placas_e" name="placas_e" value="<?php echo $placas;?>" onclick="this.blur();">
                                        <span class="bar"></span><label>Placas</label>
                                    </div>
                                </div>
                                <!--TIPO DE COMBUSTIBLE-->
                                <div class="col-md-6" style="width: 25%;">
                                    <div class="form-group form-animate-text" id="comb_e" style="opacity: 1;">
                                        <input type="text" class="form-text" id="combustible_e" name="combustible_e" value="<?php echo $tipo_comb;?>" onclick="this.blur();">
                                        <span class="bar"></span><label>Tipo de combustible</label>
                                    </div>
                                </div>
                            </div><!--END CENTRAL 1-->
                                
                            <div class="col-md-12" style="margin-top:40px !important;"> <!--CENTRAL 2-->
                                <!--TIPO DE COMBUSTIBLE-->
                                <div class="col-md-6" style="width: 25%;">
                                    <div class="form-group form-animate-text" id="T_c_e">
                                        <input type="text" class="form-text" oninput="this.value = this.value.toUpperCase()" id="tipo_comb_e" name="tipo_combustible_e" >
                                        <span class="bar"></span>
                                        <label>Combustible</label>
                                    </div>
                                </div>

                                <!--FECHA CARGA-->
                                <div class="col-md-6" style="width: 25%;">
                                    <div class="form-group form-animate-text" id="F_c_e">
                                        <input type="text" class="form-text" id="fecha_r_e" name="fecha_carga_e">
                                        <span class="bar"></span>
                                        <label>Fecha de carga</label>
                                    </div>
                                </div>

                                <!--FOLIO-->
                                <div class="col-md-6" style="width: 25%;">
                                    <div class="form-group form-animate-text" id="fol_e">
                                        <input type="text" class="form-text" id="folio_e" name="folio_e">
                                        <span class="bar"></span><label>FOLIO</label>
                                    </div>
                                </div>

                                <!--MONTO-->
                                <div class="col-md-6" style="width: 25%;">
                                    <div class="form-group form-animate-text" id="mont_e">
                                        <input type="text" class="form-text" oninput="this.value = this.value.replace(/\D/g, '');" id="monto_e" name="monto_e">
                                        <span class="bar"></span><label>MONTO</label>
                                    </div>
                                </div>
                            </div><!--END CENTRAL 2-->

                            <div class="col-md-12"><!--Boton siguiente-->
                                <div class="col-md-6">
                                    <div id="sig_bitacora_e" class="btn-guardar" style="user-select: none; background: #172e5c; width: 75px; height: 35px; text-align: center; padding-top: 8px; cursor: pointer;">Siguiente</div>
                                </div>
                                <div class="col-md-6">
                                    <div id="cancelar_e" class="btn-guardar" style="user-select: none; background: red; width: 75px; height: 35px; text-align: center; padding-top: 8px; cursor: pointer;">Cancelar</div>
                                </div>
                            </div>

                            <div class="col-md-12" id="resultados"></div>
                        </form>
                    </div>        
                </div>

                <!-- inicio de formularios para recorrido -->
                <div class="col-md-12 panel-body">
                    <div id="recorridos_e" style="display: none;"></div>
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
            $(function() {
                var f = new Date();
                fech=f.getDate()+"-"+(f.getMonth() +1)+"-"+f.getFullYear();

                //Paginacion(1);
                $("#FechaDel_e").datepicker({
                    maxDate: fech,
                    monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septimbre", "Octubre", "Noviembre", "Diciembre" ],
                    monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
                    dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
                    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
                    dateFormat: 'dd-mm-yy',
                    yearRange: '-10:+0',
                    onSelect: obtenerResultados
                });
                $("#FechaAl_e").datepicker({
                    maxDate: fech,
                    monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septimbre", "Octubre", "Noviembre", "Diciembre" ],
                    monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
                    dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
                    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
                    dateFormat: 'dd-mm-yy',
                    yearRange: '-10:+0',
                    onSelect: obtenerResultados
                });

            });
        </script>

    <!-- custom -->
        <script src="scriprts/index.js"></script>
        <script src="scriprts/formRecorrido.js"></script>
        <script>
            $( document ).ready(function(){});
        </script>
        <script>
        // Función para obtener los resultados en base a las fechas seleccionadas
        function obtenerResultados() {
            var fechaDel = document.getElementById('FechaDel_e').value;
            var fechaAl = document.getElementById('FechaAl_e').value;
            // Realiza una solicitud AJAX al servidor
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'procesarE.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Muestra los resultados en el contenedor de resultados
                document.getElementById('recorridos_e').innerHTML = xhr.responseText;
                if(document.getElementsByClassName("tablinks_e").length>1){
                    openTab_e(document.getElementsByClassName("tablinks_e")[0].value);
                    document.getElementsByClassName("tablinks_e")[0].className += " active";
                }else if(document.getElementsByClassName("tablinks_e").length==1){
                    document.getElementById("boton_guardar_e0").style.display = "block";
                    document.getElementById("btn_sig_e0").style.display = "none";
                    openTab_e(document.getElementsByClassName("tablinks_e")[0].value);
                    document.getElementsByClassName("tablinks_e")[0].className += " active";
                }else{
                    $('#sig_bitacora_e').css("display", "block");
                    swal({
                        type: 'error',
                        title: 'Rango de fecha no válido',
                        timer: 1000,
                        showConfirmButton: false
                    });
                    setTimeout(function() {
                        document.location.href = 'editarBitacora.php';
                    }, 1100);
                }
            }
            };
             
            xhr.send('FechaDel_e=' + encodeURIComponent(fechaDel) + '&FechaAl_e=' + encodeURIComponent(fechaAl));
        }
        // Obtiene los resultados al cargar la página
        obtenerResultados();
    </script>

<script type="text/javascript"> //ENVIAMOS EL ARREGLO A JS SCRIPT PARA PODER MANIPULARLO
    datos = Array(<?php echo json_encode($datos);?>);
    id_bitacora = <?php echo json_encode($id);?>;

    document.getElementById("numero_control_e").value = <?php echo json_encode($num_control);?>;
    document.getElementById("tipo_comb_e").value = <?php echo json_encode($combustible);?>;
    document.getElementById("fecha_r_e").value = <?php echo json_encode($fecha_carga);?>;
    document.getElementById("folio_e").value = <?php echo json_encode($folio);?>;
    document.getElementById("monto_e").value = <?php echo json_encode($monto);?>;
</script>

<style>
    .tab_e {
        overflow: hidden;
        border: 1px solid #ccc;
        background: #FF1781;
        font-family: 'Oxygen Mono', Tahoma, Arial, sans-serif;
    }

    .tab_e button {
        background: #FF1781;
        color: #FFFFFF;
        font-size: 17px;
        float: left;
        border: none;
        outline: none;
        padding: 14px 16px;
        transition: 0.3s;
    }

    .tab_e button.active {
        background-color: #172e5c;
    }

    .tabcontent_e {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
    }
</style>
</body>
</html>