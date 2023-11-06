<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

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
        $row = $stmt->fetch();
        $response = array(
            'message' => 'Inicio de sesión exitoso',
            'id' => $row['id'],
            'nombre' => $row['nombre'],
            'apellidos' => $row['apellidos'],
            'boleta' => $row['boleta'],
            'telefono' => $row['telefono'],
            'escuela' => $row['escuela'],
            'plan_relacion' => $row['plan_relacion'],
            'descripcion' => $row['descripcion'],
            'imagen' => $row['imagen'] // Asegúrate
        );
        echo json_encode($response);
    } else {
        // Credenciales inválidas
        http_response_code(401);
        $response = array('message' => 'Credenciales inválidas');
        echo json_encode($response);
    }
} else {
    // Datos no proporcionados
    http_response_code(400);
    $response = array('message' => 'Datos de inicio de sesión no proporcionados');
    echo json_encode($response);
}

$conn = null;
?>
