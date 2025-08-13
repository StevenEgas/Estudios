<?php
header('Content-Type: application/json');
require_once 'bd.php';

try {
    $query = "SELECT id, nombre FROM materias ORDER BY nombre";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $materias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($materias);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
