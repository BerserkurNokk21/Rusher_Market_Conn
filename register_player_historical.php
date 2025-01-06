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

if (isset($_POST['player_id']) && isset($_POST['game_id'])) {
    $player_id = $_POST['player_id'];
    $game_id = $_POST['game_id'];

    $query = "INSERT INTO player_historical (player_historical_id, player_id, game_id) 
              VALUES (uuid_generate_v4(), \$1, \$2)";
    
    $result = pg_query_params($conn, $query, array($player_id, $game_id));

    if (!$result) {
        echo json_encode([
            "error" => "Error al insertar en player_historical: " . pg_last_error($conn)
        ]);
        exit;
    } else {
        echo json_encode([
            "message" => "Historial del jugador registrado correctamente"
        ]);
    }
} else {
    echo json_encode([
        "error" => "Error: Datos no proporcionados correctamente (se necesita player_id, game_id y time_played)."
    ]);
}

pg_close($conn);
?>