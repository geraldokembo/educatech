<?php
session_start();

// Verifica se o utilizador está logado e se é professor ou admin
if (!isset($_SESSION['logado']) || ($_SESSION['nivel'] !== 'professor' && $_SESSION['nivel'] !== 'admin')) {
    // Se não for professor, expulsa para o login ou dashboard de aluno
    header("Location: login.html?erro=acesso_negado");
    exit();
}

include_once('conexao.php');

// Segurança: Verificar se é professor
//if ($_SESSION['nivel'] !== 'professor') { header("Location: dashboard.php"); exit(); }

$professor_nome = $_SESSION['usuario_nome'] ?? 'Docente';

// Estatísticas Rápidas para o Professor
$total_cursos = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM cursos"))['total'];
$total_duvidas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM duvidas WHERE resposta IS NULL"))['total'];
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <title>Painel do Professor | EducaTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #1e293b; margin: 0; }
        header { background: #2563eb; color: white; padding: 20px 40px; display: flex; justify-content: space-between; align-items: center; }
        .container { padding: 40px; max-width: 1200px; margin: auto; }
        .welcome { margin-bottom: 30px; }
        .grid-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        
        .card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; transition: 0.3s; text-decoration: none; color: inherit; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border-color: #2563eb; }
        
        .card h3 { margin-top: 0; color: #2563eb; display: flex; align-items: center; gap: 10px; }
        .card p { color: #64748b; font-size: 0.95rem; line-height: 1.5; }
        .badge { background: #ef4444; color: white; padding: 2px 8px; border-radius: 20px; font-size: 0.8rem; }
    </style>
</head>
<body>

    <header>
        <div style="font-size: 1.5rem; font-weight: 700;">EducaTech <span style="font-weight: 400; font-size: 1rem; opacity: 0.8;">| Docente</span></div>
        <a href="logout.php" style="color: white; text-decoration: none; font-size: 0.9rem;">Sair</a>
    </header>

    <div class="container">
        <div class="welcome">
            <h1>Olá, Professor/a <?php echo $professor_nome; ?>! 👋</h1>
            <p>O que pretendes gerir hoje na plataforma?</p>
        </div>

        <div class="grid-cards">
            <a href="admin_aulas.php" class="card">
                <h3>📚 Carregar Aulas</h3>
                <p>Adiciona novos vídeos, títulos e materiais de apoio (PDF) aos teus cursos ativos.</p>
                <small style="color: #2563eb; font-weight: 600;">Aceder Gestor →</small>
            </a>

            <a href="painel_duvidas.php" class="card">
                <h3>📩 Responder Dúvidas 
                    <?php if($total_duvidas > 0): ?>
                        <span class="badge"><?php echo $total_duvidas; ?></span>
                    <?php endif; ?>
                </h3>
                <p>Vê as perguntas que os teus alunos deixaram nas aulas e envia o teu feedback.</p>
                <small style="color: #2563eb; font-weight: 600;">Abrir Inbox →</small>
            </a>

            <div class="card" style="opacity: 0.7; cursor: not-allowed;">
                <h3>📊 Desempenho (Brevemente)</h3>
                <p>Acompanha quantos alunos concluíram cada aula e a média de progresso dos cursos.</p>
            </div>
        </div>
    </div>

</body>
</html>