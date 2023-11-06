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

$id = null;
$password = null;
$email = null;

if(isset($_POST['id'])){
    $id = $_POST['id'];
}

if(isset($_POST['email']) && isset($_POST['password'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
}

if(empty($id)){
    // Consulta para verificar las credenciales de inicio de sesión
    $sql = "SELECT * FROM usuarios WHERE email = :email AND password = :password";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['email' => $email, 'password' => $password]);
} else {
    $sql = "SELECT * FROM usuarios WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $id]);
}

if ($stmt->rowCount() > 0) {
    // Inicio de sesión exitoso
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $response = array(
        'message' => 'Inicio de sesión exitoso',
    'id' => $row['id'],
    'email' => $row['email'],
    // No incluimos la contraseña por razones de seguridad
    'nombre' => $row['nombre'],
    'apellidos' => $row['apellidos'],
    'boleta' => $row['boleta'],
    'telefono' => $row['telefono'],
    'escuela' => $row['escuela'],
    'plan_relacion' => $row['plan_relacion'],
    'descripcion' => $row['descripcion'],
    'imagen' => $row['imagen'] // Asegúr
    );

    echo json_encode($response);
} else {
    // Credenciales inválidas
    http_response_code(401);
    $response = array('message' => 'Credenciales inválidas');
    echo json_encode($response);
}

$conn = null;
?>
