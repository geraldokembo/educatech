<?php
session_start();

// Verifica se o utilizador está logado e se é professor ou admin
if (!isset($_SESSION['logado']) || ($_SESSION['nivel'] !== 'professor' && $_SESSION['nivel'] !== 'admin')) {
    // Se não for professor, expulsa para o login ou dashboard de aluno
    header("Location: login.html?erro=acesso_negado");
    exit();
}

include_once('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = mysqli_real_escape_string($conn, $_POST['duvida_id']);
    $resposta = mysqli_real_escape_string($conn, $_POST['resposta']);
    $data_agora = date('Y-m-d H:i:s');

    $sql = "UPDATE duvidas SET 
            resposta = '$resposta', 
            data_resposta = '$data_agora' 
            WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: painel_duvidas.php?sucesso=1");
    } else {
        echo "Erro ao responder: " . mysqli_error($conn);
    }
}