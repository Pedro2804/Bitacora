function cpf(v){
    v=v.toString();
    v=v.replace(/([^0-9\.]+)/g,''); 
    v=v.replace(/^[\.]/,''); 
    v=v.replace(/[\.][\.]/g,''); 
    v=v.replace(/\.(\d)(\d)(\d)/g,'.$1$2'); 
    v=v.replace(/\.(\d{1,2})\./g,'.$1'); 
    v = v.toString().split('').reverse().join('').replace(/(\d{3})/g,'$1,');    
    v = v.split('').reverse().join('').replace(/^[\,]/,'');
    return v;  
}

function editar(id){
    $.ajax(
    {
			type: "POST",
			url: "../controller/controller.php",
			data:
            {
                funcion:'get_vehiculo',
                id:id
            },
			cache: false,
			success: function(result)
			{
                //console.log(result);
                var obj = JSON.parse(result);
                var marca=obj['marca'];
                var modelo=obj['modelo'];
                var placas=obj['placas'];
                var combustible=obj['tipo_combustible'];
                var kilometraje=obj['kilometraje'];     
                
                $('#id').val(id);
                $('#num_unidad').val(obj['num_unidad']);
                $('#marca').val(marca);
                $('#modelo').val(modelo);
                $('#placas').val(placas);
                $('#tipo_combustible').val(combustible);
                $('#kilometraje').val(kilometraje);
			}
		});
}

function xportpdf(){
    var doc = new jsPDF();
    var specialElementHandlers = {
        '#editor': function (element, renderer) {
            return true;
        }
    };

        doc.fromHTML($('#reporte_candidato').html(), 15, 15, {
            'width': 170,
                'elementHandlers': specialElementHandlers
        });
        doc.save('sample-file.pdf');
}

function eliminar(id){
  swal({
    title: '¿Seguro que quiere eliminar?',
    text: "El vehículo se eliminará",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    cancelButtonText: 'No',
    confirmButtonText: 'Si'
    }).then((result) =>{
        if (result){
            $.ajax({
                type: "POST",
                url: "../controller/controller.php",
                data: {funcion:'eliminar_auto', num_unidad:id},
                cache: false,
                success: function(result){
                    swal({
                        title:'Eliminado',
                        type: 'success',
                        timer: 1000,
                        showConfirmButton: false
                        //allowOutsideClick: false,
                        //confirmButtonText: 'Aceptar',
                    }).then(function(){document.location.href="../admin/autos.php";});//Sirve para cuando hay opciones de aceptar en el swal
                    
                    setTimeout(function() {document.location.href = '../admin/autos.php';}, 1100);
                }
		    });
        }
    });
}

function eliminar_bitacora(id, unidad){
  swal({
    title: '¿Seguro que quiere eliminar?',
    text: "La bitácora se eliminará",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    cancelButtonText: 'No',
    confirmButtonText: 'Si'
    }).then((result) =>{
        if (result){
            $.ajax({ //Actualizar el km al anterior
                type: "POST",
                url: "eliminarBitacora.php",
                data: {id: id, unidad: unidad},
                cache: false
                /*success: function(result){
                    console.log(result);
                }*/
		    });

            $.ajax({
                type: "POST",
                url: "controller/controller.php",
                data: {funcion:'eliminar_bitacora', id_bitacora:id},
                cache: false,
                success: function(result){
                    console.log(result);
                    swal({
                        title:'Bitácora eliminada',
                        type: 'success',
                        timer: 1000,
                        showConfirmButton: false
                        //allowOutsideClick: false,
                        //confirmButtonText: 'Aceptar',
                    }).then(function(){document.location.href="Busqueda.php";});//Sirve para cuando hay opciones de aceptar en el swal
                    
                    //setTimeout(function() {document.location.href = 'Busqueda.php';}, 1100);
                }
		    });
        }
    });
}

$(document).ready(function(){
    //display_direcciones();

    $(document).ready(function(){ //estaba antes...
        var buttonCommon = {
            exportOptions: {
                format: {
                    body: function ( data, row, column, node ) {
                        // Strip $ from salary column to make it numeric
                        return column === 5 ?
                            data.replace( /[$,]/g, '' ) :
                            data;
                    }
                }
            }
        }
        var tablePC = $('#datatables-example').DataTable({
            "ajax":
            {
                "url": "../controller/controller.php",
                "type": "POST",
                "data": { "funcion":'mostrar_vehiculos' }
            },
            "language":
            {
                "url": "//cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
            },
            "columnDefs":
            [{
                 "targets": -1,
                    "data": 0,
                    "render": function (txt, type, full)
                    {
                            var id=full[0];
                            return '<img style="cursor:pointer;" title="Editar"  data-toggle="modal" data-target="#modal_editar" width="30" height="30" src="../img/pencil.png" onclick="editar('+id+');" id=""/> <img style="cursor:pointer;" title="Eliminar" width="30" height="30" src="../img/delete.png" onclick="eliminar('+id+');" id=""/>';    
                    }

            }]
        });
    });

    $( "#form_editar" ).submit(function( event ){
        event.preventDefault();
		$.ajax({
			type: "POST",
			url: "../controller/controller.php",
			data:  $("#form_editar").serialize(),
			cache: false,
			success: function(result){
                console.log(result);
				if(result==1){
                    swal({
                        title:'Correcto',
                        text: "Operacion exitosa",
                        type: 'success',
                        //allowOutsideClick: false,
                        timer: 1000,
                        showConfirmButton: false
                        //confirmButtonText: "Aceptar",
                        }).then(function(){document.location.href="../admin/autos.php";});
                        
                        setTimeout(function() {document.location.href = '../admin/autos.php';}, 1100);
				}
				//else console.log(result);
			}
		});
	});

    $( "#form_nuevo_auto" ).submit(function( event ){
        event.preventDefault();
		$.ajax({
			type: "POST",
			url: "../controller/controller.php",
			data:  $("#form_nuevo_auto").serialize(),
			cache: false,
			success: function(result){
                console.log(result);
				if(result==1){
                    swal({
                        title:'Correcto',
                        text: "Operacion exitosa",
                        type: 'success',
                        //allowOutsideClick: false,
                        timer: 1000,
                        showConfirmButton: false
                        //confirmButtonText: "Aceptar",
                        }).then(function(){document.location.href="../admin/nuevo_vehiculo.php";}); 

                        //$("#form_nuevo_auto")[0].reset();                  
				}else
                    swal({
                        type: 'warning',
                        title: 'Vehiculo repetido',
                        timer: 1000,
                        showConfirmButton: false
                    });

                $("#form_nuevo_auto")[0].reset();
				//else console.log(result);
			}
		});
	});
});
