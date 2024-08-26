<?php
if (isset($_GET['db'])) {
    $db = $_GET['db'];
    if (isset($_GET['tabla'])) {
        $tabla = $_GET['tabla'];
    }
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
        background-color: #121f42;
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
                <a href="insertardatos.php?db=<?php echo $db ?>&tabla=<?php echo $tabla ?>"><button type="submit"><img id="cen" src="IMG/insertar.png" width="20">Insertar Datos</button></a>
                <a href="consulta.php?db=<?php echo $db ?>&tabla=<?php echo $tabla ?>"><button type="submit"><img id="cen" src="IMG/sql.png" width="20">Consulta SQL</button></a>
            </div>

            <?php
            $enlace = mysqli_connect('localhost', 'root', '', $db);
            $sql = "SHOW FIELDS FROM $tabla";
            $result = mysqli_query($enlace, $sql);
            $campos = mysqli_fetch_fields($result);
            $nombreCampos = array();
            foreach ($campos as $campo) {
                $nombreCampos[] = $campo->name;
            }
            echo "<div id='tablas' ><h3>Numero de Campos: " . mysqli_num_rows($result) . "</h3></div>";
            echo "<div class='rodear'>";
            if (mysqli_num_rows($result) > 0) {
                echo "<table id='Eliminar'>";
                echo "<tr>";
                echo "<th>Editar</th>";
                echo "<th>Eliminar</th>";
                // Mostrar los nombres de los campos como encabezados de la tabla
                while ($campo = mysqli_fetch_field($result)) {
                    echo "<th>" . $campo->name . "</th>";
                }
                echo "</tr>";
                // Mostrar los rs$results
                while ($fila = mysqli_fetch_assoc($result)) {
                    $p = $nombreCampos[0];
                    $basic = array($db, $tabla, $fila[$p]);
                    $jsonDatos = json_encode($basic);
                    echo "<tr>";
                    // Recorrer todos los campos de la fila
                    echo "<td><a href='editarcampo.php?db=$db&tabla=$tabla&campo=$fila[$p]'><img id='icon' src='IMG/editar.png' width='20'>Editar</a></td>";
                    if (mysqli_num_rows($result) == 1) {
                        echo "<td><a onclick='ultimocampo()'><img id='icon' src='IMG/boton-eliminar.png' width='20'>Eliminar</a></td>";
                    } else {
                        echo "<td><a onclick='eliminarcampo($jsonDatos)'><img id='icon' src='IMG/boton-eliminar.png' width='20'>Eliminar</a></td>";
                    }
                    foreach ($fila as $valor) {
                        if ($valor == "PRI") {
                            echo "<td><img id='cen' src='IMG/llave.png' width='20'></td>";
                        } else {
                            if ($valor == "MUL") {
                                echo "<td><img id='cen' src='IMG/llavefk.png' width='20'></td>";
                            } else {
                                echo "<td>" . $valor . "</td>";
                            }
                        }
                    }
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No se encontraron resultados";
            }

            // Cerrar la conexión
            mysqli_close($enlace);
            ?>
    </div>
    <div id='agregartab'>
        <h2 id='titag'>Agregar Campos</h2><img id="cen" src="IMG/insertar.png" width="30">
        <form action="insertarcampos.php?db=<?php echo $db ?>&tabla=<?php echo $tabla ?>" method="post">
            <label id="line2" for="num_campos">Numero de Campos:</label><br>
            <input class="inptb2" type="number" name="num_campos" required placeholder="1" min="1">
            <button type="submit">Agregar</button>
        </form>
    </div>

    <div>
        <?php
        $conn =  mysqli_connect('localhost', 'root', '', $db);

        // Verificar si la conexión es exitosa
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Nombre de la tabla a consultar
        $table_name = "$tabla";

        // Consulta SQL para obtener información sobre las claves primarias y foráneas
        $sql = "SHOW KEYS FROM $table_name";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "<div id='tablas' ><h3>Numero de LLaves: " . mysqli_num_rows($result) . "</h3></div>";
            echo "<div class='rodear'>";
            // Crear tabla HTML para mostrar los resultados
            echo "<table>";
            echo "<tr><th>Eliminar</th><th>Nombre de la clave</th><th>Tipo</th><th>Column</th><th>Nulo</th></tr>";

            // Recorrer los resultados de la consulta y mostrarlos en la tabla HTML
            while ($row = mysqli_fetch_assoc($result)) {
                $key_name = $row['Key_name'];
                $key_type = $row['Index_type'];
                $column = $row['Column_name'];
                $nullable = $row['Null'] == '' ? 'NO' : $row['Null'];

                $nombre_llave = json_encode($key_name);
                if ($key_name == 'PRIMARY') {
                    echo "<tr><td><a onclick='primary()'><img id='icon' src='IMG/boton-eliminar.png' width='20'>Eliminar</a></td><td><img id='cen' src='IMG/llave.png' width='20'>$key_name</td><td>$key_type</td><td>$column</td><td>$nullable</td></tr>";
                } else {
                    echo "<tr><td><a onclick='eliminarfk($nombre_llave)'><img id='icon' src='IMG/boton-eliminar.png' width='20'>Eliminar</a></td><td><img id='cen' src='IMG/llavefk.png' width='20'>$key_name</td><td>$key_type</td><td>$column</td><td>$nullable</td></tr>";
                }
            }

            echo "</table>";

            // Cerrar la conexión a la base de datos MySQL
            mysqli_close($conn);
        } else {
        }

        ?>
    </div>
    </div>

    <div id='agregartab'>
        <?php
        $mysqli = new mysqli('localhost', 'root', '', $db);
        $result = $mysqli->query("SHOW FIELDS FROM $tabla");
        $tiposDato = array();
        while ($row = $result->fetch_assoc()) {
            $tiposDato[] =  $row['Field'];
        }
        $con = mysqli_connect("localhost", "root", "", $db);
        $sql2 = "SHOW TABLES";
        $result2 = mysqli_query($con, $sql2);
        $tablas2 = array();
        while ($fila2 = mysqli_fetch_assoc($result2)) {
            if ($tabla == $fila2["Tables_in_$db"]) {
            } else {
                $tablas2[] = $fila2["Tables_in_$db"];
            }
        }
        ?>

        <h2 id='titag'>Agregar LLave Foranea</h2> <img id="cen" src="IMG/llavefk.png" width="30">
        <form method="post" action="insertarfk.php?db=<?php echo $db ?>&tabla=<?php echo $tabla ?>">
            <label id="line2" for="campo">Campo:</label>
            <label id="line2" for='tabla_ref'>Tabla a Referenciar:</label><br>
            <?php
            echo '<select class="inptb2" type="text" name="campo" id="campo" required>';
            foreach ($tiposDato as $tipoDato) {
                echo '<option value="' . $tipoDato . '">' . $tipoDato . '</option>';
            }
            echo '</select>';
            ?>
            <?php
            echo '<select class="inptb2" type="text" name="tabla_ref" id="tabla_ref" required>';
            foreach ($tablas2 as $t) {
                echo "<option value='" . $t . "'>" . $t . "</option>";
            }
            echo '</select>';
            ?>
            <button type="submit">Agregar</button>
        </form>
    </div>
    </main>

    </div>
    <script>
        function editarcampo(edit) {
            var consultasql = "UPDATE " + edit[1] + " SET " + edit[2] + " WHERE CONDICIÓN";
            Swal.fire({
                title: "Editar Campo",
                text: "¿Realmente desea ejecutar " + consultasql,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Editar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Editado!',
                        consultasql,
                        'success'
                    )
                    window.location.href = 'editarcampo.php?db=<?php echo $db ?>&tabla=<?php echo $tabla ?>' + '&campo=' + edit[2];
                }
            })
        }

        function eliminarcampo(valor) {
            var consultasql = "ALTER TABLE " + valor[1] + " DROP " + valor[2];
            Swal.fire({
                title: "Eliminar Campo",
                text: "¿Realmente desea ejecutar " + consultasql,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Eliminalo!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Eliminado!',
                        consultasql,
                        'success'
                    )
                    window.location.href = 'eliminarcampo.php?db=<?php echo $db ?>&tabla=<?php echo $tabla ?>' + '&campo=' + valor[2];
                }
            })
        }

        function eliminarfk(fk) {
            var consultasql = "ALTER TABLE '<?php echo $tabla ?>' DROP INDEX '" + fk + "'";
            Swal.fire({
                title: "Eliminar LLave Foranea",
                text: "¿Realmente desea ejecutar " + consultasql,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Eliminalo!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Eliminado!',
                        consultasql,
                        'success'
                    )
                    window.location.href = 'eliminarfk.php?db=<?php echo $db ?>&tabla=<?php echo $tabla ?>' + '&fk=' + fk;
                }
            })
        }

        function primary() {
            Swal.fire(
                'Detalle',
                'No puedo eliminar esta llave Primaria <img id="icon" src="IMG/llave.png" width="20">',
                'warning'
            )
        }

        function ultimocampo() {
            Swal.fire(
                'Detalle',
                'No puedo eliminar este campo',
                'warning'
            )
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