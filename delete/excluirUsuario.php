<?php
session_start();
include '../connectionDB/banco.php';

// Verifica se o usuário está autenticado e é administrador
if (!isset($_SESSION['usuario_id']) || !$_SESSION['isadmin']) {
    http_response_code(403); // Código de status 403 - Proibido
    exit;
}

$conn = conectarAoBanco();

// Verifica se o ID do usuário a ser excluído foi fornecido

if (isset($_GET['id'])) {
    $idUsuario = $_GET['id'];

    // Consulta o banco de dados para obter os detalhes do usuário
    $stmt = $conn->prepare("SELECT id, nome, isadmin FROM usuarios WHERE id = :id");
    $stmt->bindParam(':id', $idUsuario);
    $stmt->execute();
    $detalhesUsuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$detalhesUsuario) {
        echo '<script>';
        echo 'alert("Usuário não encontrado.");';
        echo '</script>';
        exit;
    }

    // Verifica se o usuário é o último administrador
    $queryAdmins = $conn->prepare("SELECT COUNT(*) as total FROM usuarios WHERE isadmin = :isadmin");
    $queryAdmins->bindParam(':isadmin', $detalhesUsuario['isadmin'], PDO::PARAM_BOOL);
    $queryAdmins->execute();
    $totalAdmins = $queryAdmins->fetch(PDO::FETCH_ASSOC)['total'];

    if ($_SESSION['isadmin'] && $detalhesUsuario['isadmin'] && $totalAdmins <= 1) {

    // Adiciona um script JavaScript para exibir a mensagem
    echo '<script>';
    echo 'alert("Você não pode excluir o último administrador.");';
    echo 'window.location.href = "../dashboards/detalhes_usuario.php?id=' . $idUsuario . '";';
    echo '</script>';
    exit;
}

    // Exclui o usuário do banco de dados (substitua esta linha pelo código real de exclusão)
    $stmtExcluir = $conn->prepare("DELETE FROM usuarios WHERE id = :id");
    $stmtExcluir->bindParam(':id', $idUsuario);
    $stmtExcluir->execute();

    // Adiciona um script JavaScript para exibir a mensagem
    echo '<script>';
    echo 'alert("Exclusão feita com sucesso.");';
    echo 'window.location.href = "../dashboards/perfil.php?id=' . $_SESSION['usuario_id'] . '";';
    echo '</script>';

    exit;
} else {
    echo '<script>';
    echo 'alert("ID do usuário não fornecido.");';
    echo '</script>';
    exit;
}

