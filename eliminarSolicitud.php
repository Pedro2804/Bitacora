<?php

?>

<!DOCTYPE html>
<html>
     
<head>
    
    <!-- start: Css -->
    <link rel="stylesheet" type="text/css" href="asset/css/bootstrap.min.css">
    <!-- plugins -->
    <link rel="stylesheet" type="text/css" href="asset/css/plugins/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="asset/css/plugins/animate.min.css"/>
    <link rel="stylesheet" type="text/css" href="asset/css/plugins/nouislider.min.css"/>
    <link rel="stylesheet" type="text/css" href="asset/css/plugins/select2.min.css"/>
    <link rel="stylesheet" type="text/css" href="asset/css/plugins/ionrangeslider/ion.rangeSlider.css"/>
    <link rel="stylesheet" type="text/css" href="asset/css/plugins/ionrangeslider/ion.rangeSlider.skinFlat.css"/>
    <link rel="stylesheet" type="text/css" href="asset/css/plugins/bootstrap-material-datetimepicker.css"/>
    <link href="asset/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="css/flexselect.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/estilos.css" />
    <!-- end: Css -->
    <!-- start: sweetalert2 -->
    <script src="scriprts/sweetalert2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style/sweetalert2.min.css">


    <link rel="shortcut icon" href="img/logodifblanco.png">
    <title>
        Eliminar
    </title>
</head>
 
<body style="text-align:center;">
     
    
    <h4 class="Titulos">¿ELIMINAR?</h4>
     
    <h4>
        ¿Eliminar la solicitud?
    </h4>

    <form method="post">
        <input class="btn-guardar" type="submit" name="button1"
                class="button" value="Eliminar" />
    </form>
    <br>
        <form action="busqueda.php">
        <input class="btn-guardar" type="submit" value="Cancelar" />
    </form>
</body>
 
</html>
<?php

        
        if(array_key_exists('button1', $_POST)) {
            eliminar();
        }
        
        function eliminar(){
            include 'config/conexion.php';
            $id = $_GET['id'];

            $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM not_solicitud WHERE ClaveEntidad=$id";
            $q = $pdo->prepare($sql);
            try {
                $q->execute(array($sql));
                Database::disconnect();
                echo "<br><br>ELIMINADO<br><br>";
                echo('<a href="busqueda.php" style="font-size: 20px; text-decoration: none">Regresar</a>');
                return true;
            } catch (PDOException $e) {
                Database::disconnect();
                return "Error: " . $e;
            }
            
            
        }
        
    ?>
    