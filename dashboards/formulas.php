<?php

function calcularIdade($data_nascimento) {
    $data_nascimento = new DateTime($data_nascimento);
    $data_atual = new DateTime();
    $idade = $data_atual->diff($data_nascimento)->y;
    return $idade;
}

function calcularDensidadeCorporalMasculina($conn, $id_aluno) {
    $stmtAntropometria = $conn->prepare("SELECT peitoral, abdomem, coxa_medial FROM antropometria WHERE aluno_id = ?");
    $stmtAntropometria->execute([$id_aluno]);
    $antropometria = $stmtAntropometria->fetch(PDO::FETCH_ASSOC);

    if (!$antropometria) {
        return "Não foram encontrados dados de antropometria para o aluno.";
    }

    $stmtDataNascimento = $conn->prepare("SELECT data_nascimento FROM alunos WHERE id = ?");
    $stmtDataNascimento->execute([$id_aluno]);
    $data_nascimento = $stmtDataNascimento->fetchColumn();

    $idade = calcularIdade($data_nascimento);

    $constante = 1.109380;
    $coeficiente1 = -0.0008267;
    $coeficiente2 = 0.0000016;
    $coeficiente3 = -0.0002574;

    $densidade_corporal = $constante +
                          $coeficiente1 * ($antropometria['peitoral'] + $antropometria['abdomem'] + $antropometria['coxa_medial']) +
                          $coeficiente2 * pow(($antropometria['peitoral'] + $antropometria['abdomem'] + $antropometria['coxa_medial']), 2) +
                          $coeficiente3 * $idade;

    return $densidade_corporal;
}

function calcularDensidadeCorporalFeminina($conn, $id_aluno) {
    $stmtAntropometria = $conn->prepare("SELECT triceps, supra_iliaca_medial, coxa_medial FROM antropometria WHERE aluno_id = ?");
    $stmtAntropometria->execute([$id_aluno]);
    $antropometria = $stmtAntropometria->fetch(PDO::FETCH_ASSOC);

    if (!$antropometria) {
        return "Não foram encontrados dados de antropometria para o aluno.";
    }

    $stmtDataNascimento = $conn->prepare("SELECT data_nascimento FROM alunos WHERE id = ?");
    $stmtDataNascimento->execute([$id_aluno]);
    $data_nascimento = $stmtDataNascimento->fetchColumn();

    $idade = calcularIdade($data_nascimento);

    $constante = 1.0994921;
    $coeficiente1 = -0.0009929;
    $coeficiente2 = 0.0000023;
    $coeficiente3 = -0.0001392;

    $densidade_corporal = $constante +
                          $coeficiente1 * ($antropometria['triceps'] + $antropometria['supra_iliaca_medial'] + $antropometria['coxa_medial']) +
                          $coeficiente2 * pow(($antropometria['triceps'] + $antropometria['supra_iliaca_medial'] + $antropometria['coxa_medial']), 2) +
                          $coeficiente3 * $idade;

    return $densidade_corporal;
}

function calcularPercentualGorduraCorporal($densidade_corporal) {
    $constante = 4.95;
    $subtrativo = 4.50;

    $percentual_gordura_corporal = (($constante / $densidade_corporal) - $subtrativo) * 100;

    return $percentual_gordura_corporal;
}

function calcularPercentualGorduraMeninos($conn, $id_aluno) {
    $stmtAntropometria = $conn->prepare("SELECT triceps, perna FROM antropometria WHERE aluno_id = ?");
    $stmtAntropometria->execute([$id_aluno]);
    $antropometria = $stmtAntropometria->fetch(PDO::FETCH_ASSOC);

    if ($antropometria) {
        $percentual_gordura = (0.735 * ($antropometria['triceps'] + $antropometria['perna']) + 1);
        return $percentual_gordura;
    } else {
        return "Dados antropométricos não encontrados.";
    }
}

function calcularPercentualGorduraMeninas($conn, $id_aluno){
    $stmtAntropometria = $conn->prepare("SELECT triceps, perna FROM antropometria WHERE aluno_id = ?");
    $stmtAntropometria->execute([$id_aluno]);
    $antropometria = $stmtAntropometria->fetch(PDO::FETCH_ASSOC);

    if ($antropometria) {
        $percentual_gordura = (0.610  * ($antropometria['triceps'] + $antropometria['perna']) + 5.1);
        return $percentual_gordura;
    } else {
        return "Dados antropométricos não encontrados.";
    }
}

?>