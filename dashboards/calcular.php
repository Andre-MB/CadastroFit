<?php
include '../connectionDB/banco.php';
include 'formulas.php';

$conn = conectarAoBanco();

$id_aluno = isset($_GET['id']) ? $_GET['id'] : null;
$formula = isset($_GET['formula']) ? $_GET['formula'] : null;
$data_escolhida = isset($_GET['data']) ? $_GET['data'] : null;

if (!$id_aluno || !$formula || !$data_escolhida) {
    echo "Dados de entrada inválidos.";
    exit;
}

$stmtAntropometria = $conn->prepare("SELECT * FROM antropometria WHERE aluno_id = ? AND DATE(created_at) = ?");
$stmtAntropometria->execute([$id_aluno, $data_escolhida]);
$antropometria = $stmtAntropometria->fetch(PDO::FETCH_ASSOC);
$id_antropometria = $antropometria ? $antropometria['id'] : null;

if (!$id_antropometria) {
    echo "Nenhuma antropometria encontrada para o aluno e data especificados.";
    exit;
}

echo "Data escolhida: " . $data_escolhida . "\n"; // Log para verificar a data recebida

if ($formula == 'percentual_gordura_masculina') {
    $stmtDataNascimento = $conn->prepare("SELECT data_nascimento FROM alunos WHERE id = ?");
    $stmtDataNascimento->execute([$id_aluno]);
    $data_nascimento = $stmtDataNascimento->fetchColumn();
    $idade = calcularIdade($data_nascimento);

    if ($idade >= 8 && $idade <= 18) {
        $percentual_gordura = calcularPercentualGorduraMeninos($conn, $id_aluno, $data_escolhida);
        echo number_format($percentual_gordura, 2);
    } else {
        $densidade_corporal = calcularDensidadeCorporalMasculina($conn, $id_aluno, $data_escolhida);
        if (is_numeric($densidade_corporal)) {
            $percentual_gordura = calcularPercentualGorduraCorporal($densidade_corporal);
            echo number_format($percentual_gordura, 2);
        } else {
            echo $densidade_corporal; // Erro retornado pela função de densidade corporal
        }
    }
} elseif ($formula == 'percentual_gordura_feminina') {
    $stmtDataNascimento = $conn->prepare("SELECT data_nascimento FROM alunos WHERE id = ?");
    $stmtDataNascimento->execute([$id_aluno]);
    $data_nascimento = $stmtDataNascimento->fetchColumn();
    $idade = calcularIdade($data_nascimento);

    if ($idade >= 8 && $idade <= 18) {
        $percentual_gordura = calcularPercentualGorduraMeninas($conn, $id_aluno, $data_escolhida);
        echo number_format($percentual_gordura, 2);
    } else {
        $densidade_corporal = calcularDensidadeCorporalFeminina($conn, $id_aluno, $data_escolhida);
        if (is_numeric($densidade_corporal)) {
            $percentual_gordura = calcularPercentualGorduraCorporal($densidade_corporal);
            echo number_format($percentual_gordura, 2);
        } else {
            echo $densidade_corporal; // Erro retornado pela função de densidade corporal
        }
    }
} elseif ($formula == 'percentual_gordura_meninos') {
    $percentual_gordura = calcularPercentualGorduraMeninos($conn, $id_aluno, $data_escolhida);
    echo number_format($percentual_gordura, 2);
} elseif ($formula == 'percentual_gordura_meninas') {
    $percentual_gordura = calcularPercentualGorduraMeninas($conn, $id_aluno, $data_escolhida);
    echo number_format($percentual_gordura, 2);
} else {
    echo "Fórmula desconhecida.";
}
?>