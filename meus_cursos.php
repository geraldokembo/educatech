<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['logado'])) { header("Location: login.html"); exit(); }

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <title>Meus Cursos | EducaTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>


        /* Reutilizamos o teu Design System */
        body { font-family: 'Inter', sans-serif; background: #f8fafc; margin: 0; display: flex; }
        .sidebar { width: 250px; background: white; border-right: 1px solid #e2e8f0; min-height: 100vh; padding: 20px; }
        .main { flex: 1; padding: 40px; }
        
        /* Filtros */
        .filters { display: flex; gap: 15px; margin-bottom: 30px; }
        .filter-btn { padding: 8px 18px; border-radius: 20px; border: 1px solid #e2e8f0; background: white; cursor: pointer; transition: 0.3s; }
        .filter-btn.active { background: #2563eb; color: white; border-color: #2563eb; }

        /* Grid de Cursos */
        .courses-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px; }
        .course-card { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .course-img { width: 100%; height: 150px; background: #cbd5e1; object-fit: cover; }
        .course-info { padding: 20px; }
        .course-title { font-weight: 600; font-size: 1.1rem; margin-bottom: 10px; }


       
       
    </style>
    
</head>
<body>
    
    <aside class="sidebar">
        <h2 style="color:#2563eb">EducaTech</h2>
        <nav style="display:flex; flex-direction:column; gap:10px;">
            <a href="dashboard.php" style="text-decoration:none; color:#64748b;">🏠 Dashboard</a>
            <a href="meus_cursos.php" style="text-decoration:none; color:#2563eb; font-weight:600;">📚 Meus Cursos</a>
        </nav>
        
    </aside>
    
    <main class="main">
    <?php
    if(isset($_GET['sucesso'])) echo "Inscrição realizada com sucesso!";
    ?>
    <h1>Meus Cursos</h1>
    
    <div class="filters">
        <button class="filter-btn active">Todos</button>
    </div>

    <div class="courses-grid">
        <?php
        include_once('conexao.php');
        $usuario_id = $_SESSION['usuario_id'];

        // SQL REAL: Seleciona os cursos onde o usuário está inscrito
        $sql = "SELECT cursos.id, cursos.nome, cursos.imagem, cursos.instrutor 
                FROM inscricoes 
                JOIN cursos ON inscricoes.curso_id = cursos.id 
                WHERE inscricoes.usuario_id = '$usuario_id'";
        
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            // Se houver cursos, este loop cria os cards automaticamente
            while($curso = mysqli_fetch_assoc($result)) {
                ?>
                <div class="course-card">
                    <div class="course-img" style="background-image: url('assets/<?php echo $curso['imagem']; ?>'); background-size: cover;"></div>
                    <div class="course-info">
                        <div class="course-title"><?php echo $curso['nome']; ?></div>
                        <p style="color:#64748b; font-size:0.9rem;">Estudante: <?php echo $curso['instrutor']; ?></p>
                        <a href="aula.php?id=<?php echo $curso['id']; ?>" class="btn-course" style="display:block; text-align:center; background:#2563eb; color:white; padding:10px; border-radius:8px; text-decoration:none;">Aceder ao Curso</a>
                    </div>
                </div>
                <?php
            }
        } else {
            // MENSAGEM REAL: Caso não esteja inscrito em nada
            echo "
            <div style='grid-column: 1 / -1; text-align: center; padding: 50px; background: white; border-radius: 15px;'>
                <h3>Ainda não tens nenhum curso inscrito.</h3>
                <p>Explora o nosso catálogo e começa a aprender hoje mesmo!</p>
                <br>
                <a href='explorar.php' style='background:#2563eb; color:white; padding:12px 25px; border-radius:8px; text-decoration:none;'>Explorar Cursos</a>
            </div>";
        }
        ?>
    </div>
</main>
</body>
</html>