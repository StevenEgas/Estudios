<?php
if (isset($_POST['numero1']) && isset($_POST['numero2'])) {
    $numero1 = $_POST['numero1'];
    $numero2 = $_POST['numero2'];
    $resultado = $numero1 + $numero2;
    echo "El resultado de $numero1 + $numero2 = $resultado";
} else {
    echo "Error: Faltan datos";
}
?>