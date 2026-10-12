<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include_once('conexao.php');
if (!isset($_SESSION['logado'])) { header("Location: login.html"); exit(); }

$usuario_id = $_SESSION['usuario_id'];

// Busca dados atuais do usuário para preencher o formulário
$query = mysqli_query($conn, "SELECT nome, email FROM usuarios WHERE id = '$usuario_id'");
$dados = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <title>Definições | EducaTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; margin: 0; display: flex; }
        .sidebar { width: 250px; background: white; border-right: 1px solid #e2e8f0; min-height: 100vh; padding: 20px; }
        .main { flex: 1; padding: 40px; max-width: 800px; }
        
        .settings-card { background: white; padding: 30px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 25px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #475569; }
        input { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; }
        
        .btn-save { background: #2563eb; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight: 600; transition: 0.3s; }
        .btn-save:hover { background: #1d4ed8; }
        
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <h2 style="color:#2563eb">EducaTech</h2>
        <nav style="display:flex; flex-direction:column; gap:10px;">
            <a href="dashboard.php" style="text-decoration:none; color:#64748b;">🏠 Dashboard</a>
            <a href="meus_cursos.php" style="text-decoration:none; color:#64748b;">📚 Meus Cursos</a>
            <a href="explorar.php" style="text-decoration:none; color:#64748b;">🔎 Explorar</a>
            <a href="definicoes.php" style="text-decoration:none; color:#2563eb; font-weight:600;">⚙️ Definições</a>
        </nav>
    </aside>

    <main class="main">
        <h1>Definições da Conta</h1>

        <?php if(isset($_GET['atualizado'])): ?>
            <div class="alert success">Perfil atualizado com sucesso!</div>
        <?php endif; ?>

        <div class="settings-card">
            <h3>Informações Pessoais</h3>
            <form action="atualizar_perfil.php" method="POST">
                <div class="form-group">
                    <label>Nome Completo</label>
                    <input type="text" name="nome" value="<?php echo $dados['nome']; ?>" required>
                </div>
                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" name="email" value="<?php echo $dados['email']; ?>" required>
                </div>
                <button type="submit" class="btn-save">Guardar Alterações</button>
            </form>
        </div>

        <div class="settings-card">
            <h3>Segurança</h3>
            <form action="atualizar_senha.php" method="POST">
                <div class="form-group">
                    <label>Nova Senha</label>
                    <input type="password" name="nova_senha" placeholder="Deixe em branco para não alterar">
                </div>
                <button type="submit" class="btn-save" style="background:#64748b;">Atualizar Senha</button>
            </form>
        </div>
    </main>
</body>
</html>