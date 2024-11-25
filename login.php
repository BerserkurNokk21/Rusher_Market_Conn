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

// Recibir datos del formulario
$username = $_POST['username'];
$password = $_POST['password'];

// Verificar si las credenciales son correctas
$query = "SELECT * FROM Player WHERE Username = $1 AND Password = $2";
$result = pg_query_params($conn, $query, array($username, $password));

if (pg_num_rows($result) > 0) {
    $row = pg_fetch_assoc($result);
    $player_id = $row['player_id'];
    $username = $row['username'];

    // Devolver respuesta JSON
    echo json_encode(array("status" => "success", "player_id" => $player_id, "username" => $username));
} else {
    echo json_encode(array("status" => "error")); // Fallo en el inicio de sesión
}

// Cerrar conexión
pg_close($conn);
?>
