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

// Obtener los datos enviados desde el formulario de registro
$email = $_POST["email"] ?? "";
$password = $_POST["password"] ?? "";
$nombre = $_POST["nombre"] ?? "";
$apellido = $_POST["apellido"] ?? "";
$boleta = $_POST["boleta"] ?? "";
$escuela = $_POST["escuela"] ?? "";
$telefono = $_POST["telefono"] ?? "";
$plan_relacion = $_POST["planRelacion"] ?? "";
$descripcion = $_POST["descripcion"] ?? "";

// Manejo de la carga de la imagen
$targetDir = "Project_Polinder/src/media/";
$fileName = basename($_FILES["imagen"]["name"]);
$targetFilePath = $targetDir . $fileName;

if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $targetFilePath)) {
    $nombreImagen = $fileName; // Si la imagen se sube correctamente, obtiene el nombre del archivo
} else {
    $nombreImagen = ""; // Si la imagen no se sube, deja el nombre de la imagen vacío
}

// Query para insertar los datos en la tabla de usuarios
$sql = "INSERT INTO usuarios (email, password, nombre, apellidos, boleta, escuela, telefono, plan_relacion, descripcion, imagen) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

// Ejecutar la consulta preparada
if ($stmt->execute([$email, $password, $nombre, $apellido, $boleta, $escuela, $telefono, $plan_relacion, $descripcion, $nombreImagen])) {
    $userId = $conn->lastInsertId(); // Obtener el ID del último registro insertado
    $response = [
        'message' => 'Registro exitoso',
    'userId' => $userId,
    'email' => $email,
    'nombre' => $nombre,
    'apellido' => $apellido,
    'boleta' => $boleta,
    'escuela' => $escuela,
    'telefono' => $telefono,
    'plan_relacion' => $plan_relacion,
    'descripcion' => $descripcion,
    'imagen' => $nombreImagen // Asegúrate de que esta variable contenga la ruta o URL completa de la imagen si es necesario
    ];
    echo json_encode($response);
} else {
    // Error en el registro
    http_response_code(500);
    $response = ['message' => 'Error en el registro: ' . $stmt->errorInfo()[2]];
    echo json_encode($response);
}

$conn = null; // Cerrar la conexión a la base de datos
?>