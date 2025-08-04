<?php
if (isset($_POST['numero'])) {
    $numero = $_POST['numero'];
    if ($numero % 2 == 0) {
        echo "El número $numero es PAR";
    } else {
        echo "El número $numero es IMPAR";
    }
} else {
    echo "Error: Falta el número";
}
?>