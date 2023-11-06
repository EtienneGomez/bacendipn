<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

try {
    $conn = new PDO("sqlsrv:server = tcp:servidorpruebaipn1.database.windows.net,1433; Database = base1", "servidorpruebaipn1", "Etienne098");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// SQL Server Extension Sample Code:
$connectionInfo = array("UID" => "servidorpruebaipn1", "pwd" => "{your_password_here}", "Database" => "base1", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
$serverName = "tcp:servidorpruebaipn1.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);

    $id = $_POST['id'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    if ($email && $password) {
        // Utiliza sentencias preparadas para evitar inyección SQL
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email AND password = :password");
        $stmt->execute(['email' => $email, 'password' => $password]);
    } elseif ($id) {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);
    } else {
        throw new Exception('No se proporcionaron datos suficientes para la consulta.');
    }

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Inicio de sesión exitoso
        echo json_encode([
            'message' => 'Inicio de sesión exitoso',
            'id' => $row['id'],
            'email' => $row['email'],
            'password' => $row['password'],
            'nombre' => $row['nombre'],
            'apellidos' => $row['apellidos'],
            'boleta' => $row['boleta'],
            'telefono' => $row['telefono'],
            'escuela' => $row['escuela'],
            'plan_relacion' => $row['plan_relacion'],
            'descripcion' => $row['descripcion'],
            'imagen' => $row['imagen']
        ]);
    } else {
        // Credenciales inválidas
        http_response_code(401);
        echo json_encode(['message' => 'Credenciales inválidas']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Error de conexión a la base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['message' => $e->getMessage()]);
}
?>
