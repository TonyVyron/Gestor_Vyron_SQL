<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilodashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
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
        if (isset($_GET['db'])) {
            $db = $_GET['db'];
            $tabla = $_GET['tabla'];
            $num_campos = $_GET['num_campos'];
            $dat = array(
                'TINYINT',
                'SMALLINT',
                'MEDIUMINT',
                'INT',
                'INTEGER',
                'BIGINT',
                'FLOAT',
                'DOUBLE',
                'DECIMAL',
                'DATE',
                'DATETIME',
                'TIMESTAMP',
                'TIME',
                'YEAR',
                'BOOLEAN',
                'BOOL',
                'LONGTEXT'
            );
            $sql = "";
            for ($i = 1; $i <= $num_campos; $i++) {
                $nombre = $_POST["nombreCampo$i"];
                $tipo = $_POST["tipoCampo$i"];
                $longitud = $_POST["longitudCampo$i"];

                if (isset($_POST["AICampo$i"])) {
                    // El checkbox ha sido seleccionado
                    $AI = "not null AUTO_INCREMENT PRIMARY KEY";
                } else {
                    if (isset($_POST["PKCampo$i"])) {
                        if (isset($_POST["nuloCampo$i"])) {
                            $AI = "PRIMARY KEY";
                        } else {
                            $AI = "not null PRIMARY KEY";
                        }
                    } else {
                        $AI = "null";
                    }
                }

                $sql .= " ADD $nombre ";

                if (in_array(strtolower($tipo), array_map('strtolower', $dat))) {
                    $sql .= "$tipo  ";
                } else {
                    $sql .= "$tipo($longitud)    ";
                }

                $sql .= "$AI,";
            }

            $query = "ALTER TABLE $tabla " . $sql;
            $query = rtrim($query, ",");

            $enlace = mysqli_connect('localhost', 'root', '', $db);

            if ($enlace->connect_error) {
                die("Conexión Fallida: " . $enlace->connect_error);
            }

            if ($enlace->query($query) === true) {
                echo "<div class='listo my-element'>";
                echo "Campos Agregados Correctamente:<br>";
                echo "<img src='IMG/listo.png' width='170px'><br>";
                echo $query . "<br>";
                echo "<a href='datosdb.php?db=$db&tabla=$tabla'><button>Regresar</button></a>";
                echo "</div>";
            } else {
                echo "<div class='error my-element'>";
                echo "<img src='IMG/error.png' width='170px'><br>";
                echo "Error al agregar Campos: " . mysqli_error($enlace) . "<br>";
                echo "<a href='datosdb.php?db=$db&tabla=$tabla'><button>Regresar</button></a>";
                echo "</div>";
            }
            mysqli_close($enlace);
        }
        ?>
    </center>
</body>

</html>