<?php
$host = "localhost";
$port = "5432";
$dbname = "RushM_DB";
$user = "postgres";
$password = "postgres";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Error: No se pudo conectar a PostgreSQL"]);
    exit;
}

if (isset($_POST['game_id']) &&  isset($_POST['player_champion'])) {
    $game_id = $_POST['game_id'];
    //$time_played = $_POST['time_played'];
    $player_champion = $_POST['player_champion'];

    $query = "UPDATE Games SET player_champion = $1 WHERE game_id = $2";
    error_log("Query: " . $query);
    error_log("Params: " . print_r(array( $player_champion, $game_id), true));
    $result = pg_query_params($conn, $query, array( $player_champion, $game_id));
    error_log("Result: " . print_r($result, true));
    error_log("PG Last Error: " . pg_last_error($conn));
    if ($result) {
        echo json_encode(["status" => "success", "message" => "Champion actualizado correctamente"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al actualizar: " . pg_last_error($conn)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Faltan parámetros requeridos "]);
}

pg_close($conn);
?>