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
                $nom_tabla = $_GET['nom_tabla'];
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
                $utf8 = 0;
                $sql = "Create table $nom_tabla(";
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
                    $sql .= "$nombre  ";
                    if (in_array(strtolower($tipo), array_map('strtolower', $dat))) {
                        $utf8++;
                        $sql .= "$tipo  ";
                    } else {
                        $sql .= "$tipo($longitud)    ";
                    }
                    $sql .= "$AI,";
                }
                $sql = rtrim($sql, ",");
                if ($utf8 == $num_campos) {
                    $sql .= ") ";
                } else {
                    $sql .= ") DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
                }

                $enlace = mysqli_connect('localhost', 'root', '', $db);

                if ($enlace->connect_error) {
                    echo "<img src='IMG/error.png' width='170px'><br>";
                    die("Conexión Fallida: " . $enlace->connect_error);
                }

                if ($enlace->query($sql) === true) {
                    echo "Dato Agregados Correctamente";
                    header("location:verdb.php?db=$db");
                } else {
                    echo "<img src='IMG/error.png' width='170px'><br>";
                    echo "Error al crear la Tabla: " . mysqli_error($enlace) . "<br>";
                    echo "<a href='verdb.php?db=$db'><button>Regresar</button></a>";
                }
                mysqli_close($enlace);
            }
            ?>
        </div>
    </center>
</body>

</html>