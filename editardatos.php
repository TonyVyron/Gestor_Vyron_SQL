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
        margin-top: 10px;
        background-color: #1D2231;
        border-radius: 10px;
        width: 100%;
        font-size: 20px;
        font-weight: none;
        color: white;
        padding: 12px;
        box-shadow: 0 12px 16px 0 rgba(0, 0, 0, 0.24), 0 8px 10px 0 rgba(0, 0, 0, 0.19);
    }

    .listo {
        border-radius: 15px;
        font-size: 25px;
        padding: 15px;
        background-color: yellowgreen;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .error {
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
        $id = $_GET['id'];
        $cont = $_GET['cont'];

        $enlace = mysqli_connect('localhost', 'root', '', $db);

        if ($enlace->connect_error) {
            die("La conexión falló: " . $enlace->connect_error);
        }

        $enlace2 = mysqli_connect('localhost', 'root', '', $db);
        $sql2 = "Select * from $tabla where $id = '$cont'";
        $result2 = mysqli_query($enlace2, $sql2);

        $query_actualizar = "";
        while ($row2 = mysqli_fetch_assoc($result2)) {
            $enlace3 = mysqli_connect('localhost', 'root', '', $db);
            $sql3 = "SHOW FIELDS FROM $tabla";
            $result3 = mysqli_query($enlace3, $sql3);

            while ($row3 = mysqli_fetch_assoc($result3)) {
                $column_name = $row3['Field'];
                $valor = $row2[$column_name];
                $valornuevo = $_POST[$column_name];
                if ($valor == $valornuevo) {
                } else {
                    $query_actualizar .= "`$column_name` = '$valornuevo', ";
                }
            }
        }

        $query = "UPDATE `$tabla` SET " . substr($query_actualizar, 0, -2) . " WHERE $id = '$cont'";

        if (mysqli_query($enlace, $query)) {
            echo "<div class='listo'>";
            echo "Datos Actualizados correctamente<br>";
            echo "<img src='IMG/listo.png' width='170px'><br>";
            echo $query . "<br>";
            echo "<a href='tabladb.php?db=$db&tabla=$tabla'><button>Regresar</button></a>";
            echo "</div>";
        } else {
            if ($query_actualizar == "") {
                echo "<div class='error'>";
                echo "No se Enviaron Datos para Actualizar:<br>";
                echo "<img src='IMG/warning.png' width='170px'><br>";
                echo "<a href='tabladb.php?db=$db&tabla=$tabla'><button>Regresar</button></a>";
                echo "</div>";
            } else {
                echo "<div class='error'>";
                echo "<img src='IMG/error.png' width='170px'><br>";
                echo "Error al Actualizar los Datos: " . mysqli_error($enlace) . "<br>";
                echo "<a href='tabladb.php?db=$db&tabla=$tabla'><button>Regresar</button></a>";
                echo "</div>";
            }
        }
        mysqli_close($enlace);
        ?>

    </center>
</body>

</html>