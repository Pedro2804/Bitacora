<?php
session_start();
if(isset($_SESSION['usuario'])){
    header('Location: admin/autos.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta name="description" content="Miminium Admin Template v.1">
  <meta name="author" content="Isna Nur Azis">
  <meta name="keyword" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Vehiculos</title>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap-theme.min.css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.js"></script>

    <!-- start: sweetalert2 -->
    <script src="scriprts/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="css/estilos.css" />
    <link rel="stylesheet" type="text/css" href="style/sweetalert2.min.css">
    <!-- start: main script -->

  <!-- start: validacion -->
    <script src="scriprts/login.js"></script>

  <!-- plugins -->
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/font-awesome.min.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/simple-line-icons.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/animate.min.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/icheck/skins/flat/aero.css"/>
  <link href="asset/css/style.css" rel="stylesheet">
  <!-- end: Css -->

 	    <!-- Favicon -->
	        
                        
    <link rel="shortcut&#x20;icon" href="img/favicon.ico" type="image/ico" />
    <link rel="icon" href="img/favicon.ico" type="image/ico" />
    <!-- Apple Touch Icons -->    
    <link rel="apple-touch-icon" href="img/favicon.ico">
    <link rel="apple-touch-icon" sizes="72x72" href="img/favicon.ico" />
    <link rel="apple-touch-icon" sizes="114x114" href="img/favicon.ico" />
    <link rel="apple-touch-icon" sizes="144x144" href="img/favicon.ico" />  
     <!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]--> 
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
    <link href="css/estilos.css" rel="stylesheet">
    </head>

    <body id="mimin" class="dashboard form-signin-wrapper">
      <div class="container">
        <form id="form_login" class="form-signin" data-toggle="validator" role="form">
          <div class="panel periodic-login">
              <div class="panel-body text-center">
                  <p><img src="img/imagen3.png" width="265px"/></p>
                  <p class="element-name">Inicio de sesión</p>

                  <i class="icons icon-arrow-down"></i>

                  <div class="form-group form-animate-text" style="margin-top:40px !important;">
                    <input id="txt_mail" type="text" class="form-text" data-error="Usuario no valido" required>
                    <span class="bar"></span>
                    <label>Usuario</label>
                       <div class="help-block with-errors"></div>
                  </div>
                  <div class="form-group form-animate-text" style="margin-top:40px !important;">
                    <input id="txt_pass" type="password" class="form-text" data-error="Password no valido" data-minlength="3" required>
                    <span class="bar"></span>
                    <label>Password</label>
                    <div class="help-block with-errors"></div>
                  </div>
                  <input type="submit" class="btn-guardar" value="Entrar"/>
              </div>
          </div>
        </form>

      </div>

      <!-- end: Content -->
      <!-- start: Javascript -->
      <script src="asset/js/jquery.min.js"></script>
      <script src="asset/js/jquery.ui.min.js"></script>
      <script src="asset/js/bootstrap.min.js"></script>

      <script src="asset/js/plugins/moment.min.js"></script>
      <script src="asset/js/plugins/icheck.min.js"></script>

      <!-- custom -->
      <script src="asset/js/main.js"></script>
      <script type="text/javascript">
       $(document).ready(function(){
         $('input').iCheck({
          checkboxClass: 'icheckbox_flat-aero',
          radioClass: 'iradio_flat-aero'
        });
       });
     </script>
     <!-- end: Javascript -->
   </body>
   </html>
