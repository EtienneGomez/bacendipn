<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");


// Crear conexión
try {
    $conn = new PDO("sqlsrv:server = tcp:servidorpruebaipn1.database.windows.net,1433; Database = base1", "servidorpruebaipn1", "Etienne098");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    print("Error connecting to SQL Server.");
    die(print_r($e));
}

// Conexión a la base de datos MySQL
$servername = "servidorpruebaipn1"; // Cambia esto si tu servidor de base de datos tiene un nombre diferente
$username = "servidorpruebaipn1"; // Cambia esto por tu nombre de usuario de MySQL
$password = "Etienne098"; // Cambia esto por tu contraseña de MySQL
$dbname = "base1"; // Cambia esto por el nombre de tu base de datos



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
