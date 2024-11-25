<?php
$host = "localhost";
$port = "5432";
$dbname = "RushM_DB";
$user = "postgres";
$password = "postgres";

// Crear conexión
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Verificar conexión
if (!$conn) {
    die("Error: No se pudo conectar a PostgreSQL");
}

// Consulta a la base de datos para obtener todos los productos
$query = "SELECT Product_Id, Product_Name FROM Products";
$result = pg_query($conn, $query);

if (!$result) {
    echo "Error en la consulta a la base de datos.";
    exit;
}

$products = array();

while ($row = pg_fetch_assoc($result)) {
    $products[] = array(
        'id' => $row['product_id'],
        'name' => $row['product_name']
    );
}

// Devolver los ítems en formato JSON
echo json_encode($products);
?>
