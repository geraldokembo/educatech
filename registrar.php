<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Usa o caminho real da pasta para não haver erro de inclusão
include_once('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se os campos existem antes de processar
    if(isset($_POST['nome'], $_POST['email'], $_POST['senha'])) {
        
        $nome  = mysqli_real_escape_string($conn, $_POST['nome']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $senha_hash = password_hash($_POST['senha'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha_hash')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>
                    alert('Usuário registado com sucesso!');
                    window.location.href = '../login.html';
                  </script>";
        } else {
            echo "Erro no Banco de Dados: " . $conn->error;
        }
    } else {
        echo "Erro: Campos do formulário não encontrados.";
    }
} else {
    echo "Erro: O formulário deve ser enviado via POST.";
}
?>