<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../connectionDB/banco.php';

$conn = conectarAoBanco();

if (!$conn) {
    die("Falha na conexão com o banco de dados");
}

$id_aluno = isset($_GET['id']) ? $_GET['id'] : null;
$data_escolhida = isset($_GET['data_escolhida']) ? $_GET['data_escolhida'] : null;

if (!$id_aluno) {
    echo "ID do aluno inválido.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM alunos WHERE id = ?");
$stmt->execute([$id_aluno]);
$aluno = $stmt->fetch(PDO::FETCH_ASSOC);

$stmtDatas = $conn->prepare("SELECT DISTINCT DATE(created_at) AS data FROM antropometria WHERE aluno_id = ? ORDER BY data DESC");
$stmtDatas->execute([$id_aluno]);
$datas = $stmtDatas->fetchAll(PDO::FETCH_COLUMN);

$sqlAntropometria = "SELECT * FROM antropometria WHERE aluno_id = ?";
$paramsAntropometria = [$id_aluno];

if ($data_escolhida) {
    $sqlAntropometria .= " AND DATE(created_at) = ?";
    $paramsAntropometria[] = $data_escolhida;
}

$sqlAntropometria .= " ORDER BY created_at DESC";

$stmtAntropometria = $conn->prepare($sqlAntropometria);
$stmtAntropometria->execute($paramsAntropometria);
$antropometria = $stmtAntropometria->fetch(PDO::FETCH_ASSOC);

$stmtAntropometria = $conn->prepare("SELECT * FROM antropometria WHERE aluno_id = ? AND DATE(created_at) = ?");
$stmtAntropometria->execute([$id_aluno, $data_escolhida]);
$antropometria = $stmtAntropometria->fetch(PDO::FETCH_ASSOC);
$antropometria_id = $antropometria['id'];

$stmtTestesFisicos = $conn->prepare("SELECT * FROM testes_fisicos WHERE aluno_id = ? AND DATE(created_at) = ?");
$stmtTestesFisicos->execute([$id_aluno, $data_escolhida]);
$testesFisicos = $stmtTestesFisicos->fetch(PDO::FETCH_ASSOC);

$stmtAnamneseData = $conn->prepare("SELECT objetivos FROM anamnese WHERE aluno_id = ? AND objetivos IS NOT NULL AND objetivos != '' ORDER BY created_at ASC LIMIT 1");
$stmtAnamneseData->execute([$id_aluno]);
$anamneseObj = $stmtAnamneseData->fetch(PDO::FETCH_ASSOC);
$objetivos = $anamneseObj['objetivos'] ?? 'N/A';

function calcularIdade($data_nascimento) {
    $data_nascimento = new DateTime($data_nascimento);
    $data_atual = new DateTime();
    $idade = $data_atual->diff($data_nascimento)->y;
    return $idade;
}

function calcularDensidadeCorporal($conn, $id_aluno) {
    // Consultar os dados de antropometria do aluno
    $stmtAntropometria = $conn->prepare("SELECT peitoral, abdomem, coxa_medial FROM antropometria WHERE aluno_id = ?");
    $stmtAntropometria->execute([$id_aluno]);
    $antropometria = $stmtAntropometria->fetch(PDO::FETCH_ASSOC);

    // Verificar se há dados de antropometria
    if (!$antropometria) {
        return "Não foram encontrados dados de antropometria para o aluno.";
    }

    // Consultar a data de nascimento do aluno
    $stmtDataNascimento = $conn->prepare("SELECT data_nascimento FROM alunos WHERE id = ?");
    $stmtDataNascimento->execute([$id_aluno]);
    $data_nascimento = $stmtDataNascimento->fetchColumn();

    // Calcular a idade a partir da data de nascimento
    $data_nascimento = new DateTime($data_nascimento);
    $data_atual = new DateTime();
    $idade = $data_atual->diff($data_nascimento)->y;

    // Coeficientes da fórmula
    $constante = 1.109380;
    $coeficiente1 = -0.0008267;
    $coeficiente2 = 0.0000016;
    $coeficiente3 = -0.0002574;

    // Calcular a densidade corporal
    $densidade_corporal = $constante +
                          $coeficiente1 * ($antropometria['peitoral'] + $antropometria['abdomem'] + $antropometria['coxa_medial']) +
                          $coeficiente2 * pow(($antropometria['peitoral'] + $antropometria['abdomem'] + $antropometria['coxa_medial']), 2) +
                          $coeficiente3 * $idade;

    return $densidade_corporal;
}

function calcularPercentualGorduraCorporal($densidade_corporal) {
    // Coeficientes da fórmula de Siri (1961)
    $constante = 4.95;
    $subtrativo = 4.50;

    // Calcular o percentual de gordura corporal
    $percentual_gordura_corporal = (($constante / $densidade_corporal) - $subtrativo) * 100;

    return $percentual_gordura_corporal;
}

