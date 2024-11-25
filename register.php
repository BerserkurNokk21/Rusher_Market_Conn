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
$username = $_POST['username'];
$password = $_POST['password'];

// Verificar si el nombre de usuario ya existe en la tabla Player
$query = "SELECT * FROM Player WHERE Username = $1";
$result = pg_query_params($conn, $query, array($username));

if (pg_num_rows($result) > 0) {
    echo "duplicate"; // El nombre de usuario ya existe
} else {
    // Si no existe, insertar un nuevo jugador
    $insert_query = "INSERT INTO Player (Player_Id, Username, Password) VALUES (uuid_generate_v4(), $1, $2)";
    $insert_result = pg_query_params($conn, $insert_query, array($username, $password));

    if ($insert_result) {
        echo "success"; // Registro exitoso
    } else {
        echo "error"; // Ocurrió un error
    }
}

// Cerrar conexión
pg_close($conn);
?>
