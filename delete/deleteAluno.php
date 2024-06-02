<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    // Conectar ao banco de dados (substitua com suas credenciais)
    include '../connectionDB/banco.php';
    $conn = conectarAoBanco();

    // Verificar se a conexão foi estabelecida com sucesso
    if (!$conn) {
        die("Falha na conexão com o banco de dados");
    }

    // Obter o ID do aluno a ser excluído
    $aluno_id = $_GET["id"];

    // Preparar e executar a consulta para excluir dados do aluno e tabelas relacionadas
    $stmt1 = $conn->prepare("DELETE FROM testes_fisicos WHERE aluno_id = ?");
    $stmt2 = $conn->prepare("DELETE FROM antropometria WHERE aluno_id = ?");
    $stmt3 = $conn->prepare("DELETE FROM anamnese WHERE aluno_id = ?");
    $stmt4 = $conn->prepare("DELETE FROM alunos WHERE id = ?");

    $conn->beginTransaction();

    try {
        $stmt1->execute([$aluno_id]);
        $stmt2->execute([$aluno_id]);
        $stmt3->execute([$aluno_id]);
        $stmt4->execute([$aluno_id]);

        $conn->commit();
        
        // Redirecionar de volta à página inicial após a exclusão
        header("Location: ../dashboards/dashboard.php?id=" . $_SESSION['usuario_id']);
        exit();
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Erro ao excluir aluno: " . $e->getMessage();
    }

    // Fechar a conexão com o banco de dados
    $conn = null;
} else {
    // Se não houver ID, redirecione para a página inicial ou uma página de erro
    header("Location: ../dashboards/dashboard.php?id=" . $_SESSION['usuario_id']);
    exit();
}
?>
