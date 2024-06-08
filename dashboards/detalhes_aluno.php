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

$stmtDatas = $conn->prepare("SELECT DISTINCT DATE(created_at) AS data FROM anamnese WHERE aluno_id = ? ORDER BY data DESC");
$stmtDatas->execute([$id_aluno]);
$datas = $stmtDatas->fetchAll(PDO::FETCH_COLUMN);

$sqlAnamnese = "SELECT * FROM anamnese WHERE aluno_id = ?";
$paramsAnamnese = [$id_aluno];

if ($data_escolhida) {
    $sqlAnamnese .= " AND DATE(created_at) = ?";
    $paramsAnamnese[] = $data_escolhida;
}

$sqlAnamnese .= " ORDER BY created_at DESC";

$stmtDatas = $conn->prepare("SELECT DISTINCT DATE(created_at) AS data FROM antropometria WHERE aluno_id = ? ORDER BY data DESC");
$stmtDatas->execute([$id_aluno]);
$datasAntropometria = $stmtDatas->fetchAll(PDO::FETCH_COLUMN);

$sqlAntropometria = "SELECT * FROM antropometria WHERE aluno_id = ?";
$paramsAntropometria = [$id_aluno];

if ($data_escolhida) {
    $sqlAntropometria .= " AND DATE(created_at) = ?";
    $paramsAntropometria[] = $data_escolhida;
}

$sqlAntropometria .= " ORDER BY created_at DESC";

$stmtAnamnese = $conn->prepare($sqlAnamnese);
$stmtAnamnese->execute($paramsAnamnese);
$anamnese = $stmtAnamnese->fetch(PDO::FETCH_ASSOC);

$stmtAntropometria = $conn->prepare("SELECT * FROM antropometria WHERE aluno_id = ? AND DATE(created_at) = ?");
$stmtAntropometria->execute([$id_aluno, $data_escolhida]);
$antropometria = $stmtAntropometria->fetch(PDO::FETCH_ASSOC);

$stmtTestesFisicos = $conn->prepare("SELECT * FROM testes_fisicos WHERE aluno_id = ? AND DATE(created_at) = ?");
$stmtTestesFisicos->execute([$id_aluno, $data_escolhida]);
$testesFisicos = $stmtTestesFisicos->fetch(PDO::FETCH_ASSOC);

$stmtAnamneseData = $conn->prepare("SELECT objetivos FROM anamnese WHERE aluno_id = ? AND objetivos IS NOT NULL AND objetivos != '' ORDER BY created_at ASC LIMIT 1");
$stmtAnamneseData->execute([$id_aluno]);
$anamneseObj = $stmtAnamneseData->fetch(PDO::FETCH_ASSOC);
$objetivos = $anamneseObj['objetivos'] ?? 'N/A';

