 $(document).ready(function()
{
     $.datepicker.regional['es'] = {
     closeText: 'Cerrar',
     prevText: '< Ant',
     nextText: 'Sig >',
     currentText: 'Hoy',
     monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
     monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
     dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
     dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
     dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
     weekHeader: 'Sm',
     dateFormat: 'yy-mm-dd',
     firstDay: 1,
     isRTL: false,
     showMonthAfterYear: false,
     yearSuffix: ''
     };
    $.datepicker.setDefaults($.datepicker.regional['es']);
    
    $(document).off('.datepicker.data-api');
    $('#fecha_r').datepicker();
    $('#fecha_d').datepicker(); 
    
     $.ajax
    ({
        type: "POST",
        url: "controller/controller.php",
        data:
        {
            funcion:'direcciones'
        },
        cache: false,
        success: function(result)
        {
            var obj = JSON.parse(result);
            var i=0;
        }
    });

    $( "#departamento" ).change(function()
    {

    });

    $("#idVehiculo").on("change", function(event){ //jQuery
        event.preventDefault();
        var idVehiculo = $(this).val();

        $.ajax({
                method: "POST",
                url: "controller/controller.php",
                data: {id: idVehiculo, funcion: "vehiculo"},
                cache: false,
                success: function(result){
                    if(result!=0){
                        var resultados = JSON.parse(result);
                        $("#M_m").css("opacity", 1);
                        $("#marca_modelo").val(resultados.modelo);

                        $("#p").css("opacity", 1);
                        $("#placas").val(resultados.placas);
                        $("#km_I0").val(resultados.km);
                    }else{
                        $("#M_m").css("opacity", 0);
                        $("#p").css("opacity", 0);

                        if (idVehiculo != ""){
                            swal({
                            type: 'error',
                            title: 'La unidad no existe en el sistema',
                            timer: 1000,
                            showConfirmButton: false});
                            $("#recorridos").css("display", "none");
                            $("#sig_bitacora").css("display", "block");
                        }else{
                            swal({
                                type: 'warning',
                                title: 'Ingrese una unidad',
                                timer: 1000,
                                showConfirmButton: false});
                                $("#recorridos").css("display", "none");
                                $("#sig_bitacora").css("display", "block");
                        }
                    }
                }
        });
        
    });

    $("#sig_bitacora").on("click", function(event){
        event.preventDefault();
        var unidad = document.getElementById('idVehiculo');
        var empleado = document.getElementById('empleado');
        var recorridos = document.getElementById('recorridos');
        var dias_recorrido = document.getElementsByClassName("tablinks");
        unidad.reportValidity();
        empleado.reportValidity();

        if((empleado.value != '') && (unidad.value != '')){

            $.ajax({
                method: "POST",
                url: "controller/controller.php",
                data: {id: unidad.value, funcion: "vehiculo"},
                cache: false,
                success: function(result){
                    if(result!=0){
                        var resultados = JSON.parse(result);
                        document.getElementById('M_m').style.opacity = 1;
                        document.getElementById('marca_modelo').value = resultados.modelo;

                        document.getElementById('p').style.opacity = 1;
                        document.getElementById('placas').value = resultados.placas;
                            recorridos.style.display= 'block';
                            document.getElementById('sig_bitacora').style.display = "none";
                            openTab(dias_recorrido[0].value);
                            for(i=0; i<dias_recorrido.length; i++)
                                dias_recorrido[i].disabled = true;
                            dias_recorrido[0].className += " active";

                            document.getElementById('km_I0').value = resultados.km;
                    }else{
                        swal({
                            type: 'error',
                            title: 'La unidad no existe en el sistema',
                            timer: 1000,
                            showConfirmButton: false
                        });
                            document.getElementById('recorridos').style.display= 'none';
                            document.getElementById('sig_bitacora').style.display = 'block';
                            document.getElementById('M_m').style.opacity = 0;
                            document.getElementById('p').style.opacity = 0;
                    }
                }
            });
        }
    });
     
    $("#form_solicitud").submit(function(event) {
        event.preventDefault();

        $.ajax
            ({
                type: "POST",
                url: "controller/controller.php",
                data: $("#form_solicitud").serialize(),
                cache: false,
                success: function(result) {
                    console.log(result);
                    if (result == 1) {
                        swal({
                            type: 'success',
                            title: 'Solicitud Generada',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        $("#form_solicitud")[0].reset();
                    }
                }
            });
    });    

    $( "#form_solicitudEditar" ).submit(function( event )
    {
        event.preventDefault();
        var direccion=$('#direccion').val();
        var entrega=$('#entrega').val();
        var checks=0;
        var red=$('#red').prop('checked');
        if(red==1){checks++}
        var mantenimiento=$('#mantenimiento').prop('checked');
        if(mantenimiento==1){checks++}
        var telefonia=$('#telefonia').prop('checked');
        if(telefonia==1){checks++}
        var formateo=$('#formateo').prop('checked');
        if(formateo==1){checks++}
        var comunicacion=$('#comunicacion').prop('checked');
        if(comunicacion==1){checks++}
        var impresora=$('#impresora').prop('checked');
        if(impresora==1){checks++}
        var asistencia=$('#asistencia_t').prop('checked');
        if(asistencia==1){checks++}
        var otro=$('#otro').prop('checked');
        if(otro==1){checks++}
        
        if(checks==0)
        {
            swal(
                  'Campo obligatorio',
                  'Elija una opcion de servicio',
                  'info'
                )
        }
        else if(direccion==0)
        {
             $( "#target" ).focus();
            swal(
                  'Campo obligatorio',
                  'Elija una Dirección',
                  'info'
                )
        }
        else
        {
            $.ajax
            ({
                type: "POST",
                url: "controller/controller.php",
                data: $("#form_solicitudEditar").serialize(),
                cache: false,
                success: function(result)
                {
                    if(result==1)
                    {
                       swal({
                              type: 'success',
                              title: 'Solicitud editada',
                              //timer: 4000,
                           showConfirmButton: true
                            }).then(function() {
                                //$("#form_solicitud")[0].reset();
                                window.location.href = "busqueda.php";
                            });
                        //$("#form_solicitud")[0].reset();
                        
                    }
                }
            }); 
        }
    });

    $( "#fecha_r" ).change(function()
    {
        var fecha=$('#fecha_r').val();
        $('#fecha_d').val(fecha);
    });
     $( "#fecha_d" ).change(function()
    {
        var fecha_r=$('#fecha_r').val();
        var fecha_d=$('#fecha_d').val();
        
        var f_r = new Date(fecha_r);
        var f_d = new Date(fecha_d); 
        var resultado = f_r.getTime() >= f_d.getTime();
        if(resultado==false)
        {
            var fecha=$('#fecha_r').val();
            $('#fecha_d').val(fecha);
            if(fecha=="")
            {
                swal(
                  'Elija una fecha de recibido',
                  'Antes de llenar este campo debe elegir una fecha de recibido.',
                  'info'
                    );
            }
            else
            {
                swal(
                  'Fecha no valida',
                  'La fecha del documento debe ser menor a la de recibido',
                  'info'
                    );
            }
        }
    });

    $( "#direccion" ).change(function()
    {
        var direccion=$( "#direccion" ).val();
        $.ajax
            ({
                type: "POST",
                url: "controller/controller.php",
                data:
                {
                    funcion:'filtrar_deptos',
                    direccion:direccion
                },
                cache: false,
                success: function(result)
                {
                    $( "#departamento" ).val('');
                    if(result!=0)
                    {
                       $( "#dep" ).html(result);
                    }
                    else
                    {
                        $( "#dep" ).html('<option value="PRESIDENCIA"></option>');
                    }
                }
            }); 
    });
});
