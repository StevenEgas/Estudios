<?php
header('Content-Type: application/json');
require_once '../conexion/bd.php';

$request = json_decode(file_get_contents('php://input'), true);
$id = isset($request['id']) ? intval($request['id']) : 0;
$nombre = isset($request['nombre']) ? trim($request['nombre']) : '';
$email = isset($request['email']) ? trim($request['email']) : '';
$edad = isset($request['edad']) ? intval($request['edad']) : 0;

// Validaciones
if ($id <= 0) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'ID de usuario inválido'
    ]);
    exit();
}

if (empty($nombre) || empty($email) || $edad <= 0) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Todos los campos son obligatorios y deben ser válidos'
    ]);
    exit();
}

// Validar formato de email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'El formato del email no es válido'
    ]);
    exit();
}

// Validar que la edad sea razonable
if ($edad < 1 || $edad > 120) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'La edad debe estar entre 1 y 120 años'
    ]);
    exit();
}

try {
    // Verificar que el usuario existe
    $checkQuery = "SELECT id FROM usuarios WHERE id = :id";
    $checkStmt = $pdo->prepare($checkQuery);
    $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
    $checkStmt->execute();
    
    if ($checkStmt->rowCount() == 0) {
        echo json_encode([
            'status' => 'error', 
            'message' => 'El usuario no existe'
        ]);
        exit();
    }
    
    // Verificar que el email no esté en uso por otro usuario
    $emailQuery = "SELECT id FROM usuarios WHERE email = :email AND id != :id";
    $emailStmt = $pdo->prepare($emailQuery);
    $emailStmt->bindParam(':email', $email, PDO::PARAM_STR);
    $emailStmt->bindParam(':id', $id, PDO::PARAM_INT);
    $emailStmt->execute();
    
    if ($emailStmt->rowCount() > 0) {
        echo json_encode([
            'status' => 'error', 
            'message' => 'El email ya está en uso por otro usuario'
        ]);
        exit();
    }
    
    // Actualizar usuario
    $sql = "UPDATE usuarios SET nombre = :nombre, email = :email, edad = :edad WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':edad', $edad, PDO::PARAM_INT);
    
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        echo json_encode([
            'status' => 'success', 
            'message' => 'Usuario actualizado correctamente',
            'data' => [
                'id' => $id,
                'nombre' => $nombre,
                'email' => $email,
                'edad' => $edad
            ]
        ]);
    } else {
        echo json_encode([
            'status' => 'error', 
            'message' => 'No se detectaron cambios o no se pudo actualizar el usuario'
        ]);
    }
} catch(PDOException $e) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Error del servidor: ' . $e->getMessage()
    ]);
}
?>