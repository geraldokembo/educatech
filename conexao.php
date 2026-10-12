<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = "127.0.0.1";
$user = "root";
$pass = ""; // No XAMPP de Mac, tente deixar vazio. Se não der, tente "root"
$db   = "sistema_cursos";

// Tenta conectar
$conn = new mysqli($host, $user, $pass, $db);

// Se falhar, ele vai gritar o erro aqui:
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
} 
?>