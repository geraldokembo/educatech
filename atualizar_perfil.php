<?php
session_start();
include_once('conexao.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// FORÇAR EXIBIÇÃO DE ERROS NO MAC
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_SESSION['usuario_id'];
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // TESTE: Vamos ver se o ID e os dados estão corretos
    // Se a página travar aqui e mostrar os dados, a conexão está ok.
    // die("ID: $usuario_id | Nome: $nome | Email: $email"); 

    $sql = "UPDATE usuarios SET nome = '$nome', email = '$email' WHERE id = '$usuario_id'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['usuario_nome'] = $nome;
        header("Location: definicoes.php?atualizado=1");
        exit();
    } else {
        // Se chegar aqui, o MySQL vai dizer o porquê do erro
        die("Erro Crítico no MySQL: " . mysqli_error($conn));
    }
} else {
    die("O formulário não enviou os dados via POST corretamente.");
}
?>