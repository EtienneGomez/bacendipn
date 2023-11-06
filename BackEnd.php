<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

try {
    $conn = new PDO("sqlsrv:server = tcp:servidorpruebaipn1.database.windows.net,1433; Database = base1", "servidorpruebaipn1", "Etienne098");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
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
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email AND password = :password");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
} else {
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = :id");
    $stmt->bindParam('id', $id);
}

$stmt->execute();

if ($stmt->rowCount() > 0) {
    // Inicio de sesión exitoso
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $response = array(
        'message' => 'Inicio de sesión exitoso',
        'id' => $row['id'],
        'password' => $row['password'],
        'apellidos' => $row['apellidos'],
        'email' => $row['email'],
    // ... aquí continúas con los demás campos que quieras incluir
        'telefono' => $row['telefono'],
        'escuela' => $row['escuela'],
    );
    echo json_encode($response);
} else {
    // Inicio de sesión exitoso
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $response = array(
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
    );
    echo json_encode($response);
}

// No es necesario cerrar la conexión PDO, se cierra automáticamente al finalizar el script
?>
