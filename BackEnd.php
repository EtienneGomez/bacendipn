<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Conexión a la base de datos MySQL
$servername = "servidorpruebaipn1"; // Cambia esto si tu servidor de base de datos tiene un nombre diferente
$username =   "servidorpruebaipn1"; // Cambia esto por tu nombre de usuario de MySQL
$password = "Etienne09"; // Cambia esto por tu contraseña de MySQL
$dbname = "base1"; // Cambia esto por el nombre de tu base de datos
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
    $sql = "SELECT * FROM usuarios WHERE email = '$email' AND password = '$password'";
    
}else{
    $sql = "SELECT * FROM usuarios WHERE id = '$id'";
}
    $result = $conn->query($sql);

if ($result->rowCount() > 0) {
    // Inicio de sesión exitoso
    $row = $result->fetch_assoc();
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
} else {
    // Credenciales inválidas
    http_response_code(401);
    $response = array('message' => 'Credenciales inválidas');
    echo json_encode($response);
}

$conn = null;

?>
