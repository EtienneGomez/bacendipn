<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Crear conexión
try {
    $conn = new PDO("sqlsrv:server = tcp:servidorpruebaipn1.database.windows.net,1433; Database = base1", "servidorpruebaipn1", "Etienne098");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print("Error connecting to SQL Server.");
    die(print_r($e));
}

$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;

if ($email !== null && $password !== null) {
    // Consulta para verificar las credenciales de inicio de sesión
    $sql = "SELECT * FROM usuarios WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email, $password]);

    if ($stmt->rowCount() > 0) {
        // Inicio de sesión exitoso
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        unset($row['password']); // Eliminar la contraseña por seguridad
        echo json_encode(['message' => 'Inicio de sesión exitoso'] + $row);
    } else {
        // Credenciales inválidas
        http_response_code(401);
        echo json_encode(['message' => 'Credenciales inválidas']);
    }
} else {
    // No se proporcionaron datos suficientes para la consulta
    http_response_code(400);
    echo json_encode(['message' => 'Datos insuficientes']);
}

$conn = null;
?>
