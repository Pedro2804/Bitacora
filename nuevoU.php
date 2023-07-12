<!DOCTYPE html>
<html lang="en" >

  <head>
    <meta charset="UTF-8">
    <title>Nuevo usuario</title>
    <!-- librerias para jquery-->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
    <!-- mensajes de swal-->
    <link rel="stylesheet" type="text/css" href="style/sweetalert2.min.css">
  <script src="scriprts/sweetalert2.min.js"></script>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"  rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Roboto:400,500' rel='stylesheet' type='text/css'>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
        <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <div class="login-container">
    <section class="login" id="login">
      <header>
          <div class="imgdif" ></div>
        <h2>Nuevo usuario</h2>
      </header>
      <form id="login-form" class="login-form">
        <input id="user" type="text" class="login-input" placeholder="User" required autofocus/>
        <input id="pass" type="password" class="login-input" placeholder="Password" required/>
        <input id="conf_pass" type="password" class="login-input" placeholder="Confirmar password" required/>
        <div class="submit-container">
        <input type="submit" class="login-button" value="Crear"/>
        <!--<button type="submit" class="login-button">Crear</button>-->
          <!--<div id="NuevoUsuario" class="btn-guardar" style="color: #FFFFFF; user-select: none; background: #19528A; width: 75px; height: 35px; text-align: center; padding-top: 8px; cursor: pointer;">Crear</div>-->
        </div>
      </form>
    </section>
  </div>

  <script  src="js/index.js"></script>
  </body>
</html>
