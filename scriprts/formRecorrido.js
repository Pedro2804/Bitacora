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

        if(i != 0){
            document.getElementById(dias_recorrido[i].value).querySelector('#btn_ant'+i).style.visibility = "visible";
        }
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

    var aux = "";

    function listarRecorrido(seleccionado) {
        if(seleccionado.value != "")
            document.getElementById("listaR"+seleccionado.name[seleccionado.name.length -1]).value += seleccionado.value + '\n';
        seleccionado.value = "";
    }

    function vaciar(bntvaciar) {
        document.getElementById("listaR"+bntvaciar.id[bntvaciar.id.length - 1]).value = "";
    }

    function nuevo_recorrido(btnNuevo) {
        var nuevoR = document.getElementById("recorrido"+btnNuevo.id[btnNuevo.id.length - 1]);
        if(nuevoR.value){
            document.getElementById("listaR"+btnNuevo.id[btnNuevo.id.length -1]).value += nuevoR.value + '\n';
            nuevoR.value = "";
        }
    }