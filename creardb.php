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
            if (isset($_POST['Nomb_BD'])) {
                $basedatos = $_POST['Nomb_BD'];

                // Crear la conexión
                $conn = mysqli_connect("localhost", "root", "");

                // Verificar la conexión
                if (!$conn) {
                    die("Conexión fallida: " . mysqli_connect_error());
                }

                // Consulta para verificar si la base de datos ya existe
                $sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$basedatos'";

                // Ejecutar la consulta
                $result = mysqli_query($conn, $sql);

                // Validar si la base de datos ya existe
                if (mysqli_num_rows($result) > 0) {
                    echo "<img src='IMG/error.png' width='170px'><br>";
                    echo "La base de datos ya existe<br>";
                    echo "<a href='index.php'><button>Regresar</button></a>";
                } else {
                    // Consulta para agregar la nueva base de datos
                    $sql = "CREATE DATABASE $basedatos";

                    // Ejecutar la consulta
                    if (mysqli_query($conn, $sql)) {
                        echo "La base de datos se agregó correctamente.";
                        header('location:index.php');
                    } else {
                        echo "<img src='IMG/error.png' width='170px'><br>";
                        echo "Error al agregar la base de datos: " . mysqli_error($conn) . "<br>";
                        echo "<a href='index.php'><button>Regresar</button></a>";
                    }
                }
            }
            mysqli_close($conn);
            ?>


        </div>
    </center>
</body>

</html>