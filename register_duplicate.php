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

// Recibir datos del formulario
$username = $_POST['username'];

// Verificar si el nombre de usuario ya existe en la tabla Player
$query = "SELECT * FROM Player WHERE Username = $1";
$result = pg_query_params($conn, $query, array($username));

if (pg_num_rows($result) > 0) {
    echo "duplicate"; // El nombre de usuario ya existe
} else {
    echo "available"; // El nombre de usuario está disponible
}

// Cerrar conexión
pg_close($conn);
?>
