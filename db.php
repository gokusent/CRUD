<?php
$host = "localhost";
$usuario = "root";
$password = "";
$bbdd = "tareas_db";

try {
    $conexion = new PDO("mysql:host=$host;dbname=$bbdd;charset=utf8", $usuario, $password);

    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>