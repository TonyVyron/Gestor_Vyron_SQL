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

    // Crear conexión
    $conn = new mysqli('localhost', 'root', '', $db);

    // Verificar conexión
    if ($conn->connect_error) {
      die("La conexión falló: " . $conn->connect_error);
    }

    // Construir consulta SQL dinámicamente
    $columnas = implode(", ", array_keys($_POST));
    $valores = implode("', '", array_values($_POST));
    $sql = "INSERT INTO $tabla (" . $columnas . ") VALUES ('" . $valores . "')";

    // Ejecutar consulta
    if ($conn->query($sql) === TRUE) {
      echo "<div class='listo my-element'>";
      echo "Datos Ingresados correctamente <br>";
      echo "<img src='IMG/listo.png' width='170px'><br>";
      echo $sql . "<br>";
      echo "<a href='tabladb.php?db=$db&tabla=$tabla'><button>Regresar</button></a>";
      echo "</div>";
    } else {
      echo "<divclass='error my-element'>";
      echo "<img src='IMG/error.png' width='170px'><br>";
      echo "Error al insertar Datos: " . mysqli_error($conn) . "<br>";
      echo "<a href='tabladb.php?db=$db&tabla=$tabla'><button>Regresar</button></a>";
      echo "</div>";
    }

    $conn->close();
    ?>
  </center>
</body>

</html>