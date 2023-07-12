var form = document.getElementById('login');
var buttonE1 = document.getElementById('NuevoUsuario');

buttonE1.addEventListener('click', function () {
  if(document.getElementById("conf_pass").value == document.getElementById("pass").value){
    $.ajax({
      type: "POST",
      url: "controller/controller.php",
      data: {funcion:'nuevo_usuario', usuario: document.getElementById("user").value, pass: document.getElementById("pass").value},
      cache: false,
      success: function(result) {
        if (result == 1) {
          swal({
            type: 'success',
            title: 'Usuario creado exitosamente',
            timer: 1500,
            showConfirmButton: false
          });
            $(".login-form")[0].reset();
        }
      }
    });
  }else{
    swal({
      type: 'error',
      title: 'Contraseñas diferentes',
      timer: 1000,
      showConfirmButton: false
    });
  }
});

document.getElementById("conf_pass").addEventListener('input', function () {

  if(document.getElementById("conf_pass").value != document.getElementById("pass").value)
    document.getElementById("conf_pass").style.border = "1px solid red";
  else
    document.getElementById("conf_pass").style.border = "";
});