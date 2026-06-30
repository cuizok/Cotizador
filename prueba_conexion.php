<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$usuario = "root";
$password = "";
$port = 3306;

$conexion = new mysqli($host, $usuario, $password, "", $port);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

echo "Conexión exitosa al servidor MySQL";
?>