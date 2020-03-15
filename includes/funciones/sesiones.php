<?php
function autenticacion() {
    if (!revisar_usuario()) {
        header('Location: index.php');
        exit();
    }
}

function revisar_usuario() {
    return isset($_SESSION['login']);
}

session_start();
autenticacion();

?>