<?php
session_start();
$aluno_id = isset($_GET['id']) ? intval($_GET['id']) : null;

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

// Função para calcular o IMC
function calcularIMC($novo_peso, $nova_estatura_cm) {
    // Convertendo a estatura de centímetros para metros
    $nova_estatura = $nova_estatura_cm / 100;
    
    // Calculando o IMC
    $imc = $novo_peso / ($nova_estatura * $nova_estatura);
    
    // Arredondando o IMC para duas casas decimais
    $imc = number_format($imc, 2, '.', '');
    
    return $imc;
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Processar os dados do formulário e inserir a nova antropometria no banco de dados
    $novo_peso = $_POST["novo_peso"];
    $novo_torax = $_POST["novo_torax"];
    $nova_estatura = $_POST["nova_estatura"];
    $nova_cintura = $_POST["nova_cintura"];
    $novo_abdomem = $_POST["novo_abdomem"];
    $novo_quadril = $_POST["novo_quadril"];
    $novo_braco_relaxado_direito = $_POST["novo_braco_relaxado_direito"];
    $novo_braco_relaxado_esquerdo = $_POST["novo_braco_relaxado_esquerdo"];
    $novo_braco_contraido_direito = $_POST["novo_braco_contraido_direito"];
    $novo_braco_contraido_esquerdo = $_POST["novo_braco_contraido_esquerdo"];
    $novo_antebraco_direito = $_POST["novo_antebraco_direito"];
    $novo_antebraco_esquerdo = $_POST["novo_antebraco_esquerdo"];
    $nova_coxa_proximal_direita = $_POST["nova_coxa_proximal_direita"];
    $nova_coxa_proximal_esquerda = $_POST["nova_coxa_proximal_esquerda"];
    $nova_perna_direita = $_POST["nova_perna_direita"];
    $nova_perna_esquerda = $_POST["nova_perna_esquerda"];
    $nova_subescapular = $_POST["nova_subescapular"];
    $novo_triceps = $_POST["novo_triceps"];
    $nova_axilar_medial_vertical = $_POST["nova_axilar_medial_vertical"];
    $novo_biceps = $_POST["novo_biceps"];
    $nova_supra_iliaca_anterior = $_POST["nova_supra_iliaca_anterior"];
    $nova_coxa_proximal = $_POST["nova_coxa_proximal"];
    $nova_supra_iliaca_medial = $_POST["nova_supra_iliaca_medial"];
    $nova_coxa_medial = $_POST["nova_coxa_medial"];
    $novo_peitoral = $_POST["novo_peitoral"];
    $nova_perna = $_POST["nova_perna"];
    $novo_abdominal_vertical = $_POST["novo_abdominal_vertical"];
    $novo_biestiloide = $_POST["novo_biestiloide"];
    $novo_biependicondilar_umeral = $_POST["novo_biependicondilar_umeral"];
    $novo_biependicondilar_femural = $_POST["novo_biependicondilar_femural"];
     // Calcular IMC
    $imc = calcularIMC($novo_peso, $nova_estatura);

    // Insira os dados da nova antropometria no banco de dados
    $stmtNovaAntropometria = $conn->prepare("INSERT INTO antropometria (aluno_id, peso, torax, estatura, cintura, abdomem, quadril, braco_relaxado_direito, braco_relaxado_esquerdo, braco_contraido_direito, braco_contraido_esquerdo, antebraco_direito, antebraco_esquerdo, coxa_proximal_direita, coxa_proximal_esquerda, perna_direita, perna_esquerda, subescapular, triceps, axilar_medial_vertical, biceps, supra_iliaca_anterior, coxa_proximal, supra_iliaca_medial, coxa_medial, peitoral, perna, abdominal_vertical, biestiloide, biependicondilar_umeral, biependicondilar_femural, imc) 
                                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ,? , ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtNovaAntropometria->execute([$aluno_id, $novo_peso, $novo_torax, $nova_estatura, $nova_cintura, $novo_abdomem, $novo_quadril, $novo_braco_relaxado_direito, $novo_braco_relaxado_esquerdo, $novo_braco_contraido_direito, $novo_braco_contraido_esquerdo, $novo_antebraco_direito, $novo_antebraco_esquerdo, $nova_coxa_proximal_direita, $nova_coxa_proximal_esquerda, $nova_perna_direita, $nova_perna_esquerda, $nova_subescapular, $novo_triceps, $nova_axilar_medial_vertical, $novo_biceps, $nova_supra_iliaca_anterior, $nova_coxa_proximal, $nova_supra_iliaca_medial, $nova_coxa_medial, $novo_peitoral, $nova_perna, $novo_abdominal_vertical, $novo_biestiloide, $novo_biependicondilar_umeral, $novo_biependicondilar_femural, $imc]);

    $_SESSION['aluno_id'] = $aluno_id; // Armazene o ID na sessão
    $_SESSION['aluno_nome'] = $aluno_nome; //Armazena o nome do aluno na sessão

    // Redirecione de volta para a página de detalhes do aluno após a inserção da nova antropometria
    header("Location: ../dashboards/detalhes_aluno.php?id=$aluno_id");
    exit;
}
