<?php
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['nivel'] !== 'professor') {
    // Se não existir a variável 'logado' OU se o nível NÃO FOR 'professor'
    // ELE FAZ ISTO:
    header("Location: login.html?erro=acesso_negado"); 
    exit();
}

// Verifica se o utilizador está logado e se é professor ou admin
if (!isset($_SESSION['logado']) || ($_SESSION['nivel'] !== 'professor' && $_SESSION['nivel'] !== 'admin')) {
    // Se não for professor, expulsa para o login ou dashboard de aluno
    header("Location: login.html?erro=acesso_negado");
    exit();
}

include_once('conexao.php');

// Segurança: Só entra se for professor (ajusta conforme o teu sistema de login)
// if ($_SESSION['nivel'] !== 'professor') { header("Location: dashboard.php"); }

$res_cursos = mysqli_query($conn, "SELECT id, nome FROM cursos ORDER BY nome ASC");
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <title>Painel Docente | EducaTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #1e293b; padding: 40px; }
        .admin-card { max-width: 600px; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin: auto; }
        h2 { color: #2563eb; margin-top: 0; }
        .form-group { margin-bottom: 15px; display: flex; flex-direction: column; }
        label { font-weight: 600; margin-bottom: 5px; font-size: 0.9rem; }
        input, select, textarea { padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px; font-family: inherit; }
        .btn-salvar { background: #2563eb; color: white; border: none; padding: 12px; border-radius: 6px; font-weight: 600; cursor: pointer; margin-top: 10px; transition: 0.3s; }
        .btn-salvar:hover { background: #1d4ed8; }
        .alert { padding: 15px; border-radius: 6px; margin-bottom: 20px; display: none; }
        .alert-success { background: #dcfce7; color: #166534; display: block; }
    </style>
</head>
<body>

    <div class="admin-card">
        <h2>🚀 Cadastrar Nova Aula</h2>
        <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 25px;">Preencha os dados abaixo para disponibilizar o conteúdo aos alunos.</p>

        <?php if(isset($_GET['sucesso'])): ?>
            <div class="alert alert-success">✅ Aula publicada com sucesso!</div>
        <?php endif; ?>

        <form action="salvar_aula.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Selecionar Curso:</label>
                <select name="curso_id" required>
                    <option value="">Escolha o curso...</option>
                    <?php while($c = mysqli_fetch_assoc($res_cursos)): ?>
                        <option value="<?php echo $c['id']; ?>"><?php echo $c['nome']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Título da Aula:</label>
                <input type="text" name="titulo" placeholder="Ex: 01. Introdução ao Projeto" required>
            </div>

            <div class="form-group">
                <label>Link do Vídeo (YouTube Embed):</label>
                <input type="text" name="url_video" placeholder="https://www.youtube.com/embed/..." required>
                <small style="color: #94a3b8;">Dica: Use o link de 'Incorporar' do YouTube.</small>
            </div>

            <div class="form-group">
                <label>Ordem de Exibição:</label>
                <input type="number" name="ordem" value="1" min="1">
            </div>

            <div class="form-group">
                <label>Material de Apoio (PDF):</label>
                <input type="file" name="material_pdf" accept=".pdf">
            </div>

            <button type="submit" class="btn-salvar">Publicar Aula agora</button>
            <a href="dashboard.php" style="text-align: center; display: block; margin-top: 15px; color: #64748b; text-decoration: none; font-size: 0.8rem;">← Voltar ao Dashboard</a>
        </form>
    </div>

</body>
</html>