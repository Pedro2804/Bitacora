$(document).ready(function() 
 {
    $( "#form_login" ).submit(function( event ) 
    {
        event.preventDefault();
    	var usuario=$('#txt_mail').val();
        var pass=$('#txt_pass').val();
		$.ajax(
		{
			type: "POST",
			url: "controller/controller.php",
			data: {usuario: usuario,pass: pass, funcion: 'login'},
			cache: false,
			success: function(result)
			{
				//console.log(result);
				if(result==1){
					document.location.href="admin/autos.php";
				}else if(result==0){
					alert("Usuario no registrado!");
				}else if(result==2){
                     swal({
                        title:'Verifica los datos',
                        text: "Contraseña o Usuario Incorrectos!",
                        type: 'info',
                        allowOutsideClick: false,
                        confirmButtonText: 'Aceptar',
                        }).then(function()
                            {
                });
				}else alert("error");
			}
		});
	});
});

