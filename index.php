<?php
    include 'config/conexion.php';
    date_default_timezone_set('America/Mexico_City');
    $fecha = date('d-m-Y');
    $fechainicio = DateTime::createFromFormat('d-m-Y', $fecha);
    $fechainicio->sub(new DateInterval('P6D'));
    $fechafin = date('d-m-Y');
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="keyword" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="img/favicon.ico" type="image/ico" />
        <title>Bitacora</title>

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
        <!--<link rel="stylesheet" href="css/responsive.css" />-->
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
                <li class='active'><a href='index.php'>Nueva Bitacora</a></li>
                <li><a href='Busqueda.php'>Ver Bitacoras</a></li>
                <!--<li><a href='busquedanoticia.php'>Ver Noticia</a></li>
                <li><a href='nueva_noticia.php'>NUEVA NOTICIA</a></li>-->
                <li><a href='admin/autos.php'>ADMINISTRADOR</a></li>
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
                            <input type="hidden" value="guardar_solicitud" id="funcion" name="funcion">
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
                                    <input type="text" class="form-text" id="FechaDel" name="FechaDel" value="<?php echo $fechainicio->format('d-m-Y');?>">
                                </div>
                                <!--PERIODO AL-->
                                <div class="col-md-6" style="width: 25%;">
                                    <label>Al: </label>
                                    <input type="text" class="form-text" id="FechaAl" name="FechaAl" value="<?php echo $fechafin;?>">
                                </div>
                            </div><!--END CENTRAL 0-->
                            
                            <div class="col-md-12" style="margin-top:40px !important;"><!--CENTRAL 1-->
                                <!--UNIDAD DE RESGUARDO-->
                                <div class="col-md-6" style="width: 33%;">
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
                                <div class="col-md-6" style="width: 33%;">
                                    <div class="form-group form-animate-text" id="M_m" style="opacity: 0;">
                                        <input type="text" class="form-text" id="marca_modelo" name="marca_modelo">
                                        <span class="bar"></span><label>Marca/Modelo</label>
                                    </div>
                                </div>
                                <!--PLACAS-->
                                <div class="col-md-6" style="width: 33%;">
                                    <div class="form-group form-animate-text" id="p" style="opacity: 0;">
                                        <input type="text" class="form-text" id="placas" name="placas">
                                        <span class="bar"></span><label>Placas</label>
                                    </div>
                                </div>
                            </div><!--END CENTRAL 1-->
                                
                            <div class="col-md-12" style="margin-top:40px !important;"> <!--CENTRAL 2-->
                                <!--FECHA CARGA-->
                                <div class="col-md-6" style="width: 33%;">
                                    <div class="form-group form-animate-text" id="F_c">
                                        <input type="text" class="form-text" id="fecha_r" name="fecha_carga">
                                        <span class="bar"></span>
                                        <label>Fecha de carga</label>
                                    </div>
                                </div>

                                <!--FOLIO-->
                                <div class="col-md-6" style="width: 33%;">
                                    <div class="form-group form-animate-text" id="fol">
                                        <input type="text" class="form-text" id="folio" name="folio">
                                        <span class="bar"></span><label>FOLIO</label>
                                    </div>
                                </div>

                                <!--MONTO-->
                                <div class="col-md-6" style="width: 33%;">
                                    <div class="form-group form-animate-text" id="mont">
                                        <input type="text" class="form-text" inputmode='numeric' pattern='\d*' id="monto" name="monto">
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

        <script>
            $(function() {
                var f = new Date();
                fech=f.getDate()+"-"+(f.getMonth() +1)+"-"+f.getFullYear();

                //Paginacion(1);
                $("#FechaDel").datepicker({
                    maxDate: fech,
                    monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septimbre", "Octubre", "Noviembre", "Diciembre" ],
                    monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
                    dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
                    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
                    dateFormat: 'dd-mm-yy',
                    yearRange: '-10:+0',
                    onSelect: obtenerResultados
                });
                $("#FechaAl").datepicker({
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
            var fechaDel = document.getElementById('FechaDel').value;
            var fechaAl = document.getElementById('FechaAl').value;
            // Realiza una solicitud AJAX al servidor
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'procesar.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Muestra los resultados en el contenedor de resultados
                document.getElementById('recorridos').innerHTML = xhr.responseText;
                if(document.getElementsByClassName("tablinks").length>1){
                    openTab(document.getElementsByClassName("tablinks")[0].value);
                    document.getElementsByClassName("tablinks")[0].className += " active";
                }else if(document.getElementsByClassName("tablinks").length==1){
                    document.getElementById("boton_guardar0").style.display = "block";
                    document.getElementById("btn_sig0").style.display = "none";
                    openTab(document.getElementsByClassName("tablinks")[0].value);
                    document.getElementsByClassName("tablinks")[0].className += " active"
                    document.getElementById("destino0").required = false;
                }else{
                    $('#sig_bitacora').css("display", "block");
                    swal({
                        type: 'error',
                        title: 'Rango de fecha no válido',
                        timer: 1000,
                        showConfirmButton: false
                    });
                    setTimeout(function() {
                        document.location.href = 'index.php';
                    }, 1100);
                }
            }
            };
             
            xhr.send('FechaDel=' + encodeURIComponent(fechaDel) + '&FechaAl=' + encodeURIComponent(fechaAl));
        }
        // Obtiene los resultados al cargar la página
        obtenerResultados();
        </script>

<style>
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background: #FF1781;
        font-family: 'Oxygen Mono', Tahoma, Arial, sans-serif;
    }

    .tab button {
        background: #FF1781;
        color: #FFFFFF;
        font-size: 17px;
        float: left;
        border: none;
        outline: none;
        padding: 14px 16px;
        transition: 0.3s;
    }

    .tab button.active {
        background-color: #172e5c;
    }

    .tabcontent {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
    }
</style>
    </body>
</html>
