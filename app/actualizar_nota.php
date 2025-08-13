<?php
header('Content-Type: application/json');
require_once '../conexion/bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $usuario_id = isset($_POST['usuario_id']) ? intval($_POST['usuario_id']) : 0;
    $materia_id = isset($_POST['materia_id']) ? intval($_POST['materia_id']) : 0;
    $n1 = isset($_POST['n1']) ? floatval($_POST['n1']) : 0;
    $n2 = isset($_POST['n2']) ? floatval($_POST['n2']) : 0;
    $n3 = isset($_POST['n3']) ? floatval($_POST['n3']) : 0;
    
    // Validaciones
    if ($id <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'ID de nota inválido'
        ]);
        exit();
    }
    
    if ($usuario_id <= 0 || $materia_id <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Debe seleccionar usuario y materia válidos'
        ]);
        exit();
    }
    
    // Validar notas (0-20)
    if ($n1 < 0 || $n1 > 20 || $n2 < 0 || $n2 > 20 || $n3 < 0 || $n3 > 20) {
        echo json_encode([
            'success' => false,
            'message' => 'Las notas deben estar entre 0 y 20'
        ]);
        exit();
    }
    
    try {
        // Verificar que la nota existe
        $checkQuery = "SELECT id FROM notas WHERE id = :id";
        $checkStmt = $pdo->prepare($checkQuery);
        $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() == 0) {
            echo json_encode([
                'success' => false,
                'message' => 'La nota no existe'
            ]);
            exit();
        }
        
        // Verificar duplicado (mismo usuario y materia, pero diferente ID)
        $duplicateQuery = "SELECT id FROM notas WHERE usuario_id = :usuario_id AND materia_id = :materia_id AND id != :id";
        $duplicateStmt = $pdo->prepare($duplicateQuery);
        $duplicateStmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $duplicateStmt->bindParam(':materia_id', $materia_id, PDO::PARAM_INT);
        $duplicateStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $duplicateStmt->execute();
        
        if ($duplicateStmt->rowCount() > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Ya existe una nota para este usuario y materia'
            ]);
            exit();
        }
        
        // Calcular promedio
        $promedio = ($n1 + $n2 + $n3) / 3;
        
        // Actualizar nota
        $query = "UPDATE notas SET 
                    usuario_id = :usuario_id,
                    materia_id = :materia_id,
                    n1 = :n1,
                    n2 = :n2,
                    n3 = :n3,
                    promedio = :promedio
                  WHERE id = :id";
        
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(':materia_id', $materia_id, PDO::PARAM_INT);
        $stmt->bindParam(':n1', $n1, PDO::PARAM_STR);
        $stmt->bindParam(':n2', $n2, PDO::PARAM_STR);
        $stmt->bindParam(':n3', $n3, PDO::PARAM_STR);
        $stmt->bindParam(':promedio', $promedio, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Nota actualizada exitosamente',
                'data' => [
                    'id' => $id,
                    'promedio' => round($promedio, 2)
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al actualizar la nota'
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
        'message' => 'Método no permitido. Use POST.'
    ]);
}
?>
