<?php
include("PHP/conexion.php");
if (isset($_GET['db'])) {
  $db = $_GET['db'];
  if (isset($_GET['tabla'])) {
    $tabla = $_GET['tabla'];
  }
}
?>

<?php
// Establecer conexión a la base de datos
$conn = mysqli_connect("localhost", "root", "", $db);

// Obtener los campos y tipos de datos de la tabla
$sql = "SELECT column_name, data_type, character_maximum_length 
        FROM information_schema.columns 
        WHERE table_name = $tabla";
$result = mysqli_query($conn, $sql);

// Generar los campos del formulario
echo '<form method="post">';
while ($row = mysqli_fetch_assoc($result)) {
  $column_name = $row['column_name'];
  $data_type = $row['data_type'];
  $max_length = $row['character_maximum_length'];

  // Generar el campo de entrada en función del tipo de datos de la columna
  if ($data_type == 'varchar' || $data_type == 'char' || $data_type == 'text') {
    echo "<label>$column_name:</label>";
    echo "<input type='text' name='$column_name' maxlength='$max_length'><br>";
  } else if ($data_type == 'int' || $data_type == 'bigint' || $data_type == 'decimal') {
    echo "<label>$column_name:</label>";
    echo "<input type='number' name='$column_name'><br>";
  } else if ($data_type == 'date') {
    echo "<label>$column_name:</label>";
    echo "<input type='date' name='$column_name'><br>";
  } else if ($data_type == 'datetime' || $data_type == 'timestamp') {
    echo "<label>$column_name:</label>";
    echo "<input type='datetime-local' name='$column_name'><br>";
  } else if ($data_type == 'boolean') {
    echo "<label>$column_name:</label>";
    echo "<input type='checkbox' name='$column_name' value='1'><br>";
  }
}
echo '<button> Agregar</button>';
echo '</form>';
?>