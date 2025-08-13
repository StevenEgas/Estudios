<?php
header('Content-Type: application/json');
require_once 'bd.php';

try {
    $query = "SELECT id, nombre FROM usuarios ORDER BY nombre";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($usuarios);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
