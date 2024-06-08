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

// Se o ID do aluno não estiver disponível, redirecione para a página anterior ou exiba uma mensagem de erro
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
$stmtAnamnese = $conn->prepare("SELECT * FROM anamnese WHERE aluno_id = ? " . ($data_escolhida ? "AND DATE(created_at) = ?" : "") . " ORDER BY created_at DESC LIMIT 1");
$paramsAnamnese = [$id_aluno];
if ($data_escolhida) {
    $paramsAnamnese[] = $data_escolhida;
}
$stmtAnamnese->execute($paramsAnamnese);
$anamnese = $stmtAnamnese->fetch(PDO::FETCH_ASSOC);

// Vamos usar isso para definir o nome do arquivo
$anamneseCreatedAt = $anamnese['created_at'];

// Formate a data de criação no formato desejado (por exemplo, d-m-Y)
$anamneseCreatedAtFormatted = date('d-m-Y', strtotime($anamneseCreatedAt));

// Consulta para obter informações da antropometria
$stmtAntropometria = $conn->prepare("SELECT * FROM antropometria WHERE aluno_id = ? " . ($data_escolhida ? "AND DATE(created_at) = ?" : "") . " ORDER BY created_at DESC LIMIT 1");
$paramsAntropometria = [$id_aluno];
if ($data_escolhida) {
    $paramsAntropometria[] = $data_escolhida;
}
$stmtAntropometria->execute($paramsAntropometria);
$antropometria = $stmtAntropometria->fetch(PDO::FETCH_ASSOC);

// Consulta para obter informações dos testes físicos
$stmtTestesFisicos = $conn->prepare("SELECT * FROM testes_fisicos WHERE aluno_id = ? " . ($data_escolhida ? "AND DATE(created_at) = ?" : "") . " ORDER BY created_at DESC LIMIT 1");
$paramsTestesFisicos = [$id_aluno];
if ($data_escolhida) {
    $paramsTestesFisicos[] = $data_escolhida;
}
$stmtTestesFisicos->execute($paramsTestesFisicos);
$testesFisicos = $stmtTestesFisicos->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório do Aluno</title>
    <link rel="stylesheet" type="text/css" href="../css/relatorio.css">
    <!-- Inclua o jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Inclua a biblioteca html2pdf -->
    <script src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>

    <!-- Script jQuery para enviar o formulário quando a data for alterada -->
    <script>
    $(document).ready(function () {
        // Adicione um ouvinte de eventos ao botão de download em PDF
        $("#downloadPdf").click(function () {
            // Clone o corpo do documento
            var clonedBody = document.body.cloneNode(true);

            // Remova elementos indesejados do clone
            $(clonedBody).find("nav").remove();

            // Estilize o clone para melhorar o layout no PDF
            $(clonedBody).find("body").css({
                "margin": "20px", 
                "padding": "20px", 
                "box-sizing": "border-box"
            });

           // Use a biblioteca html2pdf para gerar o PDF a partir do clone
        html2pdf().from(clonedBody).set({
            filename: 'Relatório_' + <?php echo json_encode($anamneseCreatedAtFormatted); ?> + '.pdf' // Define o nome do arquivo com a data
        }).save();
    });
});
</script>

</head>
<body>

<!-- Navegação -->
<nav>
    <!-- Botão para baixar em PDF -->
    <button type="button" id="downloadPdf">Baixar em PDF</button>

    <button onclick="window.location.href='../dashboards/detalhes_aluno.php?id=<?= $id_aluno ?>'">Voltar</button>
</nav>

<div class="pdf-container">

    <!-- Dados do Aluno -->
    <section class="section">
        <h1>Relatório do Aluno</h1>

        <section class="subsection">
            <h2>Dados Pessoais</h2>
            <table>
                <tr>
                    <td class="with-margin"><strong>Nome:</strong><?= ucwords($aluno['nome']) ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <strong>Data da avaliação:</strong><?=formatarData($anamnese['created_at']) ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <strong>Hora da avaliação:</strong><?= date('H:i', strtotime($anamnese['created_at'])) ?>
                    </td>
                </tr>
                <tr>
                    <td class="with-margin"><strong>Telefone:</strong><?= $aluno['telefone'] ?></td>
                </tr>
                <tr>
                    <td class="with-margin">
                        <strong>Data de Nascimento:</strong><?= date('d/m/Y', strtotime($aluno['data_nascimento'])) ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <!-- Adicione quantos espaços você achar adequado -->
                        <strong class="age-label">Idade:</strong class="age-value"><?= calcularIdade($aluno['data_nascimento']) ?> anos
                    </td>
                    <?php
                        function calcularIdade($dataNascimento) {
                        $dataNascimento = new DateTime($dataNascimento);
                        $agora = new DateTime();
                        $idade = $agora->diff($dataNascimento);

                        return $idade->y;
                        }
                    ?>
                </tr>
            </table>
        </section>

        <!-- Detalhes da Anamnese -->
        <section class="subsection">
            <h2>Anamnese</h2>
            <table>
                <tr>
                    <td class="with-margin"><strong>Objetivos:</strong><?= ucwords($_SESSION['objetivos']) ?></td>
                </tr>
                <tr>
                    <td class="with-margin"><strong>Diabetes:</strong><?= $anamnese['diabetes'] ?? 'N/A' ?></td>
                </tr>
                <tr>
                    <td class="with-margin"><strong>Cardiopatia:</strong><?= $anamnese['cardiopatia'] ?? 'N/A' ?></td>
                </tr>
            </table>
        </section>

        <!-- Detalhes da Antropometria -->
        <section class="subsection">
            <h2>Antropometria</h2>
            <table>
                <tr>
                    <td class="with-margin"><strong>Braço Relaxado Direito:</strong><?= $antropometria['braco_relaxado_direito'] ?? 'N/A' ?></td>
                </tr>
                <tr>
                    <td class="with-margin"><strong>Braço Relaxado Esquerdo:</strong><?= $antropometria['braco_relaxado_esquerdo'] ?? 'N/A' ?></td>
                </tr>
            </table>
        </section>

        <!-- Detalhes dos Testes Físicos -->
        <section class="subsection">
            <h2>Testes Físicos</h2>
            <table>
                <tr>
                    <td class="with-margin"><strong>Banco de Wells:</strong><?= $testesFisicos['banco_de_wells'] ?? 'N/A' ?></td>
                </tr>
            </table>
        </section>

    </section>
</div>