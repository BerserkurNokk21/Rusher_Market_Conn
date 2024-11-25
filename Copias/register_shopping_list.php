<?php
$host = "localhost";
$port = "5432";
$dbname = "RushM_DB";
$user = "postgres";
$password = "postgres";

// Crear conexión
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Error: No se pudo conectar a PostgreSQL");
}

// Verificar si se han enviado los datos requeridos (player_id y products)
if (isset($_POST['player_id']) && isset($_POST['products'])) {
    $player_id = $_POST['player_id'];  // ID del jugador
    $json_products = $_POST['products'];  // Lista de IDs de productos en formato JSON

    // Decodificar el JSON en un array asociativo
    $product_ids = json_decode($json_products, true);

    // Verificar si el JSON tiene la estructura correcta y contiene productos
    if (is_array($product_ids) && isset($product_ids['products']) && !empty($product_ids['products'])) {
        echo "Iniciando inserción de productos para el jugador: $player_id<br>";

        foreach ($product_ids['products'] as $product_id) {  // Accede a cada UUID en el array
            // Preparar la consulta para insertar cada producto en la lista de compras
            $query = "INSERT INTO Shopping_Lists (Shopping_List_Id, Product_Id, Player_Id) 
                      VALUES (uuid_generate_v4(), $1, $2)";

            // Ejecutar la consulta con los parámetros (product_id y player_id)
            $result = pg_query_params($conn, $query, array($product_id, $player_id));

            // Verificar si hubo algún error en la inserción
            if (!$result) {
                echo "Error al insertar en Shopping_List para el producto: " . $product_id;
                exit;
            } else {
                echo "Producto $product_id insertado correctamente.<br>";
            }
        }

        // Si todo salió bien, devolver un mensaje de éxito
        echo "Lista de compras registrada correctamente para el jugador con ID: " . $player_id;
    } else {
        echo "Error: No se recibieron productos válidos para registrar en la lista de compras.";
    }
} else {
    echo "Error: Datos no proporcionados correctamente (se necesita player_id y products).";
}
?>
