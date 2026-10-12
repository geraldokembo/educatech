<?php
session_start();
include_once('conexao.php');

// --- 1. LÓGICA DE PROCESSAMENTO (O MOTOR) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $senha = $_POST['senha'];

    $sql = "SELECT id, nome, senha, nivel_acesso FROM usuarios WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $usuario = mysqli_fetch_assoc($result);

        if (password_verify($senha, $usuario['senha']) || $senha == $usuario['senha']) {
            $_SESSION['logado'] = true;
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['nivel'] = $usuario['nivel_acesso'];

            if ($usuario['nivel_acesso'] == 'professor' || $usuario['nivel_acesso'] == 'admin') {
                header("Location: dashboard_professor.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();
        } else {
            header("Location: logar.php?erro=senha_incorreta");
            exit();
        }
    } else {
        header("Location: logar.php?erro=usuario_nao_encontrado");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EducaTech</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { text-align: center; color: #333; }
        .alert { padding: 12px; border-radius: 8px; margin-bottom: 1rem; text-align: center; font-size: 14px; font-weight: bold; }
        .alert-success { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        input { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background-color: #2563eb; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; margin-top: 10px; }
        button:hover { background-color: #1d4ed8; }
        .footer-links { text-align: center; margin-top: 15px; font-size: 14px; }
    </style>
</head>
<body>

    <div class="login-card">
        <h2>Entrar na EducaTech</h2>

        <?php if (isset($_GET['sucesso'])): ?>
            <div class="alert alert-success">✅ Conta criada com sucesso!</div>
        <?php endif; ?>

        <?php if (isset($_GET['erro'])): ?>
            <div class="alert alert-error">
                ❌ <?php 
                    $e = $_GET['erro'];
                    if($e == 'senha_incorreta') echo "Palavra-passe incorreta.";
                    if($e == 'usuario_nao_encontrado') echo "Este email não está registado.";
                    if($e == 'acesso_negado') echo "Acesso restrito a professores.";
                ?>
            </div>
        <?php endif; ?>

        <form action="logar.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Palavra-passe" required>
            <button type="submit">Entrar</button>
        </form>

        <div class="footer-links">
            Ainda não tem conta? <a href="cadastro.html">Cadastrar-se</a>
        </div>
    </div>

    <script>
        // Esconde os alertas automaticamente após 5 segundos
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(a => a.style.display = 'none');
        }, 5000);
    </script>
</body>
</html>