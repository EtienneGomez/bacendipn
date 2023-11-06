<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Crear conexión
try {
    $conn = new PDO("sqlsrv:server = tcp:servidorpruebaipn1.database.windows.net,1433; Database = base1", "servidorpruebaipn1", "Etienne98");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    print("Error connecting to SQL Server.");
    die(print_r($e));
}

$id = $_POST['id'] ?? null;
$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;

try {
    if ($email !== null && $password !== null) {
        // Utiliza sentencias preparadas para evitar inyección SQL
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Inicio de sesión exitoso
            unset($user['password']); // Elimina la contraseña por seguridad
            $response = [
                'message' => 'Inicio de sesión exitoso',
                'id' => $user['id'],
                'email' => $user['email'],
                // No incluir 'password' aquí
                'nombre' => $user['nombre'],
                'apellidos' => $user['apellidos'],
                'boleta' => $user['boleta'],
                'telefono' => $user['telefono'],
                'escuela' => $user['escuela'],
                'plan_relacion' => $user['plan_relacion'],
                'descripcion' => $user['descripcion'],
                'imagen' => $user['imagen']
            ];
            echo json_encode($response);
        } else {
            // Credenciales inválidas
            http_response_code(401);
            echo json_encode(['message' => 'Credenciales inválidas']);
        }
    } elseif ($id !== null) {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            unset($user['password']); // Elimina la contraseña por seguridad
            echo json_encode(['message' => 'Usuario encontrado'] + $user);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Usuario no encontrado']);
        }
    } else {
        throw new Exception('No se proporcionaron datos suficientes para la consulta.');
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['message' => 'Error de conexión a la base de datos']);
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    http_response_code(400);
    echo json_encode(['message' => $e->getMessage()]);
}

$conn = null;
?>
