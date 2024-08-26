<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <div>
            <?php
            if (isset($_GET['tabla'])) {
                $db = $_GET['db'];
                $tabla = $_GET['tabla'];

                $enlace = mysqli_connect('localhost', 'root', '', $db);

                $sql = "DROP TABLE $tabla";

                if ($enlace->connect_error) {
                    die("Conexión Fallida: " . $enlace->connect_error);
                }

                if ($enlace->query($sql) === true) {
                    echo "Dato eliminado Correctamente";
                    header("location:verdb.php?db=$db");
                } else {
                    echo "<img src='IMG/error.png' width='170px'><br>";
                    echo "Error al Eliminar la llave: " . mysqli_error($enlace) . "<br>";
                    echo "<a href='verdb.php?db=$db'><button>Regresar</button></a>";
                }
                mysqli_close($enlace);
            }
            ?>
        </div>
    </center>
</body>

</html>