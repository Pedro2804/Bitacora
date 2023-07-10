<!DOCTYPE html>
<html>
<head>
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
        <form id="formulario_recorrido">
            <input type="hidden" name="bitacora" value="12" />
            <input type="hidden" name="dia_semana" value="<?php echo $diaSemana ?>" />

            <div class="col-md-12" style="padding-top:20px;">
                <!--Kilometro inicial-->
                <div class="col-md-6" style="width: 15%;">
                    <label style="font-size: 17px;">Km inicial:</label>
                    <input type="number" name="km_inicial" min="0" style="height: 32px;" required />
                </div>

                <!--Kilometro final-->
                <div class="col-md-6" style="width: 15%;">
                    <label style="font-size: 17px;">Km final:</label>
                    <input type="number" name="km_final" min="0" style="height: 32px;" required />
                </div>

                <!--Salida-->
                <div class="col-md-6" style="width: 20%;">
                    <?php $opciones = array('Opción 1', 'Opción 2 casitas', 'Opción 3'); ?>
                    <label style="font-size: 17px;">Salida:</label><br>
                    <select name="salida">
                        <?php
                            foreach ($opciones as $opcion) {
                                echo '<option value="'.$opcion.'">'.$opcion.'</option>';
                            }
                        ?>
                    </select>
                </div>
                
                <!--Recorrido-->
                <div class="col-md-6" style="width: 20%;">
                        <label style="font-size: 17px;">Recorrido:</label><br>
                        <select name="destino">
                            <?php
                                try {
                                    $pdo = Database::connect();
                                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $sql = "SELECT CONCAT(Nombre,' ',ApellidoPaterno,' ',ApellidoMaterno) as nombre_empleado  FROM empleado";
                                    $q = $pdo->prepare($sql);
                                    $q->execute(array());
                                    $data = $q->fetchall(PDO::FETCH_ASSOC);
                                    $i = 0;
                                    foreach($data as $row){
                                        echo '<option value="opcion'.$i.'">'.$row['nombre_empleado'].'</option>';
                                        $i++;
                                    }
                                }catch(PDOException $e){
                                    echo 'Error: ' . $e->getMessage();
                                }
                            ?>
                        </select>
                </div>

                <div class="col-md-6" style="width: 22%;">
                    <div class="form-group form-animate-text" style="margin: 0px;">
                        <input type="text" id="recorrido<?php echo $j ?>" oninput="listarRecorrido(this)" class="form-text" name="recorrido" required>
                        <span class="bar"></span><label>Nuevo recorrido</label>
                    </div>
                </div>

                <!--Boton agregar-->
                <div class="col-md-6" style="width: 8%;">
                    <div id="btn_agregar<?php echo $j ?>" class="btn-guardar" onclick="nuevo_recorrido()" style="background: #172e5c; width: 75px; height: 35px; text-align: center; padding-top: 8px; cursor: pointer;">Agregar</div>
                </div>
            </div>
            
            <!--Lista de recorridos-->
            <div class="col-md-12">
                <div class="col-md-6" style="width: 57%;"></div>
                <!--Text area-->
                <div class="col-md-6" style="width: 35%;">
                    <textarea id="listaR<?php echo $j ?>" name="listaRecorridos" placeholder="Recorridos" style="width: 100%; resize: none; border-style: outset;" readonly></textarea>
                </div>
                <!--Boton vaciar-->
                <div class="col-md-6" style="width: 8%;">
                    <div id="btn_vaciar<?php echo $j ?>" class="btn-guardar" onclick="vaciar()" style="background: #172e5c; width: 75px; height: 35px; text-align: center; padding-top: 8px; cursor: pointer;">Vaciar</div>
                </div>
            </div>

            <!--Botones-->
            <div id="botones" style="text-align: center; width: 200px; margin: auto; padding-top: 50px">
                <div id="btn_ant<?php echo $j ?>" class="btn-guardar" onclick="anterior_dia(this)" style="background: #172e5c; width: 75px; height: 35px; text-align: center; padding-top: 8px; cursor: pointer; display: inline-block; visibility: hidden;">Anterior</div>
                <div id="btn_sig<?php echo $j ?>" class="btn-guardar" onclick="siguiente_dia(this)" style="background: #172e5c; width: 75px; height: 35px; text-align: center; padding-top: 8px; cursor: pointer; display: inline-block;">Siguiente</div>
                <div id="boton_guardar<?php echo $j ?>" style="display: none;">
                    <input class="btn-guardar" type="submit" value="Guardar">
                </div>
            </div>
        </form>
        <?php $j++; ?>
    </div>
    <?php } ?>

    <script>

            function anterior_dia2(seleccionado) {
                //document.getElementById("listaR").value += seleccionado.value + '\n';
                //seleccionado.value = "";
                alert(seleccionado.id);
            }
            /*$(document).ready(function() {
                $("#recorrido<?php echo $j ?>").on("input", function(event){
                    alert("hola");
                });
            });*/
        </script>
</body>
</html>