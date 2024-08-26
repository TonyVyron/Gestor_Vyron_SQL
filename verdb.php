<?php
if (isset($_GET['db'])) {
    $db = $_GET['db'];
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
    <div class="sidebar"> <br>

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
                </label> <b id="subti"><?php echo $db ?></b>
            </h2>


        </header>

        <main>
            <div>
                <a href=""><button type="submit"><img id="cen" src="IMG/estructura.png" width="20">Estructura</button></a>
                <a href="consultadb.php?db=<?php echo $db ?>"><button type="submit"><img id="cen" src="IMG/sql.png" width="20">Consulta SQL</button></a>
            </div>

            <?php
            if ($db == "information_schema" or $db == "mysql") {
                echo "<div id='tablas' ><h3>No se encontraron resultados</h3></div>";
            } else {
                $enlace = mysqli_connect('localhost', 'root', '', $db);
                $sql = "SHOW TABLES";
                $result = mysqli_query($enlace, $sql);
                $campos = mysqli_fetch_fields($result);
                $nombreCampos = array();
                foreach ($campos as $campo) {
                    $nombreCampos[] = $campo->name;
                }
                echo "<div id='tablas' ><h3>Numero de Tablas: " . mysqli_num_rows($result) . "</h3></div>";
                echo "<div class='rodear'>";
                if (mysqli_num_rows($result) > 0) {
                    echo "<table id='Eliminar'>";
                    echo "<tr>";
                    // Mostrar los nombres de los campos como encabezados de la tabla
                    while ($campo = mysqli_fetch_field($result)) {
                        echo "<th>Tablas</th>";
                        echo "<th>Examinar</th>";
                        echo "<th>Estructura</th>";
                        echo "<th>Eliminar</th>";
                        echo "<th>Motor</th>";
                        echo "<th>Filas</th>";
                        echo "<th>Columnas</th>";
                    }
                    echo "</tr>";
                    // Mostrar los rs$results
                    while ($fila = mysqli_fetch_assoc($result)) {
                        $p = $nombreCampos[0];
                        $basic = array($db, $fila[$p]);
                        $jsonDatos = json_encode($basic);
                        echo "<tr>";
                        // Recorrer todos los campos de la fila
                        foreach ($fila as $valor) {
                            echo "<td><a href='tabladb.php?db=$db&tabla=$valor'>" . $valor . "</td></a>";
                            echo "<td><a href='tabladb.php?db=$db&tabla=$valor'><img id='icon' src='IMG/examinar.png' width='20'>Examinar</a></td>";
                            echo "<td><a href='datosdb.php?db=$db&tabla=$valor'><img id='icon' src='IMG/estructura.png' width='20'>Estructura</td></a>";
                        }
                        echo "<td><a onclick='eliminartabla($jsonDatos)'><img id='icon' src='IMG/boton-eliminar.png' width='20'>Eliminar</a></td>";
                        foreach ($fila as $valor) {
                            $enlace3 = mysqli_connect('localhost', 'root', '', $db);
                            $sql4 = "SHOW TABLE STATUS FROM $db WHERE Name = '$valor'";
                            $result4 = mysqli_query($enlace3, $sql4);
                            while ($fila2 = mysqli_fetch_assoc($result4)) {
                                echo "<td>" . $fila2["Engine"] . "</td>";
                            }
                            mysqli_close($enlace3);
                        }
                        foreach ($fila as $valor) {
                            $sql2 = "Select * from $valor";
                            $result2 = mysqli_query($enlace, $sql2);
                            echo "<td>" . mysqli_num_rows($result2) . "</td>";
                        }
                        foreach ($fila as $valor) {
                            $enlace2 = mysqli_connect('localhost', 'root', '', $db);
                            $sql3 = "SHOW FIELDS FROM $valor";
                            $result3 = mysqli_query($enlace2, $sql3);
                            echo "<td>" . mysqli_num_rows($result3) . "</td>";
                            mysqli_close($enlace2);
                        }


                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<div id='tablas' ><h3>No se Encontraron resultados</h3></div>";
                }
                // Cerrar la conexión
                mysqli_close($enlace);
            }
            ?>
    </div>
    <div id='agregartab'>
        <img id="cen" src="IMG/plus.png" width="30">
        <h2 id='titag'>Agregar Tabla</h2>
        <form action="insertartb.php?db=<?php echo $db ?>" method="post">
            <label id="line" for="nom_tabla">Nombre de la tabla:</label>
            <label id="line" for="num_campos">Numero de Campos:</label><br>
            <input class="inptb" type="text" name="nom_tabla" required placeholder="tabla">
            <input class="inptb" type="number" name="num_campos" required placeholder="1" min="1">
            <button type="submit">Agregar</button>
        </form>
    </div>
    </main>
    </div>
    <script>
        function eliminartabla(valor) {
            var consultasql = "'DROP TABLE " + valor[1] + "'?";
            Swal.fire({
                title: "Eliminar Tabla",
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
                    window.location.href = 'eliminartabla.php?db=<?php echo $db ?>&tabla=' + valor[1];
                }
            })
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