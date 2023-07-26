    function openTab(tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        document.getElementById(tabName).scrollIntoView({ behavior: 'smooth' });
    }

    function formVacio(checkbox) {
        if(checkbox.checked){
            document.getElementById("km_I"+checkbox.id[checkbox.id.length-1]).disabled = true;
            document.getElementById("km_F"+checkbox.id[checkbox.id.length-1]).disabled = true;
            document.getElementById("salida"+checkbox.id[checkbox.id.length-1]).disabled = true;
            document.getElementById("destino"+checkbox.id[checkbox.id.length-1]).disabled = true;
            document.getElementById("recorrido"+checkbox.id[checkbox.id.length-1]).disabled = true;
            document.getElementById("btn_vaciar"+checkbox.id[checkbox.id.length-1]).style.pointerEvents = "none";
            document.getElementById("btn_agregar"+checkbox.id[checkbox.id.length-1]).style.pointerEvents = "none";
         }else{
            document.getElementById("km_I"+checkbox.id[checkbox.id.length-1]).disabled = false;
            document.getElementById("km_F"+checkbox.id[checkbox.id.length-1]).disabled = false;
            document.getElementById("salida"+checkbox.id[checkbox.id.length-1]).disabled = false;
            document.getElementById("destino"+checkbox.id[checkbox.id.length-1]).disabled = false;
            document.getElementById("recorrido"+checkbox.id[checkbox.id.length-1]).disabled = false;
            document.getElementById("btn_vaciar"+checkbox.id[checkbox.id.length-1]).style.pointerEvents = "auto";
            document.getElementById("btn_agregar"+checkbox.id[checkbox.id.length-1]).style.pointerEvents = "auto";
         }
    }

    function siguiente_dia(dia){
        var dias_recorrido = document.getElementsByClassName("tablinks");
        var km_inicial = document.getElementById("km_I"+dia.id[dia.id.length - 1]);
        var km_final = document.getElementById("km_F"+dia.id[dia.id.length - 1]);
        var salida = document.getElementById("salida"+dia.id[dia.id.length - 1]);
        var listaR = document.getElementById("listaR"+dia.id[dia.id.length - 1]);
        
        if(!document.getElementById("vacio"+dia.id[dia.id.length - 1]).checked){
            if(!listaR.value)
                document.getElementById("destino"+dia.id[dia.id.length - 1]).reportValidity();

            salida.reportValidity();
            km_final.reportValidity();
            km_inicial.reportValidity();
        
            if(km_inicial.value && km_final.value && salida.value && listaR.value){
               
                if(parseInt(km_inicial.value)<parseInt(km_final.value)){
                    var i = 0;
                    while(i<dias_recorrido.length){
                        if(dias_recorrido[i].value == dia.parentNode.parentNode.parentNode.id){
                            i++;
                            break;
                        }
                        i++;
                    }

                    if((dias_recorrido.length-1) == i){
                        document.getElementById('boton_guardar'+i).style.display = "block";
                        document.getElementById(dias_recorrido[i].value).querySelector('#btn_sig'+i).style.visibility = "hidden";
                        document.getElementById(dias_recorrido[i].value).querySelector('#destino'+i).required = false;
                    }
                    openTab(dias_recorrido[i].value);
                    dias_recorrido[i].className += " active";

                    document.getElementById("km_I"+i).value = document.getElementById("km_F"+(i-1)).value;

                    if(i != 0){
                        document.getElementById(dias_recorrido[i].value).querySelector('#btn_ant'+i).style.visibility = "visible";
                    }
                }else
                    swal({
                        type: 'warning',
                        title: 'El KM inicial no puede ser mayor al KM final',
                        timer: 1500,
                        showConfirmButton: false
                    });
            }
        }else{
            var i = 0;
            while(i<dias_recorrido.length){
                if(dias_recorrido[i].value == dia.parentNode.parentNode.parentNode.id){
                    i++;
                    break;
                }
                i++;
            }

            if((dias_recorrido.length-1) == i){
                document.getElementById('boton_guardar'+i).style.display = "block";
                document.getElementById(dias_recorrido[i].value).querySelector('#btn_sig'+i).style.visibility = "hidden";
                document.getElementById(dias_recorrido[i].value).querySelector('#destino'+i).required = false;
            }
            openTab(dias_recorrido[i].value);
            dias_recorrido[i].className += " active";

            document.getElementById("km_I"+i).value = document.getElementById("km_I"+(i-1)).value;

            if(i != 0){
                document.getElementById(dias_recorrido[i].value).querySelector('#btn_ant'+i).style.visibility = "visible";
            }    
        }
        //listaR.readOnly = !listaR.readOnly;
    }

    function anterior_dia(dia){
        var dias_recorrido = document.getElementsByClassName("tablinks");

        var i = 0;
            while(i<dias_recorrido.length){
                if(dias_recorrido[i].value == dia.parentNode.parentNode.parentNode.id){
                    i--;
                    break;
                }
                i++;
            }
            openTab(dias_recorrido[i].value);
            dias_recorrido[i].className += " active";
    }

    function listarRecorrido(seleccionado) {
        if(seleccionado.value != "")
            document.getElementById("listaR"+seleccionado.id[seleccionado.id.length -1]).value += seleccionado.value + '\n';
        seleccionado.value = "";
    }

    function vaciar(bntvaciar) {
        document.getElementById("listaR"+bntvaciar.id[bntvaciar.id.length - 1]).value = "";
    }

    function nuevoRecorrido(btnNuevo) {
        var nuevoR = document.getElementById("recorrido"+btnNuevo.id[btnNuevo.id.length - 1]);
        
        if(nuevoR.value){
            nuevoR.value = nuevoR.value.toUpperCase();
            $.ajax({
                method: "POST",
                url: "controller/controller.php",
                data: {lugar: nuevoR.value, funcion: "nuevo_recorrido"},
                cache: false,
                success: function(result) {
                    if (result == 1) {
                        document.getElementById("listaR"+btnNuevo.id[btnNuevo.id.length -1]).value += nuevoR.value + '\n';
                        swal({
                            type: 'success',
                            title: 'Operacion exitosa',
                            timer: 1000,
                            showConfirmButton: false
                        });

                        var nuevaOpcion = document.createElement('option');
                        nuevaOpcion.value = nuevoR.value;
                        nuevaOpcion.textContent = nuevoR.value;
                        for (var i = 0; i < document.getElementsByClassName("tablinks").length; i++)
                            document.getElementById("destino"+i).appendChild(nuevaOpcion.cloneNode(true));

                        nuevoR.value = "";
                        
                    }else{
                        swal({
                            type: 'warning',
                            title: 'Recorrido repetido',
                            timer: 1000,
                            showConfirmButton: false
                        });
                        nuevoR.value = "";
                    }
                }
            });
            
        }else
            swal({
                type: 'error',
                title: 'Ingrese un nuevo recorrido',
                timer: 1000,
                showConfirmButton: false
            });
    }

    function Nbitacora(dia) {
        var dias_recorrido = document.getElementsByClassName("tablinks");
        var km_inicial = document.getElementById("km_I"+dia.id[dia.id.length - 1]);
        var km_final = document.getElementById("km_F"+dia.id[dia.id.length - 1]);
        var salida = document.getElementById("salida"+dia.id[dia.id.length - 1]);
        var listaR = document.getElementById("listaR"+dia.id[dia.id.length - 1]);

        if(!document.getElementById("vacio"+dia.id[dia.id.length - 1]).checked){
            if(!listaR.value)
                document.getElementById("destino"+dia.id[dia.id.length - 1]).reportValidity();

            salida.reportValidity();
            km_final.reportValidity();
            km_inicial.reportValidity();

            if(km_inicial.value && km_final.value && salida.value && listaR.value){
                $.ajax({
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
                                timer: 1000,
                                showConfirmButton: false
                            });
                            //setTimeout(function() { document.location.href = 'index.php'; }, 1100);
                        }
                    }
                });

                var i = 0;
                while (i < dias_recorrido.length) {
                    document.getElementById("listaR"+i).disabled = false;
                    $.ajax({
                        type: "POST",
                        url: "controller/controller.php",
                        data: $("#formulario_recorrido"+i).serialize(),
                        cache: false,
                        //success: function(result) {
                            //console.log(result);
                            /*if (result == 1) {
                                swal({
                                    type: 'success',
                                    title: 'Solicitud Generada',
                                    timer: 1000,
                                    showConfirmButton: false
                                });
                                setTimeout(function() { document.location.href = 'index.php'; }, 1100);
                            }*/
                        //}
                    });
                    i++;
                }
                setTimeout(function() { document.location.href = 'index.php'; }, 1100);
            }
        }else if(dias_recorrido.length == 1){//verica si solo se va a registrar un dia y que no este vacio el formulario
            swal({
                type: 'warning',
                title: 'El recorrido no debe estar vacío',
                timer: 1000,
                showConfirmButton: false
            });
            //setTimeout(function() { document.location.href = 'index.php'; }, 1100);
        }else if(dias_recorrido.length > 1){ //verifica si hay mas de un dia y por lo menos 1 debe reistrarse
            var i = 0;
            var aux = true;
            while (i < dias_recorrido.length) {
                if(!document.getElementById("vacio"+i).checked){aux = false; break;}
                i++;
            }

            if(aux == true){
                swal({
                    type: 'warning',
                    title: 'Favor de llenar un formulario',
                    timer: 1000,
                    showConfirmButton: false
                });
            }
        }
    }