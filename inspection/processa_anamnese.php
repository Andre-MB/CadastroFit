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
    // Processar os dados do formulário e inserir a nova anamnese no banco de dados
    $objetivos = $_POST["objetivos"];
    $nova_diabetes = $_POST["nova_diabetes"];
    $nova_cardiopatia = $_POST["nova_cardiopatia"];
    $nova_hipertensao = $_POST["nova_hipertensao"];
    $nova_outras_doencas = $_POST['nova_outras_doencas'];
    $nova_doencas_cronicas_outros_descricao = $_POST['nova_doencas_cronicas_outros_descricao'];
    $novo_fumante = $_POST['novo_fumante'];
    $nova_bebidas_alcoolicas = $_POST['nova_bebidas_alcoolicas'];
    $novo_exercicio_regular = $_POST['novo_exercicio_regular'];
    $novo_exercicio_frequencia = $_POST['novo_exercicio_frequencia'];
    $novo_exercicio_tipo = $_POST['novo_exercicio_tipo'];
    $novo_medicamentos = $_POST['novo_medicamentos'];
    $novo_medicamentos_descricao = $_POST['novo_medicamentos_descricao'];
    $nova_cirurgia = $_POST['nova_cirurgia'];
    $nova_cirurgia_descricao = $_POST['nova_cirurgia_descricao'];
    $novo_historico_diabetes = $_POST['novo_historico_diabetes'];
    $novo_historico_cardiopatia = $_POST['novo_historico_cardiopatia'];
    $novo_historico_hipertensao = $_POST['novo_historico_hipertensao'];
    $novo_historico_cancer = $_POST['novo_historico_cancer'];
    $novo_historico_outros = $_POST['novo_historico_outros'];
    $novo_historico_descricao = $_POST['novo_historico_descricao'];
    $novo_problemas_osteoarticulares = $_POST['novo_problemas_osteoarticulares'];
    $novo_problemas_osteoarticulares_descricao = $_POST['novo_problemas_osteoarticulares_descricao'];

    $_SESSION['aluno_id'] = $aluno_id; // Armazene o ID na sessão
    $_SESSION['aluno_nome'] = $aluno_nome; //Armazena o nome do aluno na sessão

    // Insira os dados da nova anamnese no banco de dados
    $stmtNovaAnamnese = $conn->prepare("INSERT INTO anamnese (aluno_id, objetivos, diabetes, cardiopatia, hipertensao, outras_doencas, doencas_cronicas_outros_descricao, fumante, bebidas_alcoolicas, exercicio_regular, exercicio_frequencia, exercicio_tipo, medicamentos, medicamentos_descricao, cirurgia, cirurgia_descricao, historico_diabetes, historico_cardiopatia, historico_hipertensao, historico_cancer, historico_outros, historico_descricao, problemas_osteoarticulares, problemas_osteoarticulares_descricao) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtNovaAnamnese->execute([$aluno_id, $objetivos, $nova_diabetes, $nova_cardiopatia, $nova_hipertensao, $nova_outras_doencas, $nova_doencas_cronicas_outros_descricao, $novo_fumante, $nova_bebidas_alcoolicas, $novo_exercicio_regular, $novo_exercicio_frequencia, $novo_exercicio_tipo, $novo_medicamentos, $novo_medicamentos_descricao, $nova_cirurgia, $nova_cirurgia_descricao, $novo_historico_diabetes, $novo_historico_cardiopatia, $novo_historico_hipertensao, $novo_historico_cancer, $novo_historico_outros, $novo_historico_descricao, $novo_problemas_osteoarticulares, $novo_problemas_osteoarticulares_descricao]);
    
    // Redirecione de volta para a página de detalhes do aluno após a inserção da nova anamnese
    header("Location: ../dashboards/detalhes_aluno.php?id=$aluno_id");
    exit;
}
?>
