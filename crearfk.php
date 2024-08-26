<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="estilodashboard.css?v=<?php echo time(); ?>">
    <script src="/js/functions.js?v=<?php echo time(); ?>"></script>
    <title>Document</title>
</head>
<style>
    button {
        background-color: #1D2231;
        border-radius: 10px;
        width: 100%;
        font-size: 20px;
        font-weight: none;
        color: white;
        padding: 12px;
        box-shadow: 0 12px 16px 0 rgba(0, 0, 0, 0.24), 0 8px 10px 0 rgba(0, 0, 0, 0.19);
    }

    div {
        border-radius: 15px;
        font-size: 25px;
        padding: 15px;
        background-color: rgba(255, 0, 0, 0.397);
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>

<body>
    <center>
        
            <?php
            $db = $_GET['db'];
            $tabla = $_GET['tabla'];
            $campo = $_GET['campo'];
            $tabla_ref = $_GET['tabla_ref'];
            $campo_ref = $_POST['campo_ref'];
            $con = mysqli_connect('localhost', 'root', '', $db);
            $sql = "ALTER TABLE " . $tabla . " ADD CONSTRAINT fk_" . $tabla . "_" . $tabla_ref . " FOREIGN KEY (" . $campo . ") REFERENCES " . $tabla_ref . "(" . $campo_ref . ")";

            if (mysqli_query($con, $sql)) {
                echo "Referencia completada correctamente";
                header("location:datosdb.php?db=$db&tabla=$tabla");
            } else {
                echo "<div class='my-element'>";
                echo "<img src='IMG/error.png' width='170px'><br>";
                echo "Error al agregar la referencia: " . mysqli_error($con) . "<br>";
                echo "<a href='datosdb.php?db=$db&tabla=$tabla'><button>Regresar</button></a>";
                echo "</div>";
            }

            mysqli_close($con);
            ?>
        
    </center>
</body>

</html>