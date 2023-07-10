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
function agregar_solicitud_reporte(clave)
{
    var check=$('#agrega_s_'+clave).prop('checked');
    var claves_sa=$('#claves_solicitud_sa').val();
    if(check==1)
    {
        claves_sa=claves_sa+clave+',';
        $('#claves_solicitud_sa').val(claves_sa);       
    }
    else
    {
        claves_sa=claves_sa.replace(clave+',',"");
        if(claves_sa==0){claves_sa='';}
        $('#claves_solicitud_sa').val(claves_sa);       
    }
}
function cambiar_fecha(clave)
{
    var fech_a = $('#fech_solicitud_'+clave).val();
    if(fech_a=='')
    {
        fech_a='no';
        $('#estatus_'+clave).val('RECIBIDO');
    }
    else
    {
        $('#estatus_'+clave).val('ATENDIDO');
    }
    $.ajax
    ({
        type: "POST",
        url: "controller/controller.php",
        data:
        {
            funcion:'editar_solicitud',
            e_descripcion_s:'',
            fecha_a:fech_a,
            clave_editar:clave
        },
        cache: false,
        success: function(result)
        {
            if(result==1)
            {

            }
        }
    });
}
function cambiar_fecha_sa(clave)
{
    var fech_a = $('#fech_solicitud_sa_'+clave).val();
    if(fech_a=='')
    {
        fech_a='no';
        $('#estatus_sa'+clave).val('RECIBIDO');
    }
    else
    {
        $('#estatus_sa'+clave).val('ATENDIDO');
    }
    $.ajax
    ({
        type: "POST",
        url: "controller/controller.php",
        data:
        {
            funcion:'editar_solicitud',
            e_descripcion_s:'',
            fecha_a:fech_a,
            clave_editar:clave
        },
        cache: false,
        success: function(result)
        {
            if(result==1)
            {

            }
        }
    });
}
function procesar_reporte()
{
    var existe_folio=$('#folio_generado').val();
    var doc=$('#doc_respalda').val();
    var obs=$('#observaciones').val();
    if(existe_folio==0)
    {
        swal(
              'Reporte Vacio',
              'No hay Reportes para guardar, o no se ha asignado un folio',
              'info'
                );
        var fol=$('#claves_solicitud').val();
    }
    else if(doc=='')
    {
        swal(
              'Campo Obligatorio',
              'El campo documento es requerido',
              'info'
                );
        $('#doc_respalda').focus();
    }
    else
    {
        $.ajax
        ({
            type: "POST",
            url: "controller/controller.php",
            data:$("#form_reporte").serialize(),
            cache: false,
            success: function(result)
            {
                if(result==1)
                {
                        swal({
                        title:'Reporte Guardado',
                        type: 'success',
                        allowOutsideClick: false,
                        confirmButtonText: 'Aceptar',
                        }).then(function()
                        {
                            window.location="busquedanoticia.php";
                        });
                }
            }
        }); 
    }
}
function validar_fecha(clave)
{
    var f_inicio=$('#fecha_inicio').val();
    var f_final=$('#fecha_final').val();
    
    var f_atendido=$('#fech_solicitud_'+clave).val();
    if(f_atendido >= f_inicio && f_atendido <= f_final)
    {
        cambiar_fecha(clave);
    }
    else
    {
        if(f_atendido=='')
        {
            cambiar_fecha(clave);
        }
        else
        {
            swal(
              'Fecha no valida',
              'La fecha debe estar entre el intervalo seleccionado',
              'info'
                );
            $('#fech_solicitud_'+clave).val('');
        }
    }
    
}
function validar_fecha_sa(clave)
{
    var f_inicio=$('#fecha_inicio').val();
    var f_final=$('#fecha_final').val();
    
    var f_atendido=$('#fech_solicitud_sa_'+clave).val();
    if(f_atendido >= f_inicio && f_atendido <= f_final)
    {
        cambiar_fecha_sa(clave);
    }
    else
    {
        if(f_atendido=='')
        {
            cambiar_fecha_sa(clave);
        }
        else
        {
            swal(
              'Fecha no valida',
              'La fecha debe estar entre el intervalo seleccionado',
              'info'
                );
            $('#fech_solicitud_sa_'+clave).val('');
        }
    }
    
}
function generar_folios(clave)
{
    var fol=$('#fol_ex').val();
    if(fol==clave)
    {
        $('#folio_generado').val('1');
        var numero_inicial=$('#folio_'+clave).val();
        $('#folios_g').val(numero_inicial);
        var array_inputs= $('#inputs_folio').val();
        var array = array_inputs.split(",");
        //array=array.unique();
        var indice_array=array.length;
        indice_array=indice_array-1;
        var indice_total=indice_array/2; 
        for (x=1;x<indice_total;x++)
        {
            numero_inicial++;
            var input=array[x];
            $('#'+input).val(numero_inicial);
        }
    }
    
}
function primer_folio(clave)
{
    var claves_solicitud_n=$('#claves_solicitud').val();
    var fol=$('#fol_ex').val();
    var input ='folio_'+clave+',';
    var array_inputs= $('#inputs_folio').val();
    array_inputs=array_inputs+input;
    if(fol==0)
    {
        $('#fol_ex').val(clave);
    }
    $('#inputs_folio').val(array_inputs);
    claves_solicitud_n=claves_solicitud_n+clave+',';
    $('#claves_solicitud').val(claves_solicitud_n);
}
function crear_picker(clave)
{
    $('#fech_solicitud_'+clave).datepicker(); 
}
function crear_picker_sa(clave)
{
    $('#fech_solicitud_sa_'+clave).datepicker(); 
}
function dato(clave)
{
    var global=$('#clave_global').val();
    var data=$('#desc_'+clave).val();
    if(global!=clave)
    {
        $('#dato_desc').val(data);
        $('#clave_global').val(clave);
    }
}
function editar_servicio(clave)
{
    var desc=$('#desc_'+clave).val();
    var antiguo = $('#dato_desc').val();
    if(desc!='')
    {
        $.ajax
        ({
            type: "POST",
            url: "controller/controller.php",
            data:
            {
                funcion:'editar_solicitud',
                e_descripcion_s:desc,
                clave_editar:clave
            },
            cache: false,
            success: function(result)
            {
                if(result==1)
                {
                  
                }
            }
        }); 
    }
    else
    {
        $('#desc_'+clave).val(antiguo);
    }
}
function cancelar_e_servicio(clave,descripcion)
{
    $('#modal_editar_descripcion').modal('toggle');
}
$(document).ready(function()
{
    var hoy = new Date();
    var año = hoy.getFullYear();
    var mes = hoy.getMonth()+1;
    var dia = hoy.getDate();
    if(dia<10) {dia='0'+dia} 
    if(mes<10) {mes='0'+mes} 
    var fecha_hoy=año+'-'+mes+'-'+dia;
    $('#fecha_final').val(fecha_hoy);

    
    hoy.setDate(hoy.getDate() -6);
    var año = hoy.getFullYear();
    var mes = hoy.getMonth()+1;
    var dia = hoy.getDate();
    if(dia<10) {dia='0'+dia} 
    if(mes<10) {mes='0'+mes} 
    var fecha_anterior=año+'-'+mes+'-'+dia;

    $('#fecha_inicio').val(fecha_anterior);
    

    function creartabla_no_atendidas()
    {
        var f_inicio=$('#fecha_inicio').val();
        var f_final=$('#fecha_final').val();

        var table_no_a = $('#datatable_solicitudes_sin_atender').DataTable(
        {
            "destroy":true,
            "ordering": false,
            "searching": false,
            "search":false,
            "paging": false,
            "ajax":
            {
                "url": "controller/controller.php",
                "type": "POST",
                "data":
                {
                    "funcion":'mostrar_solicitudes_sin_atender',
                    "f_inicio":f_inicio,
                    "f_final":f_final
                }
            },
            "language":
            {
                "url": "//cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
            },
            "columnDefs":
            [
                /*{
                    "targets": 2,
                    "data": 0,
                    "render": function (txt,type,full)
                    {
                        var desc=full[2];
                        var clave=full[6];
                        var desc_s="'"+full[2]+"'";
                        return '<textarea class="area_tab" onclick="dato('+clave+')" onchange="editar_servicio('+clave+')" id="desc_'+clave+'" >'+desc+'</textarea>';

                    }
                },*/
                {
                    "targets": 5,
                    "data": 0,
                    "render": function (txt,type,full)
                    {
                        var fecha_a=full[5];
                        if(fecha_a==null)
                        {
                           fecha_a='';
                        }
                        var clave=full[7];
                        var desc_s="'"+full[2]+"'";
                        return '<input onchange="validar_fecha_sa('+clave+')" onmouseover="crear_picker_sa('+clave+')" id="fech_solicitud_sa_'+clave+'" name="fech_solicitud_sa_'+clave+'" style="border: none;" type="text" class="input_tabla" value="'+fecha_a+'">';
                    }
                }/*,
                {
                    "targets": 0,
                    "data": 0,
                    "render": function (txt,type,full)
                    {
                        var fecha_a=full[4];
                        var clave=full[6];
                        primer_folio(clave);
                        return '<input onchange="generar_folios('+clave+')" id="folio_'+clave+'" name="folio_'+clave+'" style="border: none;" type="text" class="input_tabla folio">';

                    }
                }*/,
                {
                    "targets": 6,
                    "data": 0,
                    "render": function (txt,type,full)
                    {
                        var estatus=full[6];
                        var clave=full[7];
                        return '<input id="estatus_sa'+clave+'" name="estatus_sa'+clave+'" style="border: none;" type="text" class="input_tabla estatus" value="'+estatus+'" readonly>';

                    }
                },
                {
                    "targets": 7,
                    "data": 0,
                    "render": function (txt,type,full)
                    {
                        var estatus=full[6];
                        var clave=full[7];
                        return '<div class="form-group form-animate-checkbox"><input type="checkbox" class="checkbox" id="agrega_s_'+clave+'" name="agrega_s_'+clave+'" onchange="agregar_solicitud_reporte('+clave+')" ></div>';
                    }
                }
            ]
        });
        
    }
    
    
    function creartabla()
    {
        $('#claves_solicitud').val('');
        $('#folio_generado').val('0');
        $('#fol_ex').val('0');
        $('#inputs_folio').val('');
        var f_inicio=$('#fecha_inicio').val();
        var f_final=$('#fecha_final').val();

        var table = $('#datatable_nueva_noticia').DataTable(
        {
            "destroy":true,
            "ordering": false,
            "searching": false,
            "search":false,
            "paging": false,
            "ajax":
            {
                "url": "controller/controller.php",
                "type": "POST",
                "data":
                {
                    "funcion":'mostrar_solicitudes',
                    "f_inicio":f_inicio,
                    "f_final":f_final
                }
            },
            "language":
            {
                "url": "//cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
            },
            "columnDefs":
            [
                {
                    "targets": 2,
                    "data": 0,
                    "render": function (txt,type,full)
                    {
                        var desc=full[2];
                        var clave=full[7];
                        var desc_s="'"+full[2]+"'";
                        return '<textarea class="area_tab" onclick="dato('+clave+')" onchange="editar_servicio('+clave+')" id="desc_'+clave+'" >'+desc+'</textarea>';

                    }
                },
                {
                    "targets": 5,
                    "data": 0,
                    "render": function (txt,type,full)
                    {
                        var fecha_a=full[5];
                        
                        if(fecha_a==null)
                        {
                           fecha_a='';
                        }
                        var clave=full[7];
                        var desc_s="'"+full[2]+"'";
                        return '<input onchange="validar_fecha('+clave+')" onmouseover="crear_picker('+clave+')" id="fech_solicitud_'+clave+'" name="fech_solicitud_'+clave+'" style="border: none;" type="text" class="input_tabla" value="'+fecha_a+'">';
                    }
                },
                {
                    "targets": 0,
                    "data": 0,
                    "render": function (txt,type,full)
                    {
                        var fecha_a=full[5];
                        var clave=full[7];
                        primer_folio(clave);
                        return '<input onchange="generar_folios('+clave+')" id="folio_'+clave+'" name="folio_'+clave+'" style="border: none;" type="text" class="input_tabla folio">';

                    }
                },
                {
                    "targets": 6,
                    "data": 0,
                    "render": function (txt,type,full)
                    {
                        var estatus=full[6];
                        var clave=full[7];
                        return '<input id="estatus_'+clave+'" name="estatus_'+clave+'" style="border: none;" type="text" class="input_tabla estatus" value="'+estatus+'" readonly>';

                    }
                }
            ]
        });
        
    }
    
     creartabla();
    creartabla_no_atendidas();

    $('#fecha_inicio').datepicker();
    $('#fecha_final').datepicker();
    
    
    
    $( "#departamento" ).change(function()
    {

    });
     
    $( "#editar_solicitud" ).submit(function( event )
    {
        event.preventDefault();
        //var descripcion=$('#e_descripcion_s').val();
        //var entrega=$('#entrega').val();
        $.ajax
        ({
            type: "POST",
            url: "controller/controller.php",
            data: $("#editar_solicitud").serialize(),
            cache: false,
            success: function(result)
            {
                if(result==1)
                {
                   swal({
                          type: 'success',
                          title: 'Solicitud Generada',
                          timer: 1500,
                       showConfirmButton: false
                        });
                    $("#editar_solicitud")[0].reset();
                }
            }
        }); 
    });
    $( "#fecha_inicio" ).change(function()
    {
                
        var fecha_inicio=$('#fecha_inicio').val();
        var fecha_final=$('#fecha_final').val();
        
        var f_i = new Date(fecha_inicio);
        var f_f = new Date(fecha_final); 
        var resultado = f_i.getTime() > f_f.getTime();
        if(resultado==true)
        {
            $('#fecha_inicio').val(fecha_final);
            swal(
              'Fecha no valida',
              'La fecha de inicio no puede ser mayor que la final',
              'info'
                );
        }
        else
        {
            creartabla();
            creartabla_no_atendidas();
        }
    });
     $( "#fecha_final" ).change(function()
    {
        var fecha_inicio=$('#fecha_inicio').val();
        var fecha_final=$('#fecha_final').val();
        
        var f_i = new Date(fecha_inicio);
        var f_f = new Date(fecha_final); 
        var resultado = f_i.getTime() > f_f.getTime();
        if(resultado==true)
        {
            $('#fecha_final').val(fecha_inicio);
            swal(
              'Fecha no valida',
              'La fecha de inicio no puede ser mayor que la final',
              'info'
                );
        }
        else
        {
            creartabla();
            creartabla_no_atendidas();
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