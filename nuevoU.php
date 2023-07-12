<!DOCTYPE html>
<html lang="en" >

  <head>
    <meta charset="UTF-8">
    <title>Nuevo usuario</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
        rel="stylesheet">
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
      <form class="login-form">
        <input id="user" type="text" class="login-input" placeholder="User" required autofocus/>
        <input id="pass" type="password" class="login-input" placeholder="Password" required/>
        <input id="conf_pass" type="password" class="login-input" placeholder="Confirmar password" required/>
        <div class="submit-container">
          <button id="NuevoUsuario" type="submit" class="login-button">Crear</button>
          <!--<div id="NuevoUsuario" class="btn-guardar" style="color: #FFFFFF; user-select: none; background: #19528A; width: 75px; height: 35px; text-align: center; padding-top: 8px; cursor: pointer;">Crear</div>-->
        </div>
      </form>
    </section>
  </div>
  <script  src="js/index.js"></script>
  </body>
</html>
