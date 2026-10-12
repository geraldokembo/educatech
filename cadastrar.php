<?php
// Ativa a exibição de erros para sabermos exatamente o que correu mal
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include_once('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Captura e limpa os dados
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $senha = $_POST['senha']; 
    $codigo_recebido = isset($_POST['codigo_invite']) ? mysqli_real_escape_string($conn, $_POST['codigo_invite']) : '';

    // 2. Código secreto para o LISPA
    $codigo_secreto_professor = "LISPA2026"; 

    // 3. Define o nível
    $nivel = 'aluno'; 
    if ($codigo_recebido === $codigo_secreto_professor) {
        $nivel = 'professor';
    }

    // 4. Verifica se o email já existe
    $check_email = mysqli_query($conn, "SELECT id FROM usuarios WHERE email = '$email'");
    if (mysqli_num_rows($check_email) > 0) {
        header("Location: cadastro.html?erro=email_existe");
        exit();
    }

    // 5. Insere na base de dados
    $sql = "INSERT INTO usuarios (nome, email, senha, nivel_acesso) 
            VALUES ('$nome', '$email', '$senha', '$nivel')";

    if (mysqli_query($conn, $sql)) {
        // IMPORTANTE: O destino aqui tem de ser o ficheiro novo (login.php)
        header("Location: logar.php?sucesso=1");
        exit(); // O exit() é obrigatório depois do header
    } else {
        echo "Erro ao inserir: " . mysqli_error($conn);
    }
} else {
    header("Location: cadastro.html");
    exit();
}
?>