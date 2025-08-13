<?php
header('Content-Type: application/json');
require_once '../conexion/bd.php';

/* capturar datos del form ingresarnotas.php */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_POST['usuario_id'];
    $materia_id = $_POST['materia_id'];
    $nota_1 = floatval($_POST['nota_1']);
    $nota_2 = floatval($_POST['nota_2']);
    $nota_3 = floatval($_POST['nota_3']);
    
    // Validar que las notas estén entre 0 y 20
    if ($nota_1 < 0 || $nota_1 > 20 || $nota_2 < 0 || $nota_2 > 20 || $nota_3 < 0 || $nota_3 > 20) {
        echo json_encode([
            'success' => false, 
            'message' => 'Las notas deben estar entre 0 y 20'
        ]);
        exit();
    }
    
    // Validar que los IDs sean válidos
    if (empty($usuario_id) || empty($materia_id)) {
        echo json_encode([
            'success' => false, 
            'message' => 'Debe seleccionar un usuario y una materia'
        ]);
        exit();
    }
    
    $promedio = ($nota_1 + $nota_2 + $nota_3) / 3;

    try {
        // Verificar si ya existe una nota para este usuario y materia
        $checkQuery = "SELECT id FROM notas WHERE usuario_id = :usuario_id AND materia_id = :materia_id";
        $checkStmt = $pdo->prepare($checkQuery);
        $checkStmt->bindParam(':usuario_id', $usuario_id);
        $checkStmt->bindParam(':materia_id', $materia_id);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() > 0) {
            echo json_encode([
                'success' => false, 
                'message' => 'Ya existe una nota registrada para este usuario en esta materia'
            ]);
            exit();
        }

        $sql = "INSERT INTO notas (usuario_id, materia_id, n1, n2, n3, promedio) 
                VALUES (:usuario_id, :materia_id, :n1, :n2, :n3, :promedio)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':materia_id', $materia_id);
        $stmt->bindParam(':n1', $nota_1);
        $stmt->bindParam(':n2', $nota_2);
        $stmt->bindParam(':n3', $nota_3);
        $stmt->bindParam(':promedio', $promedio);
        
        if($stmt->execute()){
            echo json_encode([
                'success' => true, 
                'message' => 'Notas guardadas correctamente',
                'data' => [
                    'id' => $pdo->lastInsertId(),
                    'promedio' => round($promedio, 2),
                    'estado' => $promedio >= 14 ? 'APROBADO' : 'DESAPROBADO'
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Error al guardar las notas en la base de datos'
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
        'message' => 'Método de solicitud no válido. Use POST.'
    ]);
}
?>