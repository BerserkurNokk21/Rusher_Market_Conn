<?php
$host = "localhost";
$port = "5432";
$dbname = "RushM_DB";
$user = "postgres";
$password = "postgres";

// Crear conexión
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Error: No se pudo conectar a PostgreSQL"]);
    exit;
}

// Verificar si se han enviado los datos requeridos (player_id y products)
if (isset($_POST['player_id']) && isset($_POST['game_id']) && isset($_POST['products'])) {
    $player_id = $_POST['player_id'];
    $json_products = $_POST['products'];
    $game_id = $_POST['game_id'];
    $product_ids = json_decode($json_products, true)['products']; // Acceder directamente al array de productos

    if (is_array($product_ids) && !empty($product_ids)) {
        $shoppingListMappings = []; // Array para almacenar los mapeos de IDs

        foreach ($product_ids as $product_id) {
            // Generar un `shopping_list_id` único para cada producto
            $query = "INSERT INTO Shopping_Lists (Shopping_List_Id, Product_Id, Game_id, Player_Id) 
                      VALUES (uuid_generate_v4(), $1, $2, $3) RETURNING Shopping_List_Id";
            $result = pg_query_params($conn, $query, array($product_id, $game_id, $player_id));

            if ($result) {
                // Obtener el `shopping_list_id` generado
                $row = pg_fetch_assoc($result);
                $shopping_list_id = $row['shopping_list_id'];

                // Añadir al mapeo de productos y sus respectivos IDs de lista de compras
                $shoppingListMappings[] = ["product_id" => $product_id, "shopping_list_id" => $shopping_list_id];
            } else {
                echo json_encode(["status" => "error", "message" => "Error al insertar en Shopping_List para el producto: " . $product_id]);
                exit;
            }
        }

        // Devolver el mapeo de `shopping_list_id` para cada producto en JSON
        echo json_encode(["status" => "success", "data" => $shoppingListMappings]);
    } else {
        echo json_encode(["status" => "error", "message" => "No se recibieron productos válidos"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Datos no proporcionados correctamente"]);
}
?>
