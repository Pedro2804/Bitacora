<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nuevo Sello</title>
    <!-- start: validacion -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.js"></script>
        <!-- end: validacion -->

        <!-- start: Css -->
        <link rel="stylesheet" type="text/css" href="../asset/css/bootstrap.min.css">

        <!-- plugins -->
        <link rel="stylesheet" type="text/css" href="../asset/css/plugins/font-awesome.min.css"/>
        <link rel="stylesheet" type="text/css" href="../asset/css/plugins/datatables.bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../asset/css/plugins/animate.min.css"/>
        <link rel="stylesheet" type="text/css" href="../asset/css/plugins/simple-line-icons.css" />
        <link rel="stylesheet" type="text/css" href="../asset/css/plugins/nouislider.min.css"/>
        <link rel="stylesheet" type="text/css" href="../asset/css/plugins/select2.min.css"/>
        <link rel="stylesheet" type="text/css" href="../asset/css/plugins/ionrangeslider/ion.rangeSlider.css"/>
        <link rel="stylesheet" type="text/css" href="../asset/css/plugins/ionrangeslider/ion.rangeSlider.skinFlat.css"/>
        <link rel="stylesheet" type="text/css" href="../asset/css/plugins/bootstrap-material-datetimepicker.css"/>
        <link href="../asset/css/style.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.debug.js"></script>
        <script src="https://unpkg.com/jspdf@latest/dist/jspdf.min.js"></script>
        <script src="../scriprts/html2canvas.js"></script>
        <link rel="stylesheet" href="../css/flexselect.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="../css/estilos.css" />
        <link rel="stylesheet" href="../css/responsive.css" />
        <!-- end: Css -->
                <!-- start: sweetalert2 -->
        <script src="../scriprts/sweetalert2.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../style/sweetalert2.min.css">
        <link rel="shortcut icon" href="img/logodifblanco.png">
        <script src="responsive.js"></script>
        <script src="../asset/js/main.js"></script>
        <!-- Apple Touch Icons -->
        <link rel="apple-touch-icon" href="../img/favicon.png">
        <link rel="apple-touch-icon" sizes="72x72" href="../img/favicon.ico" />
        <link rel="apple-touch-icon" sizes="114x114" href="../img/favicon.ico" />
        <link rel="apple-touch-icon" sizes="144x144" href="../img/favicon.ico" />

    <!-- start: Javascript -->
    <script src="../asset/js/jquery.min.js"></script>
    <script src="../asset/js/jquery.ui.min.js"></script>
    <script src="../asset/js/bootstrap.min.js"></script>
    <script src="../scriprts/jquery.knob.js"></script>

    <!-- plugins -->

    <script src="../asset/js/plugins/moment.min.js"></script>
    <script src="../asset/js/plugins/jquery.datatables.min.js"></script>
    <script src="../asset/js/plugins/datatables.bootstrap.min.js"></script>

    <script src="../asset/js/plugins/jquery.nicescroll.js"></script>

    <!-- start: main script -->

    <!-- custom -->
    <link href="../css/estilos.css" rel="stylesheet">
    <script src="../scriprts/vehiculos.js"></script>
    <script src="../scriprts/formRecorrido.js"></script>
    <!-- end: Javascript -->
</head>

<body id="mimin" class="dashboard">
    <div class="navbar navbar-default header navbar-fixed-top" >
        <div class="jumbotron" id="logodif">
                <img src="../img/logodifblanco.png" class="logo_dif">
        </div>
        <div id='cssmenu'>  
            <ul>
                <li><a href='../index.php'>Nueva Bitacora</a></li>
                <li><a href='../Busqueda.php'>Ver Bitacoras</a></li>
                <!--<li><a href='../busquedanoticia.php'>Ver Noticia</a></li>
                <li><a href='../nueva_noticia.php'>NUEVA NOTICIA</a></li>-->
                <li class='active'><a href='autos.php'>ADMINISTRADOR</a></li>
            </ul>
        </div>
    </div>

    <!-- start: Header -->
    <nav class="navbar navbar-default header navbar-fixed-top" id="barra_superior" style="margin-top: 139px; background: #172e5c !important;">
        <div class="col-md-12 nav-wrapper">
            <div id="cerrarmenu" class="navbar-header" style="width:100%;">
                <div class="opener-left-menu is-open" style="background: #172e5c !important;">
                    <span class="top"></span>
                    <span class="middle"></span>
                    <span class="bottom"></span>
                </div>
            </div>
        </div>
    </nav>
    <!-- end: Header -->
    <div id="prin_admin" class="container-fluid mimin-wrapper">
        <!-- start:Left Menu -->
        <?php include 'menu.php'; ?>
        <!-- end: Left Menu -->
        <!-- start: Content -->
        <div id="content">
            <div class="panel box-shadow-none content-header">
                <div class="panel-body">
                    <div class="col-md-12">
                        <h3 class="animated fadeInLeft">Alta de vehiculo</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-12 panel">
                    <div class="col-md-12 panel-heading">
                        <h4>Nuevo Vehiculo</h4>
                    </div>
                    <div class="col-md-12 panel-body">
                        <form id="form_nuevo_auto" class="">
                            <input type="hidden" id="funcion" name="funcion" value="nuevo_auto">
                            <div class="col-md-6">
                                <div class="form-group form-animate-text" style="margin-top:40px !important;">
                                    <input id="num_unidad" name="num_unidad" type="text" class="form-text" oninput="this.value = this.value.replace(/\D/g, '');" required>
                                    <span class="bar"></span>
                                    <label>Numero de unidad</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-animate-text" style="margin-top:40px !important;">
                                    <input id="marca" name="marca" type="text" class="form-text" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                    <span class="bar"></span>
                                    <label>Marca</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group form-animate-text" style="margin-top:40px !important;">
                                    <input id="modelo" name="modelo" type="text" class="form-text" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                    <span class="bar"></span>
                                    <label>Modelo</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-animate-text" style="margin-top:40px !important;">
                                    <input id="placas"name="placas" type="text" class="form-text" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                    <span class="bar"></span>
                                    <label>Placas</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group form-animate-text" style="margin-top:40px !important;">
                                    <input id="tipo_combustible"name="tipo_combustible" type="text" class="form-text" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                    <span class="bar"></span>
                                    <label>Tipo de combustible</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-animate-text" style="margin-top:40px !important;">
                                    <input id="kilometrake"name="kilometraje" type="text" class="form-text" oninput="this.value = this.value.replace(/\D/g, '');" required>
                                    <span class="bar"></span>
                                    <label>kilometraje</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <input class="btn-guardar" type="submit" value="Guardar">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end: content -->
    </div>
    <script src="../asset/js/main.js"></script>
</body>
</html>
