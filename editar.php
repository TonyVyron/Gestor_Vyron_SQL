<?php
$db = $_GET['db'];
$tabla = $_GET['tabla'];
$id = $_GET['id'];
$cont = $_GET['cont'];

$PKS = 0;
$sql2 = "SELECT c.COLUMN_KEY FROM INFORMATION_SCHEMA.COLUMNS c LEFT JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE k ON c.TABLE_SCHEMA=k.TABLE_SCHEMA AND c.TABLE_NAME=k.TABLE_NAME AND c.COLUMN_NAME=k.COLUMN_NAME WHERE c.TABLE_SCHEMA = '$db' AND c.TABLE_NAME = '$tabla'";
$enlace2 = mysqli_connect('localhost', 'root', '');
$result2 = mysqli_query($enlace2, $sql2);
while ($row2 = mysqli_fetch_assoc($result2)) {
    $key = $row2['COLUMN_KEY'];
    if ($key == 'PRI') {
        $PKS++;
    }
}
mysqli_close($enlace2);
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
        border-radius: 10px;
        width: 100%;
        border-spacing: 2px;
    }

    tbody tr:nth-child(odd) {
        background: rgba(0, 0, 0, 0.056);
    }

    .rodear {
        border: 1px rgba(0, 0, 0, 0.356) solid;
        padding: 23px 28px 28px 28px;
        border-radius: 30px;
        box-shadow: 0 .85rem .85rem 5px rgba(0, 0, 0, 0.2);
    }

    td,
    th {
        padding: 5px;
        text-align: center;
        font-size: 16px;
        border-bottom: 1px rgba(0, 0, 0, 0.333) solid;
    }

    td:hover {
        background-color: #eee;
    }

    th {
        color: white;
        border-radius: 15px;
        background-color:#121f42;
        border-top: none;
        border-bottom: none;
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
                <a href="consulta.php?db=<?php echo $db ?>&tabla=<?php echo $tabla ?>"><button type="submit"><img id="cen" src="IMG/sql.png" width="20">Consulta SQL</button></a>
            </div>
            <div id='agregartab'>
                <h2 id='titag'>Insertar Datos en la tabla <?php echo $tabla ?></h2>
                <?php
                $enlace3 = mysqli_connect('localhost', 'root', '', $db);
                $sql3 = "Select * from $tabla where $id = '$cont'";
                $result3 = mysqli_query($enlace3, $sql3);
                while ($row3 = mysqli_fetch_assoc($result3)) {
                    // Establecer conexión a la base de datos
                    $enlace = mysqli_connect('localhost', 'root', '', $db);
                    $sql = "SELECT c.COLUMN_NAME, c.COLUMN_TYPE, c.DATA_TYPE, c.CHARACTER_MAXIMUM_LENGTH, c.COLUMN_KEY, k.REFERENCED_TABLE_NAME, k.REFERENCED_COLUMN_NAME, IF(c.EXTRA = 'auto_increment', 'SI', 'NO') AS AUTOINCREMENTABLE FROM INFORMATION_SCHEMA.COLUMNS c LEFT JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE k ON c.TABLE_SCHEMA=k.TABLE_SCHEMA AND c.TABLE_NAME=k.TABLE_NAME AND c.COLUMN_NAME=k.COLUMN_NAME WHERE c.TABLE_SCHEMA = '$db' AND c.TABLE_NAME = '$tabla'";
                    $result = mysqli_query($enlace, $sql);
                    echo "<form method='post' action='editardatos.php?db=$db&tabla=$tabla&id=$id&cont=$cont'>";
                    echo ' <p class="linea2">Campo</p><p class="linea2">Tipo de Dato</p><p class="linea2">Valor</p>';

                    while ($row = mysqli_fetch_assoc($result)) {
                        $column_name = $row['COLUMN_NAME'];
                        $data_type = $row['DATA_TYPE'];
                        $column_type = $row['COLUMN_TYPE'];
                        $max_length = $row['CHARACTER_MAXIMUM_LENGTH'];
                        $key = $row['COLUMN_KEY'];
                        $referencetab = $row['REFERENCED_TABLE_NAME'];
                        $referencecol = $row['REFERENCED_COLUMN_NAME'];
                        $AI = $row['AUTOINCREMENTABLE'];

                        if ($key  == "PRI" && $referencetab == null && $PKS == 1) {
                            if ($data_type == 'varchar' || $data_type == 'char' || $data_type == 'text' || $data_type == 'longblob') {
                                echo "<label class='linea' ><img id='cen' src='IMG/llave.png' width='20'>$column_name:</label>";
                                echo "<label class='linea' >$column_type:</label>";
                                echo "<input id='$column_name' class='linea' type='text' name='$column_name' maxlength='$max_length' value='$row3[$column_name]' required ><br>";
                            } else if ($data_type == 'int' || $data_type == 'bigint' || $data_type == 'decimal' || $data_type == 'smallint') {
                                if ($AI == 'SI') {
                                    echo "<label class='linea'><img id='cen' src='IMG/llave.png' width='20'>$column_name:</label>";
                                    echo "<label class='linea' >$column_type:</label>";
                                    echo "<input id='$column_name' class='linea type='number' name='$column_name' value='$row3[$column_name]' style='background-color: gold;' /><br>";
                                } else {
                                    echo "<label class='linea'><img id='cen' src='IMG/llave.png' width='20'>$column_name:</label>";
                                    echo "<label class='linea' >$column_type:</label>";
                                    echo "<input id='$column_name' class='linea' type='number' name='$column_name'  value='$row3[$column_name]' required ><br>";
                                }
                            } else if ($data_type == 'date') {
                                echo "<label class='linea'><img id='cen' src='IMG/llave.png' width='20'>$column_name:</label>";
                                echo "<label class='linea' >$column_type :</label>";
                                echo "<input id='$column_name' class='linea' type='date' name='$column_name'  value='$row3[$column_name]' required ><br>";
                            } else if ($data_type == 'datetime' || $data_type == 'timestamp') {
                                echo "<label class='linea'><img id='cen' src='IMG/llave.png' width='20'>$column_name:</label>";
                                echo "<label class='linea' >$column_type :</label>";
                                echo "<input id='$column_name' class='linea' type='datetime-local' name='$column_name'  value='$row3[$column_name]' required ><br>";
                            } else if ($data_type == 'boolean') {
                                echo "<label class='linea'><img id='cen' src='IMG/llave.png' width='20'>$column_name:</label>";
                                echo "<label class='linea' >$column_type :</label>";
                                echo "<input id='$column_name' class='linea' type='checkbox' name='$column_name'  value='$row3[$column_name]' required ><br>";
                            }
                        } else {
                            if ($key  == "PRI" && $referencetab != null) {
                                $mysqli = new mysqli('localhost', 'root', '', $db);
                                $result2 = $mysqli->query("SELECT $referencecol FROM $referencetab");
                                $tiposDato = array();
                                while ($row = $result2->fetch_assoc()) {
                                    $luc = $row["$referencecol"];
                                    $tipo = explode('(', $luc);
                                    $tiposDato[] = $tipo[0];
                                }
                                $tiposDato = array_unique($tiposDato);
                                echo "<label class='linea' ><img id='cen' src='IMG/llave.png' width='20'><img id='cen' src='IMG/llavefk.png' width='20'>$column_name:</label>";
                                echo "<label class='linea' >$column_type:</label>";
                                echo "<select id='$column_name' class='linea' name='$column_name' required>";
                                foreach ($tiposDato as $tipoDato) {
                                    if($row3[$column_name] == $tipoDato){
                                        echo '<option value="' . $tipoDato . '" selected>' . $tipoDato . '</option>';
                                    }else{
                                        echo '<option value="' . $tipoDato . '">' . $tipoDato . '</option>';
                                    }
                                }
                                echo '</select><br>';
                            } else {
                                if ($key  == "MUL") {
                                    $mysqli = new mysqli('localhost', 'root', '', $db);
                                    $result2 = $mysqli->query("SELECT $referencecol FROM $referencetab");
                                    $tiposDato = array();
                                    while ($row = $result2->fetch_assoc()) {
                                        $luc = $row["$referencecol"];
                                        $tipo = explode('(', $luc);
                                        $tiposDato[] = $tipo[0];
                                    }
                                    $tiposDato = array_unique($tiposDato);
                                    echo "<label class='linea' ><img id='cen' src='IMG/llavefk.png' width='20'>$column_name:</label>";
                                    echo "<label class='linea' >$column_type:</label>";
                                    echo "<select id='$column_name' class='linea' name='$column_name' required>";
                                    foreach ($tiposDato as $tipoDato) {
                                        if($row3[$column_name] == $tipoDato){
                                            echo '<option value="' . $tipoDato . '" selected>' . $tipoDato . '</option>';
                                        }else{
                                            echo '<option value="' . $tipoDato . '">' . $tipoDato . '</option>';
                                        }
                                        
                                    }
                                    echo '</select><br>';
                                } else {
                                    if ($key  == "PRI" && $referencetab != null) {
                                    } else {
                                        if ($PKS == 2 && $key  == "PRI") {
                                        } else {
                                            if ($data_type == 'varchar' || $data_type == 'char' || $data_type == 'text'|| $data_type == 'longblob') {
                                                echo "<label class='linea' >$column_name:</label>";
                                                echo "<label class='linea' >$column_type:</label>";
                                                echo "<input id='$column_name' class='linea' type='text' name='$column_name' maxlength='$max_length' value='$row3[$column_name]' required ><br>";
                                            } else if ($data_type == 'int' || $data_type == 'bigint' || $data_type == 'decimal' || $data_type == 'smallint') {
                                                echo "<label class='linea'>$column_name:</label>";
                                                echo "<label class='linea' >$column_type:</label>";
                                                echo "<input id='$column_name' class='linea' type='number' name='$column_name' value='$row3[$column_name]' required ><br>";
                                            } else if ($data_type == 'date') {
                                                echo "<label class='linea'>$column_name:</label>";
                                                echo "<label class='linea' >$column_type :</label>";
                                                echo "<input id='$column_name' class='linea' type='date' name='$column_name' value='$row3[$column_name]' required ><br>";
                                            } else if ($data_type == 'datetime' || $data_type == 'timestamp') {
                                                echo "<label class='linea'>$column_name:</label>";
                                                echo "<label class='linea' >$column_type :</label>";
                                                echo "<input id='$column_name' class='linea' type='datetime-local' name='$column_name' value='$row3[$column_name]' required ><br>";
                                            } else if ($data_type == 'boolean') {
                                                echo "<label class='linea'>$column_name:</label>";
                                                echo "<label class='linea' >$column_type :</label>";
                                                echo "<input id='$column_name' class='linea' type='checkbox' name='$column_name' value='$row3[$column_name]' required ><br>";
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    echo '<button type="submit" >Actualizar Datos</button>';
                    echo '</form>';
                    mysqli_close($enlace);
                    mysqli_close($enlace3);
                }
                ?>
            </div>
        </main>

    </div>
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