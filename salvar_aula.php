<?php
session_start();

// Verifica se o utilizador está logado e se é professor ou admin
if (!isset($_SESSION['logado']) || ($_SESSION['nivel'] !== 'professor' && $_SESSION['nivel'] !== 'admin')) {
    // Se não for professor, expulsa para o login ou dashboard de aluno
    header("Location: login.html?erro=acesso_negado");
    exit();
}

include_once('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $curso_id = mysqli_real_escape_string($conn, $_POST['curso_id']);
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $url_video = mysqli_real_escape_string($conn, $_POST['url_video']);
    $ordem = mysqli_real_escape_string($conn, $_POST['ordem']);
    $nome_pdf = "";

    // Lógica de Upload do PDF
    if (isset($_FILES['material_pdf']) && $_FILES['material_pdf']['error'] == 0) {
        $extensao = pathinfo($_FILES['material_pdf']['name'], PATHINFO_EXTENSION);
        
        if (strtolower($extensao) == 'pdf') {
            $novo_nome = uniqid() . ".pdf"; // Nome único para não sobrescrever
            $diretorio = "uploads/materiais/";
            
            // Cria a pasta se não existir
            if (!is_dir($diretorio)) {
                mkdir($diretorio, 0777, true);
            }

            if (move_uploaded_file($_FILES['material_pdf']['tmp_name'], $diretorio . $novo_nome)) {
                $nome_pdf = $novo_nome;
            }
        }
    }

    // Inserção no Banco de Dados
    $sql = "INSERT INTO aulas (curso_id, titulo, url_video, ordem, material_pdf) 
            VALUES ('$curso_id', '$titulo', '$url_video', '$ordem', '$nome_pdf')";

    if (mysqli_query($conn, $sql)) {
        header("Location: admin_aulas.php?sucesso=1");
    } else {
        echo "Erro ao salvar: " . mysqli_error($conn);
    }
}