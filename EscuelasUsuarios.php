<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

// Conexión a la base de datos MySQL
$servername = "servidorpruebaipn1"; // Cambia esto si tu servidor de base de datos tiene un nombre diferente
$username = "servidorpruebaipn1"; // Cambia esto por tu nombre de usuario de MySQL
$password = "Etienne098"; // Cambia esto por tu contraseña de MySQL
$dbname = "base1"; // Cambia esto por el nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$escuela = $_POST["escuela"];

if (!empty($escuela)) {
    $sql = "SELECT * FROM usuarios WHERE escuela = '$escuela'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        echo json_encode($rows);
    } else {
        echo "No hay resultados";
    }
} else {
    echo "No se proporcionó la escuela";
}

$conn->close();
?>
