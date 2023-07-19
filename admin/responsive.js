function ajustarMargenSiguiente() {
    var cssmenu = document.getElementById('cssmenu');
    var logodif = document.getElementById('logodif');
    var cerrar = document.getElementById('cerrarmenu');
    var margenSuperior = cssmenu.offsetHeight + logodif.offsetHeight + cerrar.offsetHeight;
  
    var barra_superior = document.getElementById('prin_admin');
    barra_superior.style.marginTop = margenSuperior + 'px';
  }

  window.addEventListener('load', ajustarMargenSiguiente);
  //window.addEventListener('resize', ajustarMargenSiguiente);