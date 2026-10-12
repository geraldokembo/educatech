<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include_once('conexao.php');

// Verifica se o usuário está logado (usando a tua variável 'logado')
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['logado'])) {
    
    $usuario_id = $_SESSION['usuario_id'];
    $curso_id = mysqli_real_escape_string($conn, $_POST['curso_id']);
    $aula_id = mysqli_real_escape_string($conn, $_POST['aula_id']); // NOVO: Captura o ID da aula
    $pergunta = mysqli_real_escape_string($conn, $_POST['duvida']);

    // SQL ATUALIZADO: Agora inclui a aula_id
    $sql = "INSERT INTO duvidas (curso_id, aula_id, usuario_id, pergunta, data_envio) 
            VALUES ('$curso_id', '$aula_id', '$usuario_id', '$pergunta', NOW())";

    if (mysqli_query($conn, $sql)) {
        // Redireciona de volta para a aula específica para o aluno ver a sua pergunta publicada
        header("Location: aula.php?id=$curso_id&aula=$aula_id");
        exit();
    } else {
        echo "Erro ao enviar dúvida: " . mysqli_error($conn);
    }
} else {
    // Se não estiver logado ou não for POST, manda para o login
    header("Location: login.html");
    exit();
}
?>