<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['logado'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | EducaTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --bg: #f8fafc;
            --text: #1e293b;
            --white: #ffffff;
            --progress-bg: #e2e8f0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            margin: 0;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: var(--white);
            border-right: 1px solid #e2e8f0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .logo {
            font-weight: 600;
            font-size: 1.5rem;
            color: var(--primary);
            margin-bottom: 30px;
        }

        .nav-link {
            text-decoration: none;
            color: var(--text);
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: 0.3s;
        }

        .nav-link:hover, .nav-link.active {
            background: #eff6ff;
            color: var(--primary);
        }

        /* Conteúdo Principal */
        .main-content {
            flex: 1;
            padding: 40px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .welcome-msg h1 { font-size: 1.8rem; margin: 0; }
        .welcome-msg p { color: #64748b; margin: 5px 0 0; }

        /* Grid de Cursos */
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            padding: 20px 0;
        }

        .course-card {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        .course-card:hover { transform: translateY(-5px); }

        .course-img {
            width: 100%;
            height: 160px;
            background-size: cover;
            background-position: center;
            background-color: #cbd5e1;
        }

        .course-info { padding: 20px; }

        /* Barra de Progresso Real */
        .progress-container {
            background: var(--progress-bg);
            height: 8px;
            border-radius: 10px;
            margin: 15px 0 5px 0;
            overflow: hidden;
        }

        .progress-fill {
            background: var(--primary);
            height: 100%;
            transition: width 0.5s ease-in-out;
        }

        .progress-text {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 500;
        }

        .logout-btn { color: #ef4444; margin-top: auto; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="logo">EducaTech</div>
        <nav style="display:flex; flex-direction:column;">
            <a href="dashboard.php" class="nav-link active">🏠 Início</a>
            <a href="meus_cursos.php" class="nav-link">📚 Meus Cursos</a>
            <a href="explorar.php" class="nav-link">🔎 Explorar</a>
            <a href="definicoes.php" class="nav-link">⚙️ Definições</a>
        </nav>
        <a href="logout.php" class="nav-link logout-btn">🚪 Sair</a>
    </aside>

    <main class="main-content">
        <header>
            <div class="welcome-msg">
                <h1>Olá, <?php echo explode(' ', $_SESSION['usuario_nome'])[0]; ?>! 👋</h1>
                <p>É bom ter-te de volta. O que vamos aprender hoje?</p>
            </div>
            <div class="user-avatar">
                <div style="width:45px; height:45px; background:var(--primary); color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:600;">
                    <?php echo substr($_SESSION['usuario_nome'], 0, 1); ?>
                </div>
            </div>
        </header>

        <section>
            <h2 style="font-size: 1.3rem; margin-bottom: 20px;">Os Teus Cursos Recentes</h2>
            <div class="courses-grid">
                <?php
                include_once('conexao.php');
                $usuario_id = $_SESSION['usuario_id'];

                $sql = "SELECT cursos.id, cursos.nome, cursos.instrutor, cursos.imagem 
                        FROM inscricoes 
                        JOIN cursos ON inscricoes.curso_id = cursos.id 
                        WHERE inscricoes.usuario_id = '$usuario_id' 
                        ORDER BY inscricoes.data_inscricao DESC 
                        LIMIT 3";
                
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while($curso = mysqli_fetch_assoc($result)) {
                        $c_id = $curso['id'];

                        // 1. Calcular total de aulas deste curso
                        $total_res = mysqli_query($conn, "SELECT COUNT(id) as total FROM aulas WHERE curso_id = '$c_id'");
                        $total_aulas = mysqli_fetch_assoc($total_res)['total'];

                        // 2. Calcular aulas concluídas pelo aluno
                        $concluidas_res = mysqli_query($conn, "SELECT COUNT(id) as concluidas FROM progresso_aulas WHERE usuario_id = '$usuario_id' AND curso_id = '$c_id'");
                        $concluidas = mysqli_fetch_assoc($concluidas_res)['concluidas'];

                        // 3. Percentagem final
                        $percentagem = ($total_aulas > 0) ? round(($concluidas / $total_aulas) * 100) : 0;

                        // Imagem dinâmica (Capa padrão se estiver vazio)
                        $capa = !empty($curso['imagem']) ? 'assets/'.$curso['imagem'] : 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=600&q=80';
                        ?>
                        <div class="course-card">
                            <div class="course-img" style="background-image: url('<?php echo $capa; ?>');"></div>
                            <div class="course-info">
                                <h3 style="margin: 0 0 5px 0; font-size: 1.1rem;"><?php echo $curso['nome']; ?></h3>
                                <p style="color: #64748b; font-size: 0.85rem; margin: 0;">Prof: <?php echo $curso['instrutor']; ?></p>
                                
                                <div class="progress-container">
                                    <div class="progress-fill" style="width: <?php echo $percentagem; ?>%;"></div>
                                </div>
                                <div class="progress-text"><?php echo $percentagem; ?>% completo</div>

                                <div style="margin-top: 15px;">
                                    <a href="aula.php?id=<?php echo $curso['id']; ?>" style="text-decoration: none; color: var(--primary); font-weight: 600; font-size: 0.9rem;">Aceder ao curso →</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "
                    <div style='grid-column: 1 / -1; padding: 30px; background: #eff6ff; border-radius: 12px; color: #1e40af; text-align: center; border: 1px dashed #2563eb;'>
                        Ainda não tens inscrições ativas. <br><br>
                        <a href='explorar.php' style='background: var(--primary); color:white; padding: 10px 20px; border-radius: 8px; text-decoration:none;'>Explorar Catálogo</a>
                    </div>";
                }
                ?>
            </div>
        </section>
    </main>

</body>
</html>