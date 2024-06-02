<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../connectionDB/banco.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conexão com o banco de dados
    $db = conectarAoBanco();
    // Recuperar os dados do formulário
    $nome = $_POST['nome'];
    $dataNascimento = $_POST['dataNascimento'];
    $telefone = $_POST['telefone'];
    $sexo = $_POST['sexo'];
    $email = $_POST['email'];

    // Verifique se o email já existe no banco de dados
    $stmt = $db->prepare("SELECT id FROM alunos WHERE email = ?");
    $stmt->execute([$email]);
    $existingEmail = $stmt->fetchColumn();

    // Check if the email already exists (you need to implement this logic)
    if ($existingEmail) {
        $_SESSION['error_message'] = "O email já está cadastrado. Por favor, use um email diferente.";
        header("Location: ../cadastroAluno.php");
        exit();

    } else {
        // Inserção na tabela "alunos"
        $stmt = $db->prepare("INSERT INTO alunos (nome, data_nascimento, telefone, sexo, email) 
            VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $dataNascimento, $telefone, $sexo, $email]);

       // Após inserir o aluno com sucesso, obtenha o ID do aluno
       $stmt = $db->prepare("SELECT id FROM alunos WHERE email = ?");
       $stmt->execute([$email]);
       $aluno_id = $stmt->fetchColumn();

       // Armazene o ID na sessão
       $_SESSION['aluno_id'] = $aluno_id;

       // Redirecione para a página de cadastro de anamnese
       header("Location: ../sucesso/cadastroSucesso.html");
       exit;
   }
}
?>