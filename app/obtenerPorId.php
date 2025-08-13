<?php
header('Content-Type: application/json');
require_once '../conexion/bd.php';

$request = json_decode(file_get_contents('php://input'), true);
$id = isset($request['id']) ? intval($request['id']) : 0;

if ($id <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'ID de usuario inválido'
    ]);
    exit();
}

try {
    $sql = "SELECT id, nombre, email, edad FROM usuarios WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario) {
        // Formatear los datos
        $usuario['id'] = intval($usuario['id']);
        $usuario['edad'] = intval($usuario['edad']);
        
        echo json_encode([
            'success' => true,
            'data' => $usuario,
            // Mantener compatibilidad con el código actual
            'id' => $usuario['id'],
            'nombre' => $usuario['nombre'],
            'email' => $usuario['email'],
            'edad' => $usuario['edad']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Usuario no encontrado'
        ]);
    }
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor: ' . $e->getMessage()
    ]);
}
?>