function calcularIdade($data_nascimento)
{
    $data_nascimento = new DateTime($data_nascimento);
    $data_atual = new DateTime();
    $idade = $data_atual->diff($data_nascimento)->y;
    return $idade;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../css/dashboard_detalhes.css">
    <link rel="stylesheet" href="../css/style_calc.css">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">

</head>

<body>

    <div class="container">

        <div class="content">
            <h2>Detalhes do Aluno</h2>

            <table class="student-table">
                <tr>
                    <td class="student-info">
                        <strong>Nome:</strong> <?= ucwords($aluno['nome']) ?>
                    </td>
                </tr>
                <tr>
                    <td class="student-info">
                        <strong>Telefone:</strong> <?= $aluno['telefone'] ?>
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

                <?php if (!empty($data_escolhida)) : ?>
                   
                    <!-- Adicione mais detalhes conforme necessário -->
                <?php endif; ?>
            </table>

            <div class="navBar">
                <div class="cont">
                    <h4>Exames :</h4>
                    <li class="smooth-hover"><a href="../inspection/nova_anamnese.php?id=<?= $aluno['id'] ?>"><i class="fa-regular fa-clipboard"></i>Anamnese</a></li>
                    <li class="smooth-hover"><a href="../inspection/nova_antropometria.php?id=<?= $aluno['id'] ?>"><i class="fa-regular fa-clipboard"></i>Antropometria</a></li>
                    <li class="smooth-hover"><a href="../inspection/novo_testesFisicos.php?id=<?= $aluno['id'] ?>"><i class="fa-regular fa-clipboard"></i>Testes Físicos</a></li>
                </div>
                <div class="cont">
                    <h4>Relatorios :</h4>
                    <li class="smooth-hover"><a href="javascript:void(0);" onclick="toggleListaDatas('listaDatasRelatorio', 'setaIconRelatorio');"><i class="fas fa-file-alt"></i> Relatório Parcial</a></li>
                    <li class="smooth-hover"><a href="../relatorio/relatorio_completo.php?id=<?= $aluno['id'] ?>"><i class="fas fa-file-alt"></i> Relatório Completo</a>

                </div>
                <div class="cont">
                    <h4>Outros :</h4>
                    <li class="smooth-hover" id="Calculadora" style="z-index: 2; position: relative;">

                        <a href="javascript:void(0);" id="calc" style="display: flex; align-items: center;">
                            <i class="fa-solid fa-calculator"></i> Calculadora
                            <!-- <i id="setaIconCalculadora" class="fas fa-chevron-down" style="margin-left: 5px;"></i> -->
                        </a>
                        <!-- <ul id="listaDatasCalculadora" style="display:none; text-align: left; padding: 0; margin: 0; z-index: 1; list-style: none;">
                            <?php
                            foreach ($datas as $data) {
                                $data_formatada = date('d/m/Y', strtotime($data));
                                echo "<li style='margin: 5px; margin-right: 20px;'><a href=\"../dashboards/calculadora.php?id=" . $aluno['id'] . "&data_escolhida=" . $data . "\">" . $data_formatada . "</a></li>";
                            }
                            ?>
                        </ul> -->
                    </li>
                    <li class="smooth-hover"><a href="../delete/deleteAluno.php?id=<?= $aluno['id']; ?>" onclick="return confirmarExclusao(event, <?= $aluno['id']; ?>)"><i class="fas fa-trash-alt" style="color: red;"></i>Excluir aluno</a></li>
                    <li class="smooth-hover"><a href="../dashboards/dashboard.php"><i class="fas fa-arrow-left"></i> Voltar</a>
                </div>
            </div>

        </div>

        <div id="calculadora" class="hiden">
            <div class="data_form">
            <select name="Data" id="selectData">
                    <option value="">Data</option>
                    <?php
                    foreach ($datasAntropometria as $data) {
                        $data_formatada = date('d/m/Y', strtotime($data));
                        echo "<option value=\"$data\">$data_formatada</option>";
                    }
                    ?>
                </select>

                <select name="formula" id="selectFormula">
                    <option value="">Formulas</option>
                        <?php if (strtolower($aluno['sexo']) === "masculino") : ?>
                        <option value="percentual_gordura_masculina">Percentual de Gordura Corporal Masculina</option>
                        <option value="percentual_gordura_meninos">Percentual de Gordura Corporal Meninos (8-18 anos)</option>
                    <?php elseif (strtolower($aluno['sexo']) === "feminino") : ?>
                        <option value="percentual_gordura_feminina">Percentual de Gordura Corporal Feminina</option>
                        <option value="percentual_gordura_meninas">Percentual de Gordura Corporal Meninas (8-18 anos)</option>
                    <?php else : ?>
                        <option value="percentual_gordura_masculina">Percentual de Gordura Corporal Masculina</option>
                        <option value="percentual_gordura_feminina">Percentual de Gordura Corporal Feminina</option>
                        <option value="percentual_gordura_meninos">Percentual de Gordura Corporal Meninos (8-18 anos)</option>
                        <option value="percentual_gordura_meninas">Percentual de Gordura Corporal Meninas (8-18 anos)</option>
                    <?php endif; ?>
                </select>

            </div>

            <h4 id="resultadoPercentualGordura">Resultados :</h4>

            <div class="btn">
                <button>Salvar</button>
            </div>
        </div>

    </div>

    <script>
        document.querySelector('.btn button').addEventListener('click', function() {
        var selectedFormula = document.getElementById('selectFormula').value;
        var selectedData = document.getElementById('selectData').value;

        if (selectedFormula && selectedData) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'salvar_calculo.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert(xhr.responseText);
                    // Aqui você pode adicionar qualquer outra ação após salvar os dados, se necessário
                } else {
                    alert('Erro ao salvar os dados.');
                }
            };
            xhr.send('id_aluno=<?= $id_aluno ?>&formula=' + selectedFormula + '&data=' + selectedData);
        } else {
            alert("Por favor, selecione uma data e uma fórmula.");
        }
    });
    </script>

<script>
        document.getElementById('selectFormula').addEventListener('change', function() {
            var selectedFormula = this.value;
            var selectedData = document.getElementById('selectData').value;

            if (selectedFormula && selectedData) {
                alert("Data selecionada: " + selectedData + "\nFórmula selecionada: " + selectedFormula);

                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'calcular.php?id=<?= $id_aluno ?>&formula=' + selectedFormula + '&data=' + selectedData, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var resultado = xhr.responseText;
                        document.getElementById('resultadoPercentualGordura').innerText = "Percentual de Gordura Corporal: " + resultado + "%";
                    }
                };
                xhr.send();
            } else {
                alert("Por favor, selecione uma data e uma fórmula.");
            }
        });
    </script>

    <script>
        function confirmarExclusao(event, alunoId) {
            var resposta = confirm("Tem certeza que deseja excluir este aluno?");
            if (!resposta) {
                window.location.replace("../dashboards/detalhes_aluno.php?id=<?= $aluno['id']; ?>");
                return false;
            }
            return true;
        }

        // Impede a propagação do evento de clique da tr para os elementos filhos
        document.querySelectorAll('.delete-container').forEach(function(container) {
            container.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        });
    </script>

    <!-- Script para escolher data do relatório -->
    <script>
        function toggleListaDatas(listaId, iconeId) {
            var listaDatas = document.getElementById(listaId);
            var setaIcon = document.getElementById(iconeId);

            if (listaDatas.style.display === 'none') {
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
    <!-- modal calc -->
    <script>
        let calc = document.querySelector("#calc")

        calc.addEventListener("click", () => {
            document.querySelector("#calculadora").classList.toggle("calculadora")
            document.querySelector("#calculadora").classList.toggle("hiden")
        })
    </script>

</body>

</html>