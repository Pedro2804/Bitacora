<?php

$id = $_GET['id'];
$unidad = $_GET['unidad'];
$bitacoras = array();

//Solo en caso de que se tenga que eliminar la nueva Bitácora por algun error, verificamos si es la nueva para actualizar el km de al
//unidad a cuando estaba antes de crear la bitacora, si es algo que ya se había creado antes, simplemente se elimina.
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT * FROM bitacora WHERE NoUnidad = ? ORDER BY id_bitacora ASC;";
$q = $pdo->prepare($sql);

    try{
        $q->execute(array($unidad));
        $data = $q->fetchAll(PDO::FETCH_ASSOC);
            foreach($data as $Solicitud):
                $bitacoras[]=$Solicitud['id_bitacora'];
            endforeach;
    }catch (PDOException $e){
        echo 'Error: ' . $e->getMessage();
    }

    if(busqueda_binaria($bitacoras, $id) == count($bitacoras)){
        $km_total = 0;
        try {
            $pdo = Database::connect();//Para obtener el kilometraje anterior
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM recorrido WHERE bitacora = ?;";
            $q = $pdo->prepare($sql);
            $q->execute(array($id));
            $filas = $q->rowCount();
            $data = $q->fetchall(PDO::FETCH_ASSOC);
            $datos = array();

            foreach($data as $Solicitud):
                $km_inicial=$Solicitud['km_inicial'];
                $km_final=$Solicitud['km_final'] ;
    
                if($Solicitud["vacio"] == 0) //verificamos que el recorrido no esté vacío
                    $km_total += $km_final - $km_inicial;
            endforeach;
            Database::disconnect();
        }catch(PDOException $e){ echo 'Error: ' . $e->getMessage();}

        try{
            $pdo = Database::connect(); //Para obtener el kilometraje actual del vehiculo
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT kilometraje FROM vehiculo WHERE num_unidad= ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($unidad));
            $data = $q->fetch(PDO::FETCH_ASSOC);
            $km_actual = $data['kilometraje'];
            Database::disconnect();
        }catch(PDOException $e){ echo 'Error: ' . $e->getMessage();}
    
        $kilometraje_anterior = $km_actual - $km_total;

        $pdo = Database::connect(); //Para actualizar el kilometraje
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE vehiculo SET kilometraje = ? WHERE num_unidad = $unidad;";
        $params = array($kilometraje_anterior);
        try{
            $q = $pdo->prepare($sql);
            $q->execute($params);
            Database::disconnect();
        }catch (PDOException $e){
            echo 'Error: ' . $e->getMessage();
        }
    }
function busqueda_binaria($lista, $objetivo){
    $izquierda = 0;
    $derecha = count($lista) - 1;
    
    while ($izquierda <= $derecha){
        $medio = ($izquierda + $derecha); // 2
        $valor_medio = $lista[$medio];
        
        if ($valor_medio == $objetivo)
            return $medio + 1;
        else if ($valor_medio < $objetivo)
            $izquierda = $medio + 1;
        else
            $derecha = $medio - 1;
    }
    return -1;  # El valor no se encontró
}

?>