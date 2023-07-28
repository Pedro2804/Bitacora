$(document).ready(function() 
 {
    $( '#login-form' ).submit(function( event ) {
      event.preventDefault();
      if($('#conf_pass').val() == $('#pass').val()){
        $.ajax({
          type: "POST",
          url: "controller/controller.php",
          data: {funcion:'nuevo_usuario', usuario: $('#user').val(), pass: $('#pass').val()},
          cache: false,
          success: function(result) {
            //console.log(result);
            if (result == 1) {
              swal({
                type: 'success',
                title: 'Usuario creado exitosamente',
                timer: 1500,
                showConfirmButton: false
              });
                $(".login-form")[0].reset();
            }else
              swal({
                type: 'error',
                title: 'El usuario ya existe en el sistema',
                timer: 1000,
                showConfirmButton: false
              });
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

  $('#conf_pass').on('input', function (event) {
    event.preventDefault();
      if($('#conf_pass').val() != $('#pass').val())
        $('#conf_pass').css("border", "1px solid red");
      else
      $('#conf_pass').css("border", "");
    });

 });