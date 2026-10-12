<?php
session_start();
include_once('conexao.php');

if (isset($_GET['aula_id']) && isset($_GET['curso_id']) && isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $aula_id = mysqli_real_escape_string($conn, $_GET['aula_id']);
    $curso_id = mysqli_real_escape_string($conn, $_GET['curso_id']);

    // Insere na tabela de progresso (IGNORE evita erro se o aluno clicar duas vezes)
    $sql = "INSERT IGNORE INTO progresso_aulas (usuario_id, aula_id, curso_id) VALUES ('$usuario_id', '$aula_id', '$curso_id')";
    
    mysqli_query($conn, $sql);
    
    // Redireciona de volta para a mesma aula
    header("Location: aula.php?id=$curso_id&aula=$aula_id");
    exit();
}
?>