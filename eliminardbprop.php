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
            if (isset($_GET['db'])) {
                $db = $_GET['db'];

                $conn = mysqli_connect("localhost", "root", "");

                if ($conn->connect_error) {
                    die("Conexión Fallida: " . $conn->connect_error);
                }
                $bases = array(
                    "information_schema",
                    "mysql",
                    "performance_schema",
                    "phpmyadmin",
                );

                if (in_array(strtolower($db), array_map('strtolower', $bases))) {
                    echo "<img src='IMG/error.png' width='170px'><br>";
                    echo "La Base de datos " . $db . " no Puede ser Eliminada<br> ";
                    echo "<a href='index.php'><button>Regresar</button></a>";
                } else {
                    $sql = "DROP DATABASE $db";
                    if (mysqli_query($conn, $sql)) {
                        echo "La base de datos se elimino correctamente.";
                        header('location:index.php');
                    } else {
                        echo "<img src='IMG/error.png' width='170px'><br>";
                        echo "Error al Eliminar Base de datos: " . mysqli_error($conn) . "<br>";
                        echo "<a href='index.php'><button>Regresar</button></a>";
                    }
                }
                mysqli_close($conn);
            }
            ?>
        </div>
    </center>
</body>

</html>