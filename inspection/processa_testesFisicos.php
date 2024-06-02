<?php
session_start();
$aluno_id = isset($_GET['id']) ? $_GET['id'] : null;

include '../connectionDB/banco.php';

// Verifique se o ID do aluno é válido
if (!$aluno_id) {
    echo "ID do aluno inválido.";
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conecta ao banco de dados (substitua com suas credenciais)
$conn = conectarAoBanco();

// Verifica se a conexão foi estabelecida com sucesso
if (!$conn) {
    die("Falha na conexão com o banco de dados");
}

// Consulta o banco de dados para obter os detalhes do aluno
$stmt = $conn->prepare("SELECT id, nome, sexo, telefone FROM alunos WHERE id = ?");
$stmt->execute([$aluno_id]);
$aluno = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $novo_banco_de_wells = $_POST["novo_banco_de_wells"];
    $nova_distancia_percorrida = $_POST["nova_distancia_percorrida"];
    $nova_fc_max = $_POST["nova_fc_max"];

    $stmtNovotesteFisico = $conn->prepare("INSERT INTO testes_fisicos (aluno_id, banco_de_wells, distancia_percorrida, fc_max) VALUES (?, ?, ?, ?)");
    $stmtNovotesteFisico->execute([$aluno_id, $novo_banco_de_wells, $nova_distancia_percorrida, $nova_fc_max]);

    $_SESSION['aluno_id'] = $aluno_id; // Armazene o ID na sessão

    header("Location: ../dashboards/detalhes_aluno.php?id=$aluno_id");
    exit;
}