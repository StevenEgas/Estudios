<?php
header('Content-Type: application/json');
require_once '../conexion/bd.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    if ($id <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'ID de nota inválido'
        ]);
        exit();
    }
    
    try {
        $query = "SELECT 
                    notas.id,
                    usuarios.nombre AS usuario_nombre, 
                    materias.nombre AS materia_nombre, 
                    notas.n1, 
                    notas.n2, 
                    notas.n3, 
                    notas.promedio,
                    notas.usuario_id,
                    notas.materia_id
                  FROM notas
                  JOIN usuarios ON notas.usuario_id = usuarios.id 
                  JOIN materias ON notas.materia_id = materias.id
                  WHERE notas.id = :id";
        
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $nota = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($nota) {
            // Formatear los datos
            $nota['n1'] = floatval($nota['n1']);
            $nota['n2'] = floatval($nota['n2']);
            $nota['n3'] = floatval($nota['n3']);
            $nota['promedio'] = floatval($nota['promedio']);
            
            echo json_encode([
                'success' => true,
                'nota' => $nota
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Nota no encontrada'
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
        'message' => 'ID no proporcionado'
    ]);
}
?>