$_SESSION['aluno_id'] = $id_aluno;
$_SESSION['data_escolhida'] = $data_escolhida;
$_SESSION['objetivos'] = $objetivos;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Aluno</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-B4dV3bKGWD7BGNl1zMMovMAf1fQ7Xf4e2MlSz9rF4zmz7xllYcP3sSttu7W5oA9bNUqR8AgwsRNXZEjSbeFaR2A==" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../css/dashboard_detalhes.css">
</head>
<body>

    <div class="container">
        <nav class="sidebar">
            <img src="../img/estacio.png" alt="Logo">
            <ul>
                <li class="smooth-hover"><a href="../dashboards/detalhes_aluno.php?id=<?= $id_aluno ?>"><i class="fas fa-arrow-left"></i> Voltar</a>
            </ul>
        </nav>

        <div class="content">
            <h1>Calculadora</h1>

        <form method="post" action="">
            <label for="formula">Selecione a Fórmula:</label>
            <select name="formula" id="formula" required>
                <option value="masculina">Percentual de Gordura Corporal</option>
                <!-- Adicione outras opções conforme necessário -->
            </select>

            <input type="submit" name="submit" value="Calcular">
        </form>

            <table class="student-table">
                <tr>
                    <td class="student-info">
                        <strong>Nome:</strong> <?= ucwords($aluno['nome']) ?>
                    </td>
                </tr>
                <tr>
                    <td class="student-info">
                        <strong>Telefone:</strong>  <?= $aluno['telefone'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="student-info">
                        <strong>Sexo:</strong> <?= $aluno['sexo'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="student-info">
                        <strong>Data de Nascimento:</strong> <?= date('d/m/Y', strtotime($aluno['data_nascimento'])) ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <!-- Adicione quantos espaços você achar adequado -->
                        <strong>Idade:</strong> <?= calcularIdade($aluno['data_nascimento']) ?> anos
                    </td>
                </tr>
                
                <!-- Adicione mais detalhes conforme necessário -->

                <?php if (!empty($data_escolhida)): ?>
                    <!-- Detalhes da Anamnese -->

                    <tr>
                        <th colspan="2" class="texto-centralizado"><h3>Anamnese</h3></th>
                    </tr>

                    <tr>
                        <td><strong>Objetivos:</strong></td>
                        <td><?= $objetivos ?></td>
                    </tr>

                    <!-- Detalhes da Antropometria -->

                    <tr>
                        <th colspan="2" class="texto-centralizado"><h3>Antropometria</h3></th>
                    </tr>
                    <tr>
                        <td><strong>ID:</strong></td>
                        <td> <?= $antropometria_id ?? 'N/A' ?></td>
                    </tr>
                    <tr>
                        <td><strong>Peso:</strong></td>
                        <td><?= $antropometria['peso'] ?? 'N/A' ?></td>
                    </tr>
                    <tr>
                        <td><strong>Percentual de Gordura</strong></td>
                        <td>
                        <?php
                            // Verificar se o formulário foi submetido
                            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
                                if (isset($_POST['formula'])) {
                                $formula = $_POST['formula'];
                                // Aqui você pode adicionar condições para chamar diferentes funções dependendo da opção selecionada
                                    if ($formula == 'masculina') {
                                    // Recuperar o ID do aluno do formulário
                                    $id_aluno = isset($_GET['id']) ? $_GET['id'] : null;

                                    // Chamar a função para calcular a densidade corporal
                                    $densidade_corporal = calcularDensidadeCorporal($conn, $id_aluno);

                                    // Verificar se a densidade corporal foi calculada com sucesso
                                    if (!is_numeric($densidade_corporal)) {
                                        echo "Erro ao calcular a densidade corporal: $densidade_corporal";
                                    } else {
                                    // Calcular o percentual de gordura corporal
                                    $percentual_gordura_corporal = calcularPercentualGorduraCorporal($densidade_corporal);

                                        // Verificar se o percentual de gordura corporal foi calculado com sucesso
                                        if (!is_numeric($percentual_gordura_corporal)) {
                                            echo "Erro ao calcular o percentual de gordura corporal.";
                                        } else {
                                        // Formatar o resultado para exibir no máximo duas casas decimais após a vírgula
                                            $percentual_gordura_corporal_formatado = number_format($percentual_gordura_corporal, 2);

                                            // Exibir o resultado
                                            echo "$percentual_gordura_corporal_formatado%";
                                        }
                                    }
                                } elseif ($formula == 'outra_opcao') {
                                    // Chame outra função aqui ou adicione a lógica necessária
                                }
                                // Adicione mais blocos elseif conforme necessário para outras opções
                                }
                            }
                        ?>

                        </td>
                    </tr>
                    
                <?php endif; ?>
            </table>
        </div>
    </div>

<!-- Script para escolher data do relatório -->
<script>
    function toggleListaDatas() {
        var listaDatas = document.getElementById('listaDatas');
        var setaIcon = document.getElementById('setaIcon');

        if (listaDatas.style.display === 'none' || listaDatas.style.display === '') {
            listaDatas.style.display = 'block';
            setaIcon.classList.remove('fa-chevron-down');
            setaIcon.classList.add('fa-chevron-up');
        } else {
            listaDatas.style.display = 'none';
            setaIcon.classList.remove('fa-chevron-up');
            setaIcon.classList.add('fa-chevron-down');
        }
    }
</script>

</body>
</html>
