<?php
include 'config/conexion.php';

date_default_timezone_set('America/Mexico_City');
$fechainicio = date('d-m-Y',strtotime ('-6 day', strtotime(date('d-m-Y')))); 
$fechafin = date('d-m-Y');

?>
<!DOCTYPE html>
<html lang="en">
<head>
  
  <meta charset="utf-8">
  <meta name="keyword" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ver Bitacoras</title>
  <!-- start: Css -->
  <link rel="stylesheet" type="text/css" href="asset/css/bootstrap.min.css">

  <!-- plugins -->
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/font-awesome.min.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/animate.min.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/nouislider.min.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/select2.min.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/ionrangeslider/ion.rangeSlider.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/ionrangeslider/ion.rangeSlider.skinFlat.css"/>
<!--  <link rel="stylesheet" type="text/css" href="asset/css/plugins/bootstrap-material-datetimepicker.css"/>-->
  <link href="asset/css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="css/flexselect.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="css/estilos.css" />
<!-- end: Css --> 

<!-- start: sweetalert2 -->
  <script>
  function Paginacion(pag) {
	$('#txtPagina').val(pag);
	var url="Paginacion.php";
	$.ajax({
		type: "POST",
		url: url,
		data: $("#FrmBusquedaSolicitudes").serialize(), //
		success: function(data) {
			$('#paginacion').html(data);
		}
		});
	}
  function FormatoSolicitud(){
          //window.open("Bitacora.php?Solicitud="+$('#TxtConsec').val());
          window.open("Bitacora.php");
	}
  </script>
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
    <!--<script src="scriprts/vehiculos.js"></script>-->
</head>
	<body id="mimin" class="dashboard">
	<div class="jumbotron">
        <img src="img/logodifblanco.png" class="logo_dif">
    </div>
    <div id='cssmenu'>  
      <ul>
        <li><a href='index.php'>Nueva Bitacora</a></li>
        <li class='active'><a href='Busqueda.php'>Ver Bitacoras</a></li>
        <li><a href='admin/autos.php'>ADMINISTRADOR</a></li>
      </ul>
    </div> 
    <div class="col-md-10" style="width: 100%;">
        <div class="col-md-12 panel">
           <div class="col-md-12 panel-heading">
              <h4 class="Titulos">RESUMEN DE BITACORAS</h4>
           </div>
           <div class="col-md-12 panel-body" style="padding-bottom:30px;">
              <div class="col-md-12">
              <form id="FrmBusquedaSolicitudes" name="FrmBusquedaSolicitudes" method="post" action="">
                    <div style="clear:both"></div>
              		<div class="izquierdo" style="margin-top:10px !important;">
                    	<label>Del: </label>
                        <input type="text" class="form-text" id="FechaDel" name="FechaDel" value="<?php echo $fechainicio;?>">
                     </div>
                    <div class="izquierdo" style="margin-top:10px !important;">
                    	<label>Al: </label>
                        <input type="text" class="form-text" id="FechaAl" name="FechaAl" value="<?php echo $fechafin;?>">
                    </div>
                    <div class="izquierdo">
                     	<input type="hidden" id="txtPagina" name="txtPagina" value="1">
					   	<input type="hidden" name="Registro" id="Registro" value="0" />
                     	<input class="btn-guardar" type="submit" id="BtnBuscar" value="Buscar">
                     </div>
                    <div style="clear:both"></div>
                     <div class="izquierdo" style="margin-top:10px !important; margin-left:37px;">
                        <label>Mostrar</label>
                        <select class="form-text" id="CmbMostrar" name="CmbMostrar">
                           <option value="5">5</option>
                           <option value="10">10</option>
                           <option value="15">15</option>
                           <option value="20">20</option>
                         </select> <label>registros</label>
                     </div>
              </form>
              	<div style="clear:both"></div>
				<div id="paginacion"></div>
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
<!--<script src="asset/js/plugins/bootstrap-material-datetimepicker.js"></script>-->
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

Paginacion(1);
$("#FechaDel").datepicker({
		maxDate: fech,
		monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septimbre", "Octubre", "Noviembre", "Diciembre" ],
		monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
		dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
     	dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        dateFormat: 'dd-mm-yy',
		yearRange: '-10:+0'
	});
$("#FechaAl").datepicker({
		maxDate: fech,
		monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septimbre", "Octubre", "Noviembre", "Diciembre" ],
		monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
		dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
     	dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        dateFormat: 'dd-mm-yy',
		yearRange: '-10:+0'
	});
$("#BtnBuscar").click(function() {
		Paginacion(1);
		return false;
	});
});
$("#CmbFolio").change(function(){
		Paginacion(1);
});
$("#CmbEstatus").change(function(){
		Paginacion(1);
});
$("#CmbDireccion").change(function(){
		Paginacion(1);
});
$("#CmbMostrar").change(function(){
		Paginacion(1);
});
</script>
	</body>
</html>

