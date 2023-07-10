function ajustarMargenSiguiente() {
    var cssmenu = document.getElementById('cssmenu');
    var logodif = document.getElementById('logodif');
    var margenSuperior = cssmenu.offsetHeight + logodif.offsetHeight;
  
    var barra_superior = document.getElementById('barra_superior');
    barra_superior.style.marginTop = margenSuperior + 'px';
  }

  window.addEventListener('load', ajustarMargenSiguiente);
  //window.addEventListener('resize', ajustarMargenSiguiente);