<!DOCTYPE html>
<html>
<head>
    <!-- librerias para jquery-->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
    <!-- mensajes de swal-->
    <link rel="stylesheet" type="text/css" href="style/sweetalert2.min.css">
    <script src="scriprts/sweetalert2.min.js"></script>
</head>
<body>
    <div class="tab_e">
        <?php
            foreach (new DatePeriod($fechaInicio, new DateInterval('P1D'), $fechaFin) as $fecha) {
                $diaSemana = $fecha->format('N'); // 1 (lunes) a 7 (domingo)
                $nombreDia = $diasSemana[$diaSemana].' '.$fecha->format('j'); // Ejemplo: "lunes 12"

                // Generar la pestaña
                echo '<button class="tablinks_e" value="'.$nombreDia.'" disabled>'.$nombreDia.'</button>';
            }
        ?>
    </div>

<!--Generar los formularios por cada fecha-->
    <?php
        $j = 0;
        foreach (new DatePeriod($fechaInicio, new DateInterval('P1D'), $fechaFin) as $fecha) {
            $diaSemana = $fecha->format('N'); // 1 (lunes) a 7 (domingo)
            $nombreDia = $diasSemana[$diaSemana].' '.$fecha->format('j'); // Ejemplo: "lunes 12"
    ?>   
    <!--Generar el formulario dentro de un div con el nombre de la pestaña-->
    <div id="<?php echo $nombreDia ?>" class="tabcontent_e" style="padding-top: 10px;">

        <!--Formulario-->
        <form id="form_editar_recorrido<?php echo $j ?>" method="get" action="">
            
            <input type="hidden" value="editar_recorrido" id="funcion" name="funcion">
            <input type="hidden" id="id_recorrido<?php echo $j ?>" name="id_recorrido" value="" />
            <input type="hidden" name="dia_semana_e" value="<?php echo $nombreDia ?>" />

            <div class="col-md-12" style="padding-top:20px;">
                <div class="form-group form-animate-checkbox">
                    <input id="vacio_e<?php echo $j ?>" class="checkbox" onclick="formVacio_e(this)" type="checkbox" name="vacio_e" />
                    <label>VACIO</label>
                </div>
            </div>
            <div class="col-md-12" style="padding-top:20px;">
                <!--Kilometro inicial-->
                <div class="col-md-6" style="width: 15%;">
                    <label style="font-size: 17px;">Km inicial:</label>
                    <input id="km_I_e<?php echo $j ?>" type="number" name="km_inicial_e" min="0" style="height: 32px;" disabled/>
                </div>

                <!--Kilometro final-->
                <div class="col-md-6" style="width: 15%;">
                    <label style="font-size: 17px;">Km final:</label>
                    <input id="km_F_e<?php echo $j ?>" type="number" name="km_final_e" min="0" style="height: 32px;" required/>
                </div>

                <!--Salida-->
                <div class="col-md-6" style="width: 18%;">
                    <?php $opciones = array('SECATI', 'Particular', 'Estacionamiento', 'Centro de día', 'CMERI', 'UMI', 'Almacen'); ?>
                    <label style="font-size: 17px;">Salida:</label><br>
                    <select id="salida_e<?php echo $j ?>" name="salida_e" required>
                        <?php
                            echo '<option value="">Seleccione una opción</option>';
                            foreach ($opciones as $opcion) {
                                echo '<option value="'.$opcion.'">'.$opcion.'</option>';
                            }
                        ?>
                    </select>
                </div>

                <div class="col-md-6" style="width: 20%;">
                    <div class="form-group form-animate-text" style="margin: 0px;">
                        <input type="text" oninput="this.value = this.value.toUpperCase()" onkeydown="if(event.keyCode === 13){nuevoRecorrido_e(this);}" maxlength="60" id="recorrido_e<?php echo $j ?>" class="form-text" name="recorrido_e" required>
                        <span class="bar"></span><label>Nuevo recorrido</label>
                    </div>
                </div>

                <!--Boton agregar-->
                <div class="col-md-6" style="width: 6%;">
                    <div id="btn_agregar_e<?php echo $j ?>" class="btn-guardar" onclick="nuevoRecorrido_e(this)" style="user-select: none; background: #172e5c; width: 75px; height: 35px; text-align: center; padding-top: 8px; cursor: pointer;">Agregar</div>
                </div>

                <!--Text area-->
                <div class="col-md-6" style="width: 20%;">
                    <textarea id="listaR_e<?php echo $j ?>" name="listaRecorridos_e" placeholder="Recorridos (maximo 60 caracteres)" style="width: 100%; height: 80px; resize: none; border-style: outset;" disabled></textarea>
                </div>
                <!--Boton vaciar-->
                <div class="col-md-6" style="width: 6%;">
                    <div id="btn_vaciar_e<?php echo $j ?>" class="btn-guardar" onclick="vaciar_e(this)" style="user-select: none; background: #172e5c; width: 75px; height: 35px; text-align: center; padding-top: 8px; cursor: pointer;">Vaciar</div>
                </div>
            </div>

            <!--Botones-->
            <div id="botones" style="text-align: center; width: 200px; margin: auto; padding-top: 50px">
                <div id="btn_ant_e<?php echo $j ?>" class="btn-guardar" onclick="anterior_dia_e(this)" style="user-select: none; background: #172e5c; width: 75px; height: 35px; text-align: center; padding-top: 8px; cursor: pointer; display: inline-block; visibility: hidden;">Anterior</div>
                <div id="btn_sig_e<?php echo $j ?>" class="btn-guardar" onclick="siguiente_dia_e(this)" style="user-select: none; background: #172e5c; width: 75px; height: 35px; text-align: center; padding-top: 8px; cursor: pointer; display: inline-block;">Siguiente</div>
                <div id="boton_guardar_e<?php echo $j ?>" style="display: none;">
                    <input id="btn_guardar_e<?php echo $j ?>" class="btn-guardar" type="button" onclick="Nbitacora_e(this)" value="Guardar">
                </div>
            </div>
        </form>
        <?php $j++; ?>
    </div>
    <?php } ?>
</body>
</html>