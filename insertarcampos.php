<?php
$db = $_GET['db'];
$tabla = $_GET['tabla'];
$num_campos = $_POST['num_campos'];

$PKS = 0;
$sql2 = "SELECT COUNT(*) AS PK_EXISTS FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = '$db' AND TABLE_NAME = '$tabla' AND CONSTRAINT_TYPE = 'PRIMARY KEY'";
$enlace2 = mysqli_connect('localhost', 'root', '');
$result2 = mysqli_query($enlace2, $sql2);
while ($row2 = mysqli_fetch_assoc($result2)) {
    $PKS =  $row2['PK_EXISTS'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="estilodashboard.css?v=<?php echo time(); ?>">
    <script src="/js/functions.js?v=<?php echo time(); ?>"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="datatables-simple-demo.js"></script>
    <title>VyrSQL</title>
</head>
<style>
    table {
        border-radius: 15px;
        border: 1px black solid;
        width: 100%;
        border-collapse: collapse;
    }

    tbody tr:nth-child(odd) {
        background: #eee;
    }

    td,
    th {
        border: 1px black solid;
        padding: 5px;
        text-align: center;
        font-size: 16px;
    }

    th {
        color: white;

        background-color: #1D2231;
    }

    h2 {
        font-size: 23px;
    }

    b a {
        font-size: 16px;
        color: #551A8B;
        padding-bottom: 0px;
    }

    a {
        text-decoration: none;
    }

    small {
        font-size: 14px;
        padding-top: 0px;
    }
</style>

<body background="IMG/fondo2.jpg">

    <input type="checkbox" id="nav-toggle">

    <!--BARRA LATERAL-->
    <div class="sidebar"><br>
        <a href="index.php">
            <h1 id="titulo">VyrSQL</h1>
            <img id="bd" src="IMG/bd_logo.png" width="60">
        </a>

        <br>
        <br>

        <div id="muestra">
      <p onclick="agregar()" id="b"><img id="a" src='IMG/plusdb.png' width='25'>Nueva</p>
      <?php
      $enlace = mysqli_connect('localhost', 'root', '');
      $sql = "SHOW DATABASES";
      $result = mysqli_query($enlace, $sql);
      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<nav class='nav'>
            <ul class='list'>
                <li class='list__item list__item--click'>
                    <div class='list__button list__button--click'>
                        <img src='IMG/base-de-datos.png' class='list__img' width='20px'>
                        <a href='#' class='nav__link'>
                            <p><a id='subtito' href='verdb.php?db=$row[Database]'>$row[Database]</a></p>
                        </a>
                        <img src='IMG/down.png' class='list__arrow' width='20px'>
                    </div>
                    <ul class='list__show'>";
          // Agrega un bucle interno para obtener las tablas de cada base de datos
          $db_name = $row['Database'];
          $tables_sql = "SHOW TABLES FROM $db_name";
          $aver = "Tables_in_$db_name";
          $tables_result = mysqli_query($enlace, $tables_sql);
          if (mysqli_num_rows($tables_result) > 0) {
            while ($table_row = mysqli_fetch_assoc($tables_result)) {
              echo "<li class='list__inside'>
                                <a href='tabladb.php?db=$db_name&tabla=$table_row[$aver]' class='nav__link nav__link--inside'>$table_row[$aver]</a>
                            </li>";
            }
          } else {
            echo "<li class='list__inside'>No se encontraron tablas en $db_name</li>";
          }
          echo "</ul>
                </li>
            </ul>
        </nav>";
        }
      } else {
        echo "<p> No se encontraron bases de datos. </p>";
      }
      ?>

    </div>


    </div>
    <!--BARRA LATERAL-->


    <div class="main-content">
        <!--NAV-->
        <header>
            <h2>
                <label for="nav-toggle">
                    <span class="las la-bars" id="barras"></span>
                </label> <b id="subti"><?php echo $db . " / " . $tabla ?></b>
            </h2>


        </header>

        <main>
            <div>
                <a href="datosdb.php?db=<?php echo $db ?>&tabla=<?php echo $tabla ?>"><button type="submit"><img id="cen" src="IMG/estructura.png" width="20">Estructura</button></a>
                <a href="tabladb.php?db=<?php echo $db ?>&tabla=<?php echo $tabla ?>"><button type="submit"><img id="cen" src="IMG/examinar.png" width="20">Datos</button></a>
                <a href="insertardatos.php?db=<?php echo $db ?>&tabla=<?php echo $tabla ?>"><button type="submit"><img id="cen" src="IMG/insertar.png" width="20">Insertar Datos</button></a>
                <a href="consulta.php?db=<?php echo $db ?>&tabla=<?php echo $tabla ?>"><button type="submit"><img id="cen" src="IMG/sql.png" width="20">Consulta SQL</button></a>
            </div>
            <div id='agregartab'>
                <h2 id='titag'>Agregar Campos en la tabla <?php echo $tabla ?></h2>
                <form method="post" action="crearcampos.php?db=<?php echo $db ?>&tabla=<?php echo $tabla ?>&num_campos=<?php echo $num_campos ?>">
                    <?php
                    // Obtener los tipos de dato disponibles en MySQL
                    $mysqli = new mysqli('localhost', 'root', '', $db);
                    $result = $mysqli->query("SELECT DISTINCT DATA_TYPE FROM information_schema.columns");
                    $tiposDato = array();
                    while ($row = $result->fetch_assoc()) {
                        $tipo = explode('(', $row['DATA_TYPE']);
                        $tiposDato[] = $tipo[0];
                    }
                    $tiposDato = array_unique($tiposDato);

                    // Generar campos para ingresar los datos de cada campo de la tabla
                    for ($i = 1; $i <= $num_campos; $i++) {
                        echo '<label id="line" for="nombreCampo' . $i . '">Nombre del campo:</label>';
                        echo '<label id="line" for="tipoCampo' . $i . '">tipo de campo:</label>';
                        echo '<label id="line" for="longitudCampo' . $i . '">Longitud de campo:</label>';
                        if ($PKS == 0) {
                            echo '<label id="line" for="nuloCampo' . $i . '">Nulo:</label>';
                            echo '<label id="line" for="AICampo' . $i . '">AutoIncrementable:</label>';
                            echo '<label id="line" for="PKCampo' . $i . '"><img id="icon" src="IMG/llave.png" width="20">Primary:</label><br>';
                        } else {
                            echo '<label id="line" for="nuloCampo' . $i . '">Nulo:</label><br>';
                        }
                        echo '<input class="inptb" type="text" name="nombreCampo' . $i . '" id="nombreCampo' . $i . '" required placeholder="tabla" >';
                        echo '<div class="caja"><select name="tipoCampo' . $i . '" id="tipoCampo' . $i . '" required>';
                        foreach ($tiposDato as $tipoDato) {
                            if ($tipoDato == 'int') {
                                echo '<option value="' . $tipoDato . '" selected>' . $tipoDato . '</option>';
                            } else {
                                echo '<option value="' . $tipoDato . '">' . $tipoDato . '</option>';
                            }
                        }
                        echo '</select></div>';
                        echo '<input class="inptb" type="number" min="1" name="longitudCampo' . $i . '" id="longitudCampo' . $i . '" required placeholder="1" >';
                        if ($PKS == 0) {
                            echo '<input class="inptb" type="checkbox" name="nuloCampo' . $i . '" id="nuloCampo' . $i . '" checked >';
                            echo '<input class="inptb"  type="checkbox" name="AICampo' . $i . '" id="AICampo' . $i . '"  >';
                            echo '<input class="inptb"  type="checkbox" name="PKCampo' . $i . '" id="PKCampo' . $i . '" ><br>';
                        } else {
                            echo '<input class="inptb" type="checkbox" name="nuloCampo' . $i . '" id="nuloCampo' . $i . '" checked ><br>';
                        }
                    }
                    ?><br>
                    <button onclick="return validacion()"> Agregar</button>

                </form>
            </div>
        </main>

    </div>
    <script>
        function validacion() {
            var numero = <?php echo $num_campos ?>;
            var llavepk = <?php echo $PKS ?>;
            for (var i = 1; i <= numero; i++) {
                if (llavepk == 0) {
                    var nullCheckbox = document.getElementById('nuloCampo' + i);
                    var aiCheckbox = document.getElementById('AICampo' + i);
                    var pkCheckbox = document.getElementById('PKCampo' + i);
                    var tipoCampoSelect = document.getElementById('tipoCampo' + i);
                    var tiposInvalidos = ['longtext', 'blob', 'text', 'date', 'time', 'datetime', 'timestamp', 'boolean', 'bool', 'tinyint'];

                    // Validar que La clave primaria no puede ser un tipo de dato invalido o muy grande
                    if (pkCheckbox.checked) {
                        if (tiposInvalidos.includes(tipoCampoSelect.value)) {
                            Swal.fire(
                                'Detalle',
                                'Primary <img id="icon" src="IMG/llave.png" width="20">  no puede ser un tipo de dato invalido o muy grande',
                                'warning'
                            );
                            return false;
                        }
                    }
                    // Validar que no se pueda seleccionar "Null" junto a "AI" o "PK"
                    if (nullCheckbox.checked && (aiCheckbox.checked || pkCheckbox.checked)) {
                        Swal.fire(
                            'Detalle',
                            'No puede seleccionar "Null" junto a "AutoIncrementable" o "Primary <img id="icon" src="IMG/llave.png" width="20">".',
                            'warning'
                        )
                        return false;
                    }
                    // Validar que si "AI" o "PK" están seleccionados, el "Tipo Campo" sea "int"
                    if ((aiCheckbox.checked || pkCheckbox.checked) && tipoCampoSelect.value !== 'int') {
                        // Validar que si "PK" están seleccionados no es necesario el "AI"
                        if (pkCheckbox.checked && aiCheckbox.checked == false) {} else {
                            Swal.fire(
                                'Detalle',
                                'Si selecciona "AutoIncrementable" o "Primary <img id="icon" src="IMG/llave.png" width="20">", el tipo de campo debe ser "int".',
                                'warning'
                            )
                            return false;
                        }
                    }
                } else {

                }

            }
            return true;
        }
    </script>
    <script>
     function agregar() {
      Swal.fire({
        title: "Agregar Bases de datos",
        icon: 'question',
        html: "<form action='creardb.php' method='POST' required> <label for='Crear'><strong>Nombre de la Base de datos</strong> </label><input id='Crear' type='text' placeholder='Base de Datos' name='Nomb_BD'  required><br><br><button id='myform'  type='submit'>Agregar</button><br></form>",
        confirmButtonText: "Cancelar",
        confirmButtonColor: "red",
      })
    }

  </script>
    <script src="main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>