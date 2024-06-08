<?php
session_start();
include '../connectionDB/banco.php';

// Verifica se o usuário está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

$conn = conectarAoBanco();

// Obtém o ID do usuário cuja senha será alterada
$usuario_id = $_GET['id'];

if (!$usuario_id) {
    header('Location: ../index.php');
    exit;
}

// Consulta o banco de dados para obter informações do usuário
$query = $conn->prepare("SELECT id, nome FROM usuarios WHERE id = :id");
$query->bindParam(':id', $usuario_id);
$query->execute();
$usuario = $query->fetch(PDO::FETCH_ASSOC);

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $nova_senha = $_POST['nova_senha'];
    $confirma_nova_senha = $_POST['confirma_nova_senha'];

    // Validações adicionais
    if ($nova_senha === $confirma_nova_senha) {
        // Hash da nova senha
        $hashed_nova_senha = password_hash($nova_senha, PASSWORD_DEFAULT);

        // Atualiza a senha no banco de dados
        $query = $conn->prepare("UPDATE usuarios SET senha = :nova_senha WHERE id = :id");
        $query->bindParam(':nova_senha', $hashed_nova_senha);
        $query->bindParam(':id', $usuario_id);
        $query->execute();

        echo "Senha atualizada com sucesso!";
        header('Location: ../index.php');
        exit;
    } else {
        echo "A nova senha e a confirmação não coincidem. Tente novamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trocar Senha</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../css/forgetPassword.css">
</head>
<body>

    <div class="container">
        <nav class="sidebar">
            <!-- Seu menu aqui -->
        </nav>

        <div class="login-container">
            <h1>Trocar Senha</h1>
            <p>Você está trocando a senha do usuário: <?php echo ucwords($usuario['nome']); ?></p>

            <form method="post" action="">
                <div class="input-group">
                    <input type="password" name="senha_atual" placeholder="Senha Atual" required>
                </div>

                <div class="input-group">
                <input type="password" name="nova_senha" placeholder="Nova Senha" required>
                </div>

                <div class="input-group">
                <input type="password" name="confirma_nova_senha" placeholder="Confirme a Nova Senha" required>
                </div>

                <button type="submit" class="login-btn" name="submit">Trocar Senha</button>
            </form>
        </div>
    </div>

</body>
</html>
