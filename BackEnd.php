<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

try {
    $conn = new PDO("sqlsrv:server = tcp:servidorpruebaipn1.database.windows.net,1433; Database = base1", "servidorpruebaipn1", "Etienne098");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = $_POST['id'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    if ($email && $password) {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            // Inicio de sesión exitoso
            unset($user['password']); // Elimina la contraseña por seguridad
            echo json_encode([
                'message' => 'Inicio de sesión exitoso',
            ] + $user);
        } else {
            // Credenciales inválidas
            http_response_code(401);
            echo json_encode(['message' => 'Credenciales inválidas']);
        }
    } elseif ($id) {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            unset($user['password']); // Elimina la contraseña por seguridad
            echo json_encode([
                'message' => 'Usuario encontrado',
            ] + $user);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Usuario no encontrado']);
        }
    } else {
        throw new Exception('No se proporcionaron datos suficientes para la consulta.');
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Error de conexión a la base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['message' => $e->getMessage()]);
}
?>
