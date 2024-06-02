<?php
session_start();
$id_aluno = $_SESSION['aluno_id'];
$data_escolhida = $_SESSION['data_escolhida'] ?? null;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../connectionDB/banco.php';

$conn = conectarAoBanco();

// Obtém o ID do aluno a partir do parâmetro na URL
$id_aluno = isset($_GET['id']) ? $_GET['id'] : null;

// Se o ID do aluno não estiver disponível, redirecione para a página anterior
if (!$id_aluno) {
    header("Location: dashboard.php");
    exit;
}

// Função para formatar as datas
function formatarData($data) {
    return date('d/m/Y', strtotime($data));
}

// Consulta o banco de dados para obter os detalhes do aluno
$stmt = $conn->prepare("SELECT * FROM alunos WHERE id = ?");
$stmt->execute([$id_aluno]);
$aluno = $stmt->fetch(PDO::FETCH_ASSOC);

// Consulta para obter informações da anamnese
$stmtAnamnese = $conn->prepare("SELECT * FROM anamnese WHERE aluno_id = ?");
$stmtAnamnese->execute([$id_aluno]);
$anamneses = $stmtAnamnese->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obter informações da antropometria
$stmtAntropometria = $conn->prepare("SELECT * FROM antropometria WHERE aluno_id = ?");
$stmtAntropometria->execute([$id_aluno]);
$antropometrias = $stmtAntropometria->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obter informações dos testes físicos
$stmtTestesFisicos = $conn->prepare("SELECT * FROM testes_fisicos WHERE aluno_id = ?");
$stmtTestesFisicos->execute([$id_aluno]);
$testesFisicos = $stmtTestesFisicos->fetchAll(PDO::FETCH_ASSOC);

$dates = [];
$imcValues = [];

foreach ($antropometrias as $antropometria) {
    $dates[] = formatarData($antropometria['created_at']);
    $imcValues[] = $antropometria['imc'];
}

?>