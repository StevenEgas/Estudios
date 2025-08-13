<?php
header('Content-Type: application/json');
require_once '../conexion/bd.php';

$request = json_decode(file_get_contents('php://input'), true);
$id = isset($request['id']) ? intval($request['id']) : 0;

if ($id <= 0) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'ID de usuario inválido'
    ]);
    exit();
}

try {
    // Verificar que el usuario existe antes de eliminarlo
    $checkQuery = "SELECT nombre FROM usuarios WHERE id = :id";
    $checkStmt = $pdo->prepare($checkQuery);
    $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
    $checkStmt->execute();
    
    $usuario = $checkStmt->fetch(PDO::FETCH_ASSOC);
    if (!$usuario) {
        echo json_encode([
            'status' => 'error', 
            'message' => 'El usuario no existe'
        ]);
        exit();
    }
    
    // Verificar si el usuario tiene notas asociadas
    $notasQuery = "SELECT COUNT(*) as total FROM notas WHERE usuario_id = :id";
    $notasStmt = $pdo->prepare($notasQuery);
    $notasStmt->bindParam(':id', $id, PDO::PARAM_INT);
    $notasStmt->execute();
    $notasCount = $notasStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    if ($notasCount > 0) {
        echo json_encode([
            'status' => 'error', 
            'message' => 'No se puede eliminar el usuario porque tiene notas asociadas. Elimine primero las notas.'
        ]);
        exit();
    }
    
    // Eliminar usuario
    $sql = "DELETE FROM usuarios WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        echo json_encode([
            'status' => 'success', 
            'message' => 'Usuario "' . $usuario['nombre'] . '" eliminado correctamente'
        ]);
    } else {
        echo json_encode([
            'status' => 'error', 
            'message' => 'No se pudo eliminar el usuario'
        ]);
    }
} catch(PDOException $e) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Error del servidor: ' . $e->getMessage()
    ]);
}
?>