<?php
    include 'config/conexion.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  
  <meta charset="utf-8">
  <meta name="keyword" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Noticia</title>

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
    
    <link rel="stylesheet" type="text/css" href="asset/css/plugins/datatables.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="asset/css/plugins/animate.min.css"/>
  <!-- end: Css -->
        <!-- start: sweetalert2 -->
    <script src="scriprts/sweetalert2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style/sweetalert2.min.css">
    <link rel="stylesheet" href="css/estilos.css" />


  <link rel="shortcut icon" href="img/logodifblanco.png">
</head>
<script>
    
</script>
<form id="form_reporte">
<input type="hidden" id="funcion" name="funcion" value="guardar_reporte">
<input type="hidden" id="claves_solicitud_sa" name="claves_solicitud_sa" value="0">
<input type="hidden" id="claves_solicitud" name="claves_solicitud" value="0">
<input type="hidden" id="folio_generado" name="folio_generado" value="0">
<input type="hidden" id="fol_ex" name="fol_ex" value="0">
<input type="hidden" id="inputs_folio" name="inputs_folio" value="">
<input type="hidden" id="folios_g" name="folios_g" value="">
<input type="hidden" id="clave_editar" name="clave_editar" value="0">
<input type="hidden" class="form-text" id="e_descripcion_s" name="e_descripcion_s">
<body id="mimin" class="dashboard">
    <input type="hidden" id="clave_global" name="clave_global" value="">
    <input type="hidden" id="dato_desc" name="dato_desc" value="">
     <div class="jumbotron">
        <img src="img/logodifblanco.png" class="logo_dif">
    </div>
    
    <div id='cssmenu'>  
      <ul>
        <li><a href='index.php'>Nueva Solicitud</a></li>
        <li><a href='busqueda.php'>Ver Solicitudes</a></li>
        <li><a href='busquedanoticia.php'>Ver Noticia</a></li>
        <li class='active'><a href='nueva_noticia.php'>NUEVA NOTICIA</a></li>
      </ul>
    </div>  
                <div class="col-md-10" style="width: 100%;">
                  <div class="col-md-12 panel">
                    <div class="col-md-12 panel-heading">
                      <h4 class="Titulos"><center>NUEVA NOTICIA</center></h4>
                    </div>
                    <div class="col-md-12 panel-body" style="padding-bottom:30px;">
                      <div class="col-md-12">
                          <div class="col-md-6">
                            <div class="form-group form-animate-text" style="margin-top:40px !important;">
                              <input type="text" class="form-text" id="fecha_inicio" name="fecha_inicio" required>
                              <span class="bar"></span>
                              <label>Del: </label>
                            </div>
                             <div class="form-group form-animate-text" style="margin-top:40px !important;" >
                              <input type="text" class="form-text" id="doc_respalda" name="doc_respalda" value="Solicitud de Mantenimiento y/o Memorandúm" style="text-transform:inherit;" required>
                              <span class="bar"></span>
                              <label>Documento Respalda</label>
                            </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group form-animate-text" style="margin-top:40px !important;">
                              <input type="text" class="form-text" id="fecha_final" name="fecha_final" required>
                              <span class="bar"></span>
                              <label>Al:</label>
                            </div>
                              <div class="form-group form-animate-text" style="margin-top:40px !important;" >
                              <input type="text" class="form-text" id="observaciones" name="observaciones">
                              <span class="bar"></span>
                              <label>Observaciones</label>
                            </div>
                          </div>
                          <div class="col-md-12">
                              <div class="col-md-12 top-20 padding-0">
                                <div class="col-md-12">
                                <div class="panel">
                                
                                <div class="panel-body">
                                <div class="responsive-table">
                                <table id="datatable_nueva_noticia" class="table table-striped table-bordered" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                <th>Folio</th>
                                <th>Descripción del Problema</th>
                                <th>Descripción del Servicio</th>
                                <th>Área</th>
                                <th>Fecha Recibido</th>
                                <th>Fecha Atendido</th>
                                <th>Estatus</th>
                                </tr>
                                </thead>
                                </table>
                                </div>
                                </div>
                                </div>
                                </div>  
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
    <div class="col-md-10" style="width: 100%;">
                  <div class="col-md-12 panel">
                    <div class="col-md-12 panel-heading">
                      <h4><center>Solicitudes sin Atender</center></h4>
                    </div>
                    <div class="col-md-12 panel-body" style="padding-bottom:30px;">
                      <div class="col-md-12">
                          <div class="col-md-12">
                              <div class="col-md-12 top-20 padding-0">
                                <div class="col-md-12">
                                <div class="panel">
                                
                                <div class="panel-body">
                                <div class="responsive-table">
                                <table id="datatable_solicitudes_sin_atender" class="table table-striped table-bordered" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                <th>Folio</th>
                                <th>Descripción del Problema</th>
                                <th>Descripción del Servicio</th>
                                <th>Área</th>
                                <th>Fecha Recibido</th>
                                <th>Fecha Atendido</th>
                                <th>Estatus</th>
                                <th>Agregar</th>
                                </tr>
                                </thead>
                                </table>
                                </div>
                                </div>
                                </div>
                                </div>  
                            </div>
                        </div>
                        <div class="col-md-12">
                             <input onclick="procesar_reporte()" class="btn-guardar" type="button" value="Guardar">
                        </div>
                    </div>
                  </div>
                </div>
              </div>
</form>
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
<script src="asset/js/plugins/jquery.datatables.min.js"></script>
<script src="asset/js/plugins/datatables.bootstrap.min.js"></script>

<!-- custom -->
<script src="scriprts/nueva_noticia.js"></script>
</body>
</html>