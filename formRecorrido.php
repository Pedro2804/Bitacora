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
    <div class="tab">
        <?php
            foreach (new DatePeriod($fechaInicio, new DateInterval('P1D'), $fechaFin) as $fecha) {
                $diaSemana = $fecha->format('N'); // 1 (lunes) a 7 (domingo)
                $nombreDia = $diasSemana[$diaSemana].' '.$fecha->format('j'); // Ejemplo: "lunes 12"

                // Generar la pestaña
                echo '<button class="tablinks" value="'.$nombreDia.'">'.$nombreDia.'</button>';
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
    <div id="<?php echo $nombreDia ?>" class="tabcontent" style="padding-top: 10px;">

        <!--Formulario-->
        <form id="formulario_recorrido<?php echo $j ?>" method="get" action="">
            
            <input type="hidden" value="guardar_recorrido" id="funcion" name="funcion">
            <!--<input type="hidden" name="bitacora" value="12" />-->
            <input type="hidden" name="dia_semana" value="<?php echo $nombreDia ?>" />

            <div class="col-md-12" style="padding-top:20px;">
                <div class="form-group form-animate-checkbox">
                    <input id="vacio<?php echo $j ?>" class="checkbox" onclick="formVacio(this)" type="checkbox" name="vacio" />
                    <label>VACIO</label>
                </div>
            </div>
            <div class="col-md-12" style="padding-top:20px;">
                <!--Kilometro inicial-->
                <div class="col-md-6" style="width: 15%;">
                    <label style="font-size: 17px;">Km inicial:</label>
                    <input id="km_I<?php echo $j ?>" type="number" name="km_inicial" min="0" style="height: 32px;" required />
                </div>

                <!--Kilometro final-->
                <div class="col-md-6" style="width: 15%;">
                    <label style="font-size: 17px;">Km final:</label>
                    <input id="km_F<?php echo $j ?>" type="number" name="km_final" min="0" style="height: 32px;" required />
                </div>

                <!--Salida-->
                <div class="col-md-6" style="width: 20%;">
                    <?php $opciones = array('Estacionamiento', 'Casitas', 'Opción 3'); ?>
                    <label style="font-size: 17px;">Salida:</label><br>
                    <select id="salida<?php echo $j ?>" name="salida" required>
                        <?php
                            echo '<option value="">Seleccione una opción</option>';
                            foreach ($opciones as $opcion) {
                                echo '<option value="'.$opcion.'">'.$opcion.'</option>';
                            }
                        ?>
                    </select>
                </div>
                
                <!--Recorrido-->
                <div class="col-md-6" style="width: 20%;">
                        <label style="font-size: 17px;">Recorrido:</label><br>
                        <select id="destino<?php echo $j ?>" name="destino" onchange="listarRecorrido(this)" required>
                            <?php
                                try {
                                    $pdo = Database::connect();
                                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $sql = "SELECT * FROM Destino";
                                    $q = $pdo->prepare($sql);
                                    $q->execute(array());
                                    $data = $q->fetchall(PDO::FETCH_ASSOC);
                                    echo '<option value="">Seleccione los destinos</option>';
                                    foreach($data as $row)
                                        echo '<option value="'.$row['lugar'].'">'.$row['lugar'].'</option>';
                                }catch(PDOException $e){
                                    echo 'Error: ' . $e->getMessage();
                                }
                            ?>
                        </select>
                </div>

                <div class="col-md-6" style="width: 22%;">
                    <div class="form-group form-animate-text" style="margin: 0px;">
                        <input type="text" id="recorrido<?php echo $j ?>" class="form-text" name="recorrido">
                        <span class="bar"></span><label>Nuevo recorrido</label>
                    </div>
                </div>

                <!--Boton agregar-->
                <div class="col-md-6" style="width: 8%;">
                    <div id="btn_agregar<?php echo $j ?>" class="btn-guardar" onclick="nuevoRecorrido(this)" style="user-select: none; background: #172e5c; width: 75px; height: 35px; text-align: center; padding-top: 8px; cursor: pointer;">Agregar</div>
                </div>
            </div>
            
            <!--Lista de recorridos-->
            <div class="col-md-12">
                <div class="col-md-6" style="width: 70%;"></div>
                <!--Text area-->
                <div class="col-md-6" style="width: 22%;">
                    <textarea id="listaR<?php echo $j ?>" name="listaRecorridos" placeholder="Recorridos" style="width: 100%; height: 80px; resize: none; border-style: outset;" disabled></textarea>
                </div>
                <!--Boton vaciar-->
                <div class="col-md-6" style="width: 8%;">
                    <div id="btn_vaciar<?php echo $j ?>" class="btn-guardar" onclick="vaciar(this)" style="user-select: none; background: #172e5c; width: 75px; height: 35px; text-align: center; padding-top: 8px; cursor: pointer;">Vaciar</div>
                </div>
            </div>

            <!--Botones-->
            <div id="botones" style="text-align: center; width: 200px; margin: auto; padding-top: 50px">
                <div id="btn_ant<?php echo $j ?>" class="btn-guardar" onclick="anterior_dia(this)" style="user-select: none; background: #172e5c; width: 75px; height: 35px; text-align: center; padding-top: 8px; cursor: pointer; display: inline-block; visibility: hidden;">Anterior</div>
                <div id="btn_sig<?php echo $j ?>" class="btn-guardar" onclick="siguiente_dia(this)" style="user-select: none; background: #172e5c; width: 75px; height: 35px; text-align: center; padding-top: 8px; cursor: pointer; display: inline-block;">Siguiente</div>
                <div id="boton_guardar<?php echo $j ?>" style="display: none;">
                    <input id="btn_guardar<?php echo $j ?>" class="btn-guardar" type="button" onclick="Nbitacora(this)" value="Guardar">
                </div>
            </div>
        </form>
        <?php $j++; ?>
    </div>
    <?php } ?>
</body>
<script src="scriprts/nueva_bitacora.js"></script>
</html>