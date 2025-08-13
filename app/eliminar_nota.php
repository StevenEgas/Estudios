<?php
header('Content-Type: application/json');
require_once '../conexion/bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    
    if ($id <= 0) {
        echo json_encode([
            'success' => false, 
            'message' => 'ID de nota inválido'
        ]);
        exit();
    }
    
    try {
        // Primero verificar si la nota existe
        $checkQuery = "SELECT id FROM notas WHERE id = :id";
        $checkStmt = $pdo->prepare($checkQuery);
        $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() === 0) {
            echo json_encode([
                'success' => false, 
                'message' => 'La nota no existe o ya fue eliminada'
            ]);
            exit();
        }
        
        // Eliminar la nota
        $query = "DELETE FROM notas WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true, 
                'message' => 'Nota eliminada correctamente',
                'data' => ['id' => $id]
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Error al eliminar la nota de la base de datos'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false, 
            'message' => 'Error del servidor: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Método no válido o ID no proporcionado'
    ]);
}
?>
