<?php
if (isset($_POST['numero'])) {
    $numero = $_POST['numero'];
    echo "<h6>Tabla del $numero:</h6>";
    for ($i = 1; $i <= 10; $i++) {
        $resultado = $numero * $i;
        echo "$numero x $i = $resultado<br>";
    }
} else {
    echo "Error: Falta el número";
}
?>