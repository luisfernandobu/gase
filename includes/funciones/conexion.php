<?php
$bd = "bdsaloneventos";
$usuario = "root";
$pw = "rootroot";
$host = "localhost";

$conn = new mysqli($host, $usuario, $pw, $bd);
if($conn->connect_error) {
    echo ($conn->connect_error);
}
$conn->set_charset('utf8');

?>