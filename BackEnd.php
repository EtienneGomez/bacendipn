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

$id = $_POST['id'] ?? null;
$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;

if ($email !== null && $password !== null) {
    // Consulta para verificar las credenciales de inicio de sesión
    $sql = "SELECT * FROM usuarios WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->rowCount() > 0) {
        // Inicio de sesión exitoso
        $row = $result->fetch(PDO::FETCH_ASSOC);
        unset($row['password']); // Es una buena práctica no enviar la contraseña, incluso si no te preocupa la seguridad
        echo json_encode(['message' => 'Inicio de sesión exitoso'] + $row);
    } else {
        // Credenciales inválidas
        http_response_code(401);
        echo json_encode(['message' => 'Credenciales inválidas']);
    }
} elseif ($id !== null) {
    // Consulta por ID
    $sql = "SELECT * FROM usuarios WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->rowCount() > 0) {
        // Usuario encontrado
        $row = $result->fetch(PDO::FETCH_ASSOC);
        unset($row['password']); // Es una buena práctica no enviar la contraseña, incluso si no te preocupa la seguridad
        echo json_encode(['message' => 'Usuario encontrado'] + $row);
    } else {
        // Usuario no encontrado
        http_response_code(404);
        echo json_encode(['message' => 'Usuario no encontrado']);
    }
} else {
    // No se proporcionaron datos suficientes para la consulta
    http_response_code(400);
    echo json_encode(['message' => 'Datos insuficientes']);
}

$conn = null;
?>
