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

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
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
  <div class="sidebar">
    <br>
    <a href="index.php">
      <h1 id="titulo">VyrSQL</h1>
      <img id="bd" src="IMG/bd_logo.png" width="60">
    </a>
    <br><br>
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
                    <ul class='list__show list__item--active'>";
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
            echo "<li class='list__inside'><a href='' class='nav__link nav__link--inside'>Sin Tablas</a></li>";
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

  <div class="main-content">
    <!--NAV-->
    <header>
      <h2>
        <label for="nav-toggle">
          <span class="las la-bars" id="barras"></span>
        </label> <b id="subti">Tablero</b>
      </h2>
    </header>
    <main>
      <button onclick="agregar()"><img id="cen" src="IMG/plusdb.png" width="20">Crear Base de datos</button>
      <button onclick="eliminar()"><img id="cen" src="IMG/boton-eliminar.png" width="20">Eliminar Base de datos</button>
      <a href="consultaindex.php"><button type="submit"><img id="cen" src="IMG/sql.png" width="20">Consulta SQL</button></a>
      <div class="base">
        <?php
        $enlace = mysqli_connect('localhost', 'root', '');
        $sql = "SHOW DATABASES";
        $result = mysqli_query($enlace, $sql);
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            $enlace2 = mysqli_connect('localhost', 'root', '', $row['Database']);
            $sql2 = "SHOW TABLES";
            $result2 = mysqli_query($enlace2, $sql2);
            $nomb = $row['Database'];
            $jsonDatos = json_encode($nomb);
            echo "<div id='flotante'>";
            echo "<div id='divisor'>";
            echo "<a href='verdb.php?db=$row[Database]'><h2 >" . $row['Database'] . "</h2></a>";
            echo "<strong>Numero de Tablas: </strong>" . mysqli_num_rows($result2);
            echo "</div>";
            echo "<div id='divisor2'>";
            echo "<p><img onclick='eliminar2($jsonDatos)'src='IMG/boton-eliminar.png' width='35'></p>";
            echo "</div>";
            echo "</div>";
          }
        } else {
          echo "<p> No se encontraron bases de datos. </p>";
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

    function eliminar() {
      Swal.fire({
        title: "Eliminar Base de datos",
        icon: 'warning',
        html: "<form action='eliminardb.php' method='POST' required><label for='Crear'> <strong>Nombre de la Base de datos</strong> </label><input id='Crear' type='text' placeholder='Base de Datos' name='Nomb_BD'  required><br><br><button type='submit'>Eliminar</button><br></form>",
        confirmButtonText: "Cancelar",
        confirmButtonColor: "red",
      })
    }

    function eliminar2(base) {
      Swal.fire({
        title: 'Desea Eliminar la Base de datos ' + base + '?',
        text: "DROP DATABASE " + base,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar'
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire(
            'Eliminado!',
            'Base de Datos Eliminada',
            'success'
          )
          window.location.href = 'eliminardbprop.php?db=' + base;
        }
      })
    }
  </script>
  <script src="main.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>