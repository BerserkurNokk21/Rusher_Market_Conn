<?php
$host = "localhost";
$port = "5432";
$dbname = "RushM_DB";
$user = "postgres";
$password = "postgres";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    echo json_encode(["error" => "Error: No se pudo conectar a PostgreSQL"]);
    exit;
}

if (isset($_POST['shopping_list_id']) && isset($_POST['product_id']) && isset($_POST['item_picked'])) {
    $shopping_list_id = $_POST['shopping_list_id'];
    $product_id = $_POST['product_id'];
    $item_picked = $_POST['item_picked'];

    $task_action_query = "INSERT INTO task_actions (task_action_id, shopping_list_id, product_id, item_picked) 
                          VALUES (uuid_generate_v4(), $1, $2, $3)";
    $result = pg_query_params($conn, $task_action_query, array($shopping_list_id, $product_id, $item_picked));

    if (!$result) {
        echo json_encode(["error" => "Error al insertar en Task_Actions para el producto: " . $product_id]);
        exit;
    } else {
        echo json_encode(["message" => "Acción de tarea registrada correctamente para el producto ID: " . $product_id]);
    }
} else {
    echo json_encode(["error" => "Error: Datos no proporcionados correctamente (se necesita shopping_list_id, product_id y item_picked)."]);
}
?>
