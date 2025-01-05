<?php
$host = "localhost";
$port = "5432";
$dbname = "RushM_DB";
$user = "postgres";
$password = "postgres";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    echo json_encode([
        "status" => "error",
        "message" => "Error: No se pudo conectar a PostgreSQL",
        "data" => null
    ]);
    exit;
}

// Función para generar UUID v4
function generateUUIDv4() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

if (isset($_POST['lobby_id'])) {
    $lobby_id = $_POST['lobby_id'];
    
    try {
        // Generar nuevo UUID para game_id
        $game_id = generateUUIDv4();
        
        $query = "INSERT INTO games (game_id, time_played, player_champion) 
                VALUES ($1, '00:00:00', NULL) 
                RETURNING game_id";
                
        $result = pg_query_params($conn, $query, array($game_id));
        
        if ($result) {
            $row = pg_fetch_assoc($result);
            $response = [
                "status" => "success",
                "message" => "Partida registrada correctamente",
                "data" => [
                    "game_id" => $row['game_id']
                ]
            ];
            error_log("Response: " . json_encode($response)); // Debug log
            echo json_encode($response);
        } else {
            $error = pg_last_error($conn);
            echo json_encode([
                "status" => "error",
                "message" => "Error al crear el registro del juego: " . $error,
                "data" => null
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Error: " . $e->getMessage(),
            "data" => null
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Falta el lobby_id",
        "data" => null
    ]);
}
?>