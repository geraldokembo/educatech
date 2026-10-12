<?php
session_start();
include_once('conexao.php');

// 1. VERIFICAÇÃO DE SEGURANÇA
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

// 2. DEFINIÇÃO DAS VARIÁVEIS BASE
$usuario_id = $_SESSION['usuario_id']; 
$curso_id = isset($_GET['id']) ? $_GET['id'] : null;
$aula_id = isset($_GET['aula']) ? $_GET['aula'] : null;

// 3. BUSCAR DADOS DO CURSO
$res_curso = mysqli_query($conn, "SELECT * FROM cursos WHERE id = '$curso_id'");
$curso = mysqli_fetch_assoc($res_curso);

if (!$curso) {
    $curso = ['nome' => 'Curso não encontrado', 'descricao' => ''];
}

// 4. BUSCAR DADOS DA AULA ATUAL
if ($aula_id) {
    $res_aula = mysqli_query($conn, "SELECT * FROM aulas WHERE id = '$aula_id' AND curso_id = '$curso_id'");
} else {
    $res_aula = mysqli_query($conn, "SELECT * FROM aulas WHERE curso_id = '$curso_id' ORDER BY ordem ASC LIMIT 1");
}
$aula_atual = mysqli_fetch_assoc($res_aula);

// 5. VERIFICAR SE A AULA FOI CONCLUÍDA
$foi_concluida = false;
if ($aula_atual) {
    $a_id = $aula_atual['id'];
    $verificar_sql = "SELECT id FROM progresso_aulas WHERE usuario_id = '$usuario_id' AND aula_id = '$a_id'";
    $res_verificar = mysqli_query($conn, $verificar_sql);
    $foi_concluida = (mysqli_num_rows($res_verificar) > 0);
}

