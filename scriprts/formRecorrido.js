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
        //evt.currentTarget.className += " active";
    }

    function siguiente_dia(dia){
        var dias_recorrido = document.getElementsByClassName("tablinks");
        var km_inicial = document.getElementById("km_I"+dia.id[dia.id.length - 1]);
        var km_final = document.getElementById("km_F"+dia.id[dia.id.length - 1]);
        var salida = document.getElementById("salida"+dia.id[dia.id.length - 1]);
        var listaR = document.getElementById("listaR"+dia.id[dia.id.length - 1]);
        
        if(!listaR.value)
            document.getElementById("destino"+dia.id[dia.id.length - 1]).reportValidity();

        salida.reportValidity();
        km_final.reportValidity();
        km_inicial.reportValidity();

        if(km_inicial.value && km_final.value && salida.value && listaR.value){
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
            }
            openTab(dias_recorrido[i].value);
            dias_recorrido[i].className += " active";

            document.getElementById("km_I"+i).value = document.getElementById("km_F"+(i-1)).value;

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
            document.getElementById("listaR"+seleccionado.name[seleccionado.name.length -1]).value += seleccionado.value + '\n';
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
                            title: 'Recorrido agregado con éxito',
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

    