<?php
session_start();

// Verifica se o utilizador está logado e se é professor ou admin
if (!isset($_SESSION['logado']) || ($_SESSION['nivel'] !== 'professor' && $_SESSION['nivel'] !== 'admin')) {
    // Se não for professor, expulsa para o login ou dashboard de aluno
    header("Location: login.html?erro=acesso_negado");
    exit();
}

include_once('conexao.php');

// Consulta para buscar dúvidas, nomes dos alunos e títulos das aulas
$sql = "SELECT d.*, u.nome AS aluno_nome, a.titulo AS aula_titulo 
        FROM duvidas d
        JOIN usuarios u ON d.usuario_id = u.id
        JOIN aulas a ON d.aula_id = a.id
        WHERE d.resposta IS NULL 
        ORDER BY d.data_envio DESC";

$res_duvidas = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <title>Central de Dúvidas | EducaTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; color: #1e293b; padding: 20px; }
        .container { max-width: 800px; margin: auto; }
        .duvida-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 20px; border-left: 5px solid #2563eb; }
        .meta { font-size: 0.8rem; color: #64748b; margin-bottom: 10px; }
        .pergunta { font-weight: 500; margin-bottom: 15px; display: block; }
        textarea { width: 100%; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; font-family: inherit; resize: vertical; }
        .btn-responder { background: #2563eb; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; margin-top: 10px; font-weight: 600; }
        .btn-responder:hover { background: #1d4ed8; }
        .vazio { text-align: center; color: #94a3b8; margin-top: 50px; }
    </style>
</head>
<body>
<a href="dashboard_professor.php" style="text-decoration: none; color: #2563eb; font-weight: 600;">← Voltar ao Painel Principal</a>
    <div class="container">
        <h2>📩 Dúvidas Pendentes</h2>
        <p>Aqui estão as questões que os teus alunos enviaram recentemente.</p>

        <?php if(mysqli_num_rows($res_duvidas) > 0): ?>
            <?php while($d = mysqli_fetch_assoc($res_duvidas)): ?>
                <div class="duvida-card">
                    <div class="meta">
                        <strong><?php echo $d['aluno_nome']; ?></strong> em 
                        <em><?php echo $d['aula_titulo']; ?></em> • 
                        <?php echo date('d/m/Y H:i', strtotime($d['data_envio'])); ?>
                    </div>
                    <span class="pergunta">"<?php echo $d['pergunta']; ?>"</span>
                    
                    <form action="responder_duvida.php" method="POST">
                        <input type="hidden" name="duvida_id" value="<?php echo $d['id']; ?>">
                        <textarea name="resposta" placeholder="Escreve aqui a tua resposta..." required></textarea>
                        <button type="submit" class="btn-responder">Enviar Resposta</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="vazio">
                <p>🎉 Bom trabalho! Não há dúvidas pendentes neste momento.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>