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
    <title>Administrador</title>
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
        
        <link rel="stylesheet" href="../css/flexselect.css" type="text/css" media="screen" />
        
        <!-- end: Css -->
                <!-- start: sweetalert2 -->
        <script src="../scriprts/sweetalert2.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../style/sweetalert2.min.css">
        <link rel="shortcut icon" href="img/logodifblanco.png">
        <script src="responsive.js"></script>

    <!-- start: main script -->

    <!-- custom -->
    <link href="../css/estilos.css" rel="stylesheet">
    <script src="../scriprts/vehiculos.js"></script>
    <!-- end: Javascript -->
    <link rel="icon" href="../img/favicon.ico" type="image/ico" />
    <!-- Apple Touch Icons -->    
    <link rel="apple-touch-icon" href="../img/favicon.ico">
    <link rel="apple-touch-icon" sizes="72x72" href="../img/favicon.ico" />
    <link rel="apple-touch-icon" sizes="114x114" href="../img/favicon.ico" />
    <link rel="apple-touch-icon" sizes="144x144" href="../img/favicon.ico" /> 
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
                <li class='active'><a href='autos.php'>ADMINISTRADOR</a></li>
            </ul>
        </div>
    </div>

    <!-- start: Header -->
    <nav class="navbar navbar-default header navbar-fixed-top" id="barra_superior" style="margin-top: 139px; background: #172e5c !important;">
        <div class="col-md-12 nav-wrapper">
            <div id="cerrarmenu" class="navbar-header" style="width:100%;">
                <div class="opener-left-menu is-closed" style="background: #172e5c !important;">
                    <span class="top"></span>
                    <span class="middle"></span>
                    <span class="bottom"></span>
                </div>
            </div>
        </div>
    </nav>
    <!-- end: Header -->
    <div id="prin_admin" class="container-fluid mimin-wrapper" >
        <!-- start:Left Menu -->
        <?php include 'menu.php'; ?>
        <!-- end: Left Menu -->
        <!-- start: Content -->
        <div id="content">
            <div class="panel box-shadow-none content-header">
                <div class="panel-body">
                    <div class="col-md-12" >
                        <h3 class="animated fadeInLeft">Ver autos</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-12 top-20 padding-0">
                <div class="col-md-12">
                    <a href="nuevo_vehiculo.php"><button type="button" class="btn-guardar">Nuevo auto</button></a>
                    <!--<a href="export/ExcelReportePagos.php"><button type="button" class="btnazul">Descargar Excel</button></a>-->
                </div>

            </div>
            <div class="col-md-12 top-20 padding-0">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3>autos Registrados</h3>
                        </div>
                        <div class="panel-body">
                            <div class="responsive-table">
                                <table id="datatables-example" class="table table-striped table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Numero de unidad</th>
                                            <th>Marca </th>
                                            <th>Modelo </th>
                                            <th>Tipo </th>
                                            <th>Placas </th>
                                            <th>Serie </th>
                                            <th>Combustible </th>
                                            <th>Kilometraje </th>
                                            <th>Transmisión </th>
                                            <!--<th>Auto dirección </th>
                                            <th>Direccion </th>
                                            <th>Estado </th>
                                            <th>Logo </th>-->
                                            <th>Ubicación </th>
                                            <th>Resguardante </th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="editor"></div>
            <!-- Modal edit-->
            <div id="modal_editar" class="modal fade" role="dialog">
                <div class="modal-dialog candidato">
                    <div class="modal-content">
                        <div class="modal-header" style="border-bottom: 1px solid #fff">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Editar vehiculo</h4>
                        </div>
                        <div class="col-md-12">
                            <form id="form_editar" class="">
                                <input type="hidden" value="editar_auto" id="funcion" name="funcion">
                                <input type="hidden" id="id" name="id" value="0">
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <div class="form-group form-animate-text" style="margin-top:40px !important;">
                                            <input id="num_unidad" name="num_unidad" type="text" class="form-text" oninput="this.value = this.value.replace(/\D/g, '');" required>
                                            <span class="bar"></span>
                                            <label>Numero de unidad</label>
                                        </div>

                                        <div class="form-group form-animate-text" style="margin-top:40px !important;">
                                            <input id="marca" name="marca" type="text" class="form-text" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                            <span class="bar"></span>
                                            <label>Marca</label>
                                        </div>
                                        <div class="form-group form-animate-text" style="margin-top:40px !important;">
                                            <input id="modelo" name="modelo" type="text" class="form-text" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                            <span class="bar"></span>
                                            <label>Modelo</label>
                                        </div>

                                        <div class="form-group form-animate-text" style="margin-top:40px !important;">
                                            <input id="placas" name="placas" type="text" class="form-text" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                            <span class="bar"></span>
                                            <label>Placas</label>
                                        </div>
                                        <div class="form-group form-animate-text" style="margin-top:40px !important;">
                                            <input id="tipo_combustible" name="tipo_combustible" type="text" class="form-text" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                            <span class="bar"></span>
                                            <label>Tipo de combustible</label>
                                        </div>
                                        <div class="form-group form-animate-text" style="margin-top:40px !important;">
                                            <input id="kilometraje" name="kilometraje" type="text" class="form-text" oninput="this.value = this.value.replace(/\D/g, '');" required>
                                            <span class="bar"></span>
                                            <label>kilometraje</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <input class="submit btn btn-danger" type="submit" value="Guardar">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form id="form_eliminar" class="">
            <input type="hidden" value="eliminar_sello" id="funcion" name="funcion">
            <input type="hidden" id="id" name="id" value="0">
        </form>
        <!-- end Modal -->
    </div>
    <!-- end: content -->
    </div><th>Modelo</th>

    <!-- start: Javascript -->
    <script src="../asset/js/jquery.min.js"></script>
    <script src="../asset/js/jquery.ui.min.js"></script>
    <script src="../asset/js/bootstrap.min.js"></script>

    <!-- plugins -->

    <script src="../asset/js/plugins/moment.min.js"></script>
    <script src="../asset/js/plugins/jquery.datatables.min.js"></script>
    <script src="../asset/js/plugins/datatables.bootstrap.min.js"></script>

    <script src="../asset/js/plugins/jquery.nicescroll.js"></script>

    <script src="../asset/js/main.js"></script>   

</body>

</html>
