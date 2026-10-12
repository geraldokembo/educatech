<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include_once('conexao.php');

if (!isset($_SESSION['logado'])) { header("Location: login.html"); exit(); }

if (isset($_GET['id'])) {
    $curso_id = mysqli_real_escape_string($conn, $_GET['id']);
    $usuario_id = $_SESSION['usuario_id'];

    // Verifica se já não existe (prevenção dupla)
    $check = mysqli_query($conn, "SELECT id FROM inscricoes WHERE usuario_id = '$usuario_id' AND curso_id = '$curso_id'");

    if (mysqli_num_rows($check) == 0) {
        $sql = "INSERT INTO inscricoes (usuario_id, curso_id) VALUES ('$usuario_id', '$curso_id')";
        
        if (mysqli_query($conn, $sql)) {
            // Sucesso! Redireciona para Meus Cursos
            header("Location: meus_cursos.php?sucesso=1");
        } else {
            echo "Erro ao processar inscrição.";
        }
    } else {
        header("Location: meus_cursos.php");
    }
} else {
    header("Location: explorar.php");
}
?>