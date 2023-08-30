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
    $('#fecha_r_e').datepicker();
    $('#fecha_d_e').datepicker();

    $("#numero_control").on("change", function(event){
        event.preventDefault();
        var Ncontrol = $(this).val();

        $.ajax({
            method: "POST",
            url: "controller/controller.php",
            data: {control: Ncontrol, funcion: "nombre_empleado"},
            cache: false,
            success: function(result){
                //console.log(result);
                if(result!=0){
                    var resultados = JSON.parse(result);
                    $("#N_O").css("opacity", 1);
                    $("#N_operador").val(resultados);
                }else{
                    $("#N_O").css("opacity", 0);

                    if (Ncontrol != ""){
                        $("#numero_control").val("");
                        swal({
                        type: 'error',
                        title: 'El número no existe en el sistema',
                        timer: 1000,
                        showConfirmButton: false});
                        $("#recorridos").css("display", "none");
                        $("#sig_bitacora").css("display", "block");
                    }else{
                        swal({
                            type: 'warning',
                            title: 'Ingrese un número de control',
                            timer: 1000,
                            showConfirmButton: false});
                            $("#recorridos").css("display", "none");
                            $("#sig_bitacora").css("display", "block");
                    }
                }
            }
        });
    });

    $("#numero_control_e").on("change", function(event){
        event.preventDefault();
        var Ncontrol = $(this).val();

        $.ajax({
            method: "POST",
            url: "controller/controller.php",
            data: {control: Ncontrol, funcion: "nombre_empleado"},
            cache: false,
            success: function(result){
                //console.log(result);
                if(result!=0){
                    var resultados = JSON.parse(result);
                    $("#N_O_e").css("opacity", 1);
                    $("#N_operador_e").val(resultados);
                }else{
                    $("#N_O_e").css("opacity", 0);

                    if (Ncontrol != ""){
                        $("#numero_control_e").val("");
                        swal({
                        type: 'error',
                        title: 'El número no existe en el sistema',
                        timer: 1000,
                        showConfirmButton: false});
                        $("#recorridos_e").css("display", "none");
                        $("#sig_bitacora_e").css("display", "block");
                    }else{
                        swal({
                            type: 'warning',
                            title: 'Ingrese un número de control',
                            timer: 1000,
                            showConfirmButton: false});
                            $("#recorridos_e").css("display", "none");
                            $("#sig_bitacora_e").css("display", "block");
                    }
                }
            }
        });
    });
    
    $("#idVehiculo").on("change", function(event){ //jQuery
        event.preventDefault();
        var idVehiculo = $("#idVehiculo").val();
        if(idVehiculo!=''){
            obtenerResultados();
            $.ajax({
                method: "POST",
                url: "controller/controller.php",
                data: {id: idVehiculo, funcion: "vehiculo"},
                cache: false,
                success: function(result){
                    //console.log(result);
                    if(result!=0){
                        var resultados = JSON.parse(result);
                        $("#M_m").css("opacity", 1);
                        $("#marca_modelo").val(resultados.modelo);

                        $("#p").css("opacity", 1);
                        $("#placas").val(resultados.placas);
                        $("#comb").css("opacity", 1);
                        $("#combustible").val(resultados.comb);
                        $("#km_I0").val(resultados.km);
                    }else{
                        $("#M_m").css("opacity", 0);
                        $("#p").css("opacity", 0);
                        $("#comb").css("opacity", 0);

                        $("#idVehiculo").val("");
                        swal({
                            type: 'error',
                            title: 'La unidad no existe en el sistema',
                            timer: 1000,
                            showConfirmButton: false
                        });
                        $("#recorridos").css("display", "none");
                        $("#sig_bitacora").css("display", "block");
                    }
                }
            }); 
        }else{
            $("#M_m").css("opacity", 0);
            $("#p").css("opacity", 0);
            $("#comb").css("opacity", 0);
            $("#recorridos").css("display", "none");
            $("#sig_bitacora").css("display", "block");
            swal({
                type: 'warning',
                title: 'Ingrese una unidad',
                timer: 1000,
                showConfirmButton: false
            }); 
        }      
    });

    $("#idVehiculo_e").on("change", function(event){ //jQuery
        var dias_recorrido = document.getElementsByClassName("tablinks_e");
        event.preventDefault();
        var idVehiculo = $("#idVehiculo_e").val();
        if(idVehiculo!=''){
            $.ajax({
                method: "POST",
                url: "controller/controller.php",
                data: {id_bitacora: id_bitacora,unidad: idVehiculo, funcion: "bitacora_existente"},
                cache: false,
                success: function(result){
                    if(result==1){
                        obtenerResultados();
                        $.ajax({
                            method: "POST",
                            url: "controller/controller.php",
                            data: {id: idVehiculo, funcion: "vehiculo"},
                            cache: false,
                            success: function(result){
                                //console.log(result);
                                if(result!=0){
                                    var resultados = JSON.parse(result);

                                    var j=0;
                                    while(j<dias_recorrido.length){
                                        document.getElementById("id_recorrido"+j).value = datos[0][dias_recorrido[j].value]["id_recorrido"];
                                        j++;
                                    }
                                    $("#M_m_e").css("opacity", 1);
                                    $("#marca_modelo_e").val(resultados.modelo);

                                    $("#p_e").css("opacity", 1);
                                    $("#placas_e").val(resultados.placas);
                                    $("#comb_e").css("opacity", 1);
                                    $("#combustible_e").val(resultados.comb);
                                    $("#km_I_e0").val(resultados.km);
                                }else{
                                    $("#M_m_e").css("opacity", 0);
                                    $("#p_e").css("opacity", 0);
                                    $("#comb_e").css("opacity", 0);

                                    $("#idVehiculo_e").val("");
                                    swal({
                                        type: 'error',
                                        title: 'La unidad no existe en el sistema',
                                        timer: 1000,
                                        showConfirmButton: false
                                    });
                                    $("#recorridos_e").css("display", "none");
                                    $("#sig_bitacora_e").css("display", "block");
                                }
                            }
                        });
                    }else{
                        swal({
                            type: 'warning',
                            title: 'El vehículo ya tiene registrado una bitácora más actual',
                            timer: 1500,
                            showConfirmButton: false});
                            setTimeout(function() {$("#idVehiculo_e").val(NoUnidad);}, 1500);
                        }
                }}); 
        }else{
            $("#M_m_e").css("opacity", 0);
            $("#p_e").css("opacity", 0);
            $("#comb_e").css("opacity", 0);
            $("#recorridos_e").css("display", "none");
            $("#sig_bitacora_e").css("display", "block");
            swal({
                type: 'warning',
                title: 'Ingrese una unidad',
                timer: 1000,
                showConfirmButton: false
            }); 
        }      
    });

    $("#sig_bitacora").on("click", function(event){
        event.preventDefault();
        var unidad = document.getElementById('idVehiculo');
        var empleado = document.getElementById('numero_control');
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
                    //console.log(result);
                    if(result!=0){
                        var resultados = JSON.parse(result);
                        document.getElementById('M_m').style.opacity = 1;
                        document.getElementById('marca_modelo').value = resultados.modelo;

                        document.getElementById('p').style.opacity = 1;
                        document.getElementById('placas').value = resultados.placas;
                        document.getElementById('comb').style.opacity = 1;
                        document.getElementById('combustible').value = resultados.comb;
                            recorridos.style.display= 'block';
                            document.getElementById('sig_bitacora').style.display = "none";

                            $.ajax({
                                method: "POST",
                                url: "controller/controller.php",
                                data: {id: 0, funcion: "num_bitacoras"},
                                cache: false,
                                success: function (result) {
                                    document.getElementById("sig").value = JSON.parse(result);
                                    //var j = 0;
                                    //while (j < dias_recorrido.length) {$("#num_bitacoras"+j).val(0); j++;}
                                }
                            });

                            openTab(dias_recorrido[0].value);
                            //for(i=0; i<dias_recorrido.length; i++)
                                //dias_recorrido[i].disabled = true;
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
                            document.getElementById('comb').style.opacity = 0;
                    }
                }
            });

            $.ajax({
                method: "POST",
                url: "controller/controller.php",
                data: {control: empleado.value, funcion: "nombre_empleado"},
                cache: false,
                success: function(result){
                    //console.log(result);
                    if(result!=0){
                        var resultados = JSON.parse(result);
                        $("#N_O").css("opacity", 1);
                        $("#N_operador").val(resultados);
                    }else{
                        $("#N_O").css("opacity", 0);
    
                        if (Ncontrol != ""){
                            $("#numero_control").val("");
                            swal({
                            type: 'error',
                            title: 'La número no existe en el sistema',
                            timer: 1000,
                            showConfirmButton: false});
                            $("#recorridos").css("display", "none");
                            $("#sig_bitacora").css("display", "block");
                        }else{
                            swal({
                                type: 'warning',
                                title: 'Ingrese un número de control',
                                timer: 1000,
                                showConfirmButton: false});
                                $("#recorridos").css("display", "none");
                                $("#sig_bitacora").css("display", "block");
                        }
                    }
                }
            });
        }
    });

    $("#sig_bitacora_e").on("click", function(event){
        event.preventDefault();
        var unidad = document.getElementById('idVehiculo_e');
        var empleado = document.getElementById('numero_control_e');
        var recorridos = document.getElementById('recorridos_e');
        var dias_recorrido = document.getElementsByClassName("tablinks_e");
        unidad.reportValidity();
        empleado.reportValidity();
        
        if((empleado.value != '') && (unidad.value != '')){
        
            $.ajax({
                method: "POST",
                url: "controller/controller.php",
                data: {id: unidad.value, funcion: "vehiculo"},
                cache: false,
                success: function(result){
                    //console.log(result);
                    if(result!=0){
                        var resultados = JSON.parse(result);
                        document.getElementById('M_m_e').style.opacity = 1;
                        document.getElementById('marca_modelo_e').value = resultados.modelo;

                        document.getElementById('p_e').style.opacity = 1;
                        document.getElementById('placas_e').value = resultados.placas;
                        document.getElementById('comb_e').style.opacity = 1;
                        document.getElementById('combustible_e').value = resultados.comb;
                            recorridos.style.display= 'block';
                            document.getElementById('sig_bitacora_e').style.display = "none";
                            
                            openTab_e(dias_recorrido[0].value);

                            for(i=0; i<dias_recorrido.length; i++)
                                dias_recorrido[i].disabled = true;
                            dias_recorrido[0].className += " active";

                            if (NoUnidad == unidad.value) {
                                var j=0;
                                while(j<dias_recorrido.length){
                                    if(dias_recorrido[j].value in datos[0] && datos[0][dias_recorrido[j].value]["vacio"] == 0){
                                        document.getElementById("id_recorrido"+j).value = datos[0][dias_recorrido[j].value]["id_recorrido"];

                                        if(datos[0][dias_recorrido[j].value]["km_inicial"])
                                            document.getElementById("km_I_e"+j).value = datos[0][dias_recorrido[j].value]["km_inicial"];
                                        else
                                            document.getElementById("km_I_e"+j).value = km_ant;
                                        document.getElementById("km_F_e"+j).value = datos[0][dias_recorrido[j].value]["km_final"];
                                        document.getElementById("salida_e"+j).value = datos[0][dias_recorrido[j].value]["salida"];
                                        document.getElementById("listaR_e"+j).value = datos[0][dias_recorrido[j].value]["recorrido"];
                                    }else{
                                        document.getElementById("id_recorrido"+j).value = datos[0][dias_recorrido[j].value]["id_recorrido"];
                                        document.getElementById("vacio_e"+j).checked = true;
                                        if(j == 0)
                                            document.getElementById("km_I_e"+j).value = km_ant;
                                        document.getElementById("km_I_e"+j).disabled = true;
                                        document.getElementById("km_F_e"+j).disabled = true;
                                        document.getElementById("salida_e"+j).disabled = true;
                                        document.getElementById("recorrido_e"+j).disabled = true;
                                        document.getElementById("btn_vaciar_e"+j).style.pointerEvents = "none";
                                        document.getElementById("btn_agregar_e"+j).style.pointerEvents = "none";
                                    }   
                                    j++;
                                }
                            }else{
                                document.getElementById("km_I_e0").value = resultados.km;
                            }
                            
                    }else{
                        swal({
                            type: 'error',
                            title: 'La unidad no existe en el sistema',
                            timer: 1000,
                            showConfirmButton: false
                        });
                            document.getElementById('recorridos_e').style.display= 'none';
                            document.getElementById('sig_bitacora_e').style.display = 'block';
                            document.getElementById('M_m_e').style.opacity = 0;
                            document.getElementById('p_e').style.opacity = 0;
                            document.getElementById('comb_e').style.opacity = 0;
                    }
                }
            });

            $.ajax({
                method: "POST",
                url: "controller/controller.php",
                data: {control: empleado.value, funcion: "nombre_empleado"},
                cache: false,
                success: function(result){
                    //console.log(result);
                    if(result!=0){
                        var resultados = JSON.parse(result);
                        $("#N_O_e").css("opacity", 1);
                        $("#N_operador_e").val(resultados);
                    }else{
                        $("#N_O_e").css("opacity", 0);
    
                        if (Ncontrol != ""){
                            $("#numero_control_e").val("");
                            swal({
                            type: 'error',
                            title: 'La número no existe en el sistema',
                            timer: 1000,
                            showConfirmButton: false});
                            $("#recorridos_e").css("display", "none");
                            $("#sig_bitacora_e").css("display", "block");
                        }else{
                            swal({
                                type: 'warning',
                                title: 'Ingrese un número de control',
                                timer: 1000,
                                showConfirmButton: false});
                                $("#recorridos_e").css("display", "none");
                                $("#sig_bitacora_e").css("display", "block");
                        }
                    }
                }
            });
        }
    });

    $("#cancelar_e").on("click", function(event){
        document.location.href = 'Busqueda.php';
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
});
