<?php
session_start();

include '../connectionDB/banco.php';
include 'formulas.php';

$conn = conectarAoBanco();

$id_aluno = isset($_POST['id_aluno']) ? $_POST['id_aluno'] : null;
$formula = isset($_POST['formula']) ? $_POST['formula'] : null;
$data_escolhida = isset($_POST['data']) ? $_POST['data'] : null;

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

if ($formula == 'percentual_gordura_masculina') {
    $stmtDataNascimento = $conn->prepare("SELECT data_nascimento FROM alunos WHERE id = ?");
    $stmtDataNascimento->execute([$id_aluno]);
    $data_nascimento = $stmtDataNascimento->fetchColumn();
    $idade = calcularIdade($data_nascimento);

    if ($idade >= 8 && $idade <= 18) {
        $percentual_gordura = calcularPercentualGorduraMeninos($conn, $id_aluno, $data_escolhida);
    } else {
        $densidade_corporal = calcularDensidadeCorporalMasculina($conn, $id_aluno, $data_escolhida);
        if (is_numeric($densidade_corporal)) {
            $percentual_gordura = calcularPercentualGorduraCorporal($densidade_corporal);
        } else {
            echo $densidade_corporal; // Erro retornado pela função de densidade corporal
            exit;
        }
    }
} elseif ($formula == 'percentual_gordura_feminina') {
    $stmtDataNascimento = $conn->prepare("SELECT data_nascimento FROM alunos WHERE id = ?");
    $stmtDataNascimento->execute([$id_aluno]);
    $data_nascimento = $stmtDataNascimento->fetchColumn();
    $idade = calcularIdade($data_nascimento);

    if ($idade >= 8 && $idade <= 18) {
        $percentual_gordura = calcularPercentualGorduraMeninas($conn, $id_aluno, $data_escolhida);
    } else {
        $densidade_corporal = calcularDensidadeCorporalFeminina($conn, $id_aluno, $data_escolhida);
        if (is_numeric($densidade_corporal)) {
            $percentual_gordura = calcularPercentualGorduraCorporal($densidade_corporal);
        } else {
            echo $densidade_corporal; // Erro retornado pela função de densidade corporal
            exit;
        }
    }
} elseif ($formula == 'percentual_gordura_meninos') {
    $percentual_gordura = calcularPercentualGorduraMeninos($conn, $id_aluno, $data_escolhida);
} elseif ($formula == 'percentual_gordura_meninas') {
    $percentual_gordura = calcularPercentualGorduraMeninas($conn, $id_aluno, $data_escolhida);
} else {
    echo "Fórmula desconhecida.";
    exit;
}

$percentual_gordura = number_format($percentual_gordura, 2);

$stmt = $conn->prepare("INSERT INTO calculadora (id_aluno, formula, data, resultado_porcentagem) VALUES (?, ?, ?, ?)");
if ($stmt->execute([$id_aluno, $formula, $data_escolhida, $percentual_gordura])) {
    echo "Dados salvos com sucesso!";
} else {
    echo "Erro ao salvar os dados.";
    error_log("Erro ao executar: " . implode(", ", $stmt->errorInfo()));
}
?>
