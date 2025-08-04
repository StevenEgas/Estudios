<?php
if (isset($_POST['ano'])) {
    $anoNacimiento = $_POST['ano'];
    $anoActual = date('Y');
    $edad = $anoActual - $anoNacimiento;
    echo "Si naciste en $anoNacimiento, tienes $edad años";
} else {
    echo "Error: Falta el año de nacimiento";
}
?>