// 6. CÁLCULO DE PROGRESSO
$percentagem = 0;
$total_res = mysqli_query($conn, "SELECT COUNT(id) as total FROM aulas WHERE curso_id = '$curso_id'");
$total_aulas = mysqli_fetch_assoc($total_res)['total'];
if ($total_aulas > 0) {
    $conc_res = mysqli_query($conn, "SELECT COUNT(id) as concluidas FROM progresso_aulas WHERE usuario_id = '$usuario_id' AND curso_id = '$curso_id'");
    $concluidas = mysqli_fetch_assoc($conc_res)['concluidas'];
    $percentagem = round(($concluidas / $total_aulas) * 100);
}
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <title><?php echo $aula_atual ? $aula_atual['titulo'] : 'Aula'; ?> | EducaTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #0f172a; color: white; margin: 0; display: flex; flex-direction: column; height: 100vh; }
        header { background: #1e293b; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #334155; }
        .back-btn { color: #94a3b8; text-decoration: none; font-size: 0.9rem; }
        .aula-container { display: flex; flex: 1; overflow: hidden; }
        .video-area { flex: 3; padding: 30px; overflow-y: auto; background: #0f172a; }
        .video-placeholder { width: 100%; aspect-ratio: 16/9; background: black; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(0,0,0,0.5); overflow: hidden; }
        .playlist { flex: 1; background: #1e293b; border-left: 1px solid #334155; overflow-y: auto; padding: 20px; }
        .playlist h3 { font-size: 1.1rem; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #334155; }
        .aula-item { display: flex; align-items: center; gap: 15px; padding: 12px; border-radius: 8px; cursor: pointer; transition: 0.2s; margin-bottom: 8px; background: #334155; text-decoration: none; color: white; }
        .aula-item.active { background: #2563eb; }
        .aula-number { background: rgba(0,0,0,0.2); width: 25px; height: 25px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 0.8rem; }
        .btn-material { display: inline-flex; align-items: center; gap: 8px; background: #334155; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; margin-top: 20px; border: 1px solid #475569; }
        
        /* Estilo para as Respostas */
        .resposta-professor { margin-left: 30px; margin-top: 15px; background: #1e293b; padding: 15px; border-radius: 8px; border-left: 4px solid #10b981; }
    </style>
</head>
<body>

    <header>
        <a href="meus_cursos.php" class="back-btn">← Voltar para Meus Cursos</a>
        <div style="font-weight: 600;"><?php echo $curso['nome']; ?></div>
        <div style="font-size: 0.8rem; color: #94a3b8;">Progresso: <?php echo $percentagem; ?>%</div>
    </header>

    <div class="aula-container">
        <main class="video-area">
            <div class="video-placeholder">
                <?php if ($aula_atual && !empty($aula_atual['url_video'])): ?>
                    <iframe width="100%" height="100%" src="<?php echo $aula_atual['url_video']; ?>" frameborder="0" allowfullscreen></iframe>
                <?php else: ?>
                    <span style="color: #475569;">Vídeo não disponível</span>
                <?php endif; ?>
            </div>

            <div style="margin-top: 20px;">
                <?php if ($aula_atual): ?>
                    <?php if (!$foi_concluida): ?>
                        <a href="concluir_aula.php?aula_id=<?php echo $aula_atual['id']; ?>&curso_id=<?php echo $curso_id; ?>" 
                           style="background: #10b981; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block;">
                           ✅ Marcar como Concluída
                        </a>
                    <?php else: ?>
                        <span style="background: #334155; color: #10b981; padding: 12px 25px; border-radius: 8px; font-weight: 600; display: inline-block; border: 1px solid #10b981;">
                           ⭐ Aula Concluída
                        </span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <div class="video-info">
                <h1><?php echo $aula_atual['titulo'] ?? 'Selecione uma aula'; ?></h1>
                <p><?php echo $curso['descricao']; ?></p>
                
                <?php if ($aula_atual && !empty($aula_atual['material_pdf'])): ?>
                    <a href="uploads/materiais/<?php echo $aula_atual['material_pdf']; ?>" class="btn-material" download>📂 Baixar Material de Apoio (PDF)</a>
                <?php endif; ?>

                <div class="duvidas-section" style="margin-top: 50px; border-top: 1px solid #334155; padding-top: 30px;">
                    <h3>Dúvidas sobre esta aula</h3>
                    
                    <form action="enviar_duvida.php" method="POST" style="margin-bottom: 40px;">
                        <input type="hidden" name="curso_id" value="<?php echo $curso_id; ?>">
                        <input type="hidden" name="aula_id" value="<?php echo $aula_atual['id'] ?? ''; ?>">
                        <textarea name="duvida" placeholder="Escreve aqui a tua questão..." required 
                            style="width: 100%; background: #1e293b; border: 1px solid #334155; border-radius: 8px; color: white; padding: 15px; min-height: 100px; font-family: inherit;"></textarea>
                        <button type="submit" style="background: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 6px; margin-top: 10px; cursor: pointer; font-weight: 600;">
                            Enviar Pergunta
                        </button>
                    </form>

                    <div class="lista-duvidas">
                        <?php
                        // Nota: Adicionámos os campos resposta e data_resposta à query
                        $duvidas_query = mysqli_query($conn, "SELECT duvidas.*, usuarios.nome 
                                                             FROM duvidas 
                                                             JOIN usuarios ON duvidas.usuario_id = usuarios.id 
                                                             WHERE duvidas.aula_id = '{$aula_atual['id']}' 
                                                             ORDER BY duvidas.data_envio DESC");

                        if(mysqli_num_rows($duvidas_query) > 0) {
                            while($d = mysqli_fetch_assoc($duvidas_query)) {
                                echo '
                                <div style="background: #1e293b; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #2563eb;">
                                    <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
                                        <strong style="color:#2563eb;">'.$d['nome'].'</strong>
                                        <small style="color:#64748b;">'.date('d/m/Y H:i', strtotime($d['data_envio'])).'</small>
                                    </div>
                                    <p style="margin:0; font-size:0.95rem; color:#cbd5e1;">'.$d['pergunta'].'</p>';

                                    // VERIFICA SE HÁ RESPOSTA DO PROFESSOR
                                    if(!empty($d['resposta'])) {
                                        echo '
                                        <div class="resposta-professor">
                                            <div style="margin-bottom:8px;">
                                                <strong style="color:#10b981; font-size:0.85rem;">👨‍🏫 Resposta do Professor</strong>
                                                <small style="color:#64748b; margin-left:10px;">'.date('d/m/Y H:i', strtotime($d['data_resposta'])).'</small>
                                            </div>
                                            <p style="margin:0; font-size:0.9rem; color:#94a3b8;">'.$d['resposta'].'</p>
                                        </div>';
                                    }

                                echo '</div>';
                            }
                        } else {
                            echo "<p style='color:#64748b;'>Ainda não há perguntas nesta aula.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </main>

        <aside class="playlist">
            <h3>Conteúdo do Curso</h3>
            <?php
            $aulas_sidebar = mysqli_query($conn, "SELECT id, titulo, ordem FROM aulas WHERE curso_id = '$curso_id' ORDER BY ordem ASC");
            while($item = mysqli_fetch_assoc($aulas_sidebar)) {
                $active = ($aula_atual && $item['id'] == $aula_atual['id']) ? 'active' : '';
                echo "
                <a href='aula.php?id=$curso_id&aula={$item['id']}' class='aula-item $active'>
                    <div class='aula-number'>{$item['ordem']}</div>
                    <div>{$item['titulo']}</div>
                </a>";
            }
            ?>
        </aside>
    </div>
</body>
</html>