<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();
include_once('conexao.php');
if (!isset($_SESSION['logado'])) { 
    header("Location: logar.php?erro=sessao_expirada"); 
    exit(); 
}
$usuario_id = $_SESSION['usuario_id'];


?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <title>Explorar Cursos | EducaTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; margin: 0; display: flex; }
        .sidebar { width: 250px; background: white; border-right: 1px solid #e2e8f0; min-height: 100vh; padding: 20px; }
        .main { flex: 1; padding: 40px; }
        .courses-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .course-card { background: white; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; }
        .course-info { padding: 15px; }
        .btn-inscrever { display: block; text-align: center; background: #2563eb; color: white; padding: 10px; border-radius: 6px; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <h2 style="color:#2563eb">EducaTech</h2>
        <nav style="display:flex; flex-direction:column; gap:10px;">
            <a href="dashboard.php" style="text-decoration:none; color:#64748b;">🏠 Dashboard</a>
            <a href="meus_cursos.php" style="text-decoration:none; color:#64748b;">📚 Meus Cursos</a>
            <a href="explorar.php" style="text-decoration:none; color:#2563eb; font-weight:600;">🔎 Explorar</a>
        </nav>
    </aside>

    <main class="main">
        <h1>Explorar Novos Cursos</h1>
        <div class="courses-grid">
            <?php
            // SQL: Seleciona cursos onde o usuário ainda NÃO está inscrito
            $sql = "SELECT * FROM cursos WHERE id NOT IN (SELECT curso_id FROM inscricoes WHERE usuario_id = $usuario_id)";
            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo '
                    <div class="course-card">
                    <div style="height:140px; overflow:hidden;">
                        <img src="assets/'.$row['imagem'].'" alt="'.$row['nome'].'" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                    
                    <div class="course-info">
                        <h3>'.$row['nome'].'</h3>
                        <p style="color:#64748b; font-size:0.85rem;">Instrutor: '.$row['instrutor'].'</p>
                        <a href="inscrever.php?id='.$row['id'].'" class="btn-inscrever">Inscrever-me Agora</a>
                    </div>
                </div>';
                }
            } else {
                echo "<p>Já estás inscrito em todos os nossos cursos! Parabéns!</p>";
            }
            ?>
        </div>
    </main>
    
</body>
</html>