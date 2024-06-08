<?php
session_start();
include '../connectionDB/banco.php';

// Verifica se o token e o e-mail estão na sessão
if (!isset($_SESSION['reset_token']) || !isset($_SESSION['reset_email'])) {
    header('Location: forgot_password.php');
    exit;
}

$token = $_SESSION['reset_token'];
$email = $_SESSION['reset_email'];
$conn = conectarAoBanco();

// Verifica se o token e o e-mail são válidos
$query = $conn->prepare("SELECT * FROM usuarios WHERE email = :email AND token = :token AND data_expiracao > NOW()");
$query->bindParam(':email', $email);
$query->bindParam(':token', $token);
$query->execute();
$usuario = $query->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Link de redefinição inválido ou expirado. Tente novamente.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $nova_senha = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT);

    // Atualiza a senha do usuário no banco de dados
    $query = $conn->prepare("UPDATE usuarios SET senha = :senha, token = NULL, data_expiracao = NULL WHERE email = :email");
    $query->bindParam(':senha', $nova_senha);
    $query->bindParam(':email', $email);
    $query->execute();

    // Limpa a sessão
    unset($_SESSION['reset_token']);
    unset($_SESSION['reset_email']);

    echo "Senha redefinida com sucesso. Você pode fazer login com sua nova senha.";
    header('Location: ../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" type="text/css" href="../css/forgetPassword.css">
</head>

<body>

    <div class="login-container">
            <h1>Redefinir Senha</h1>
            <p>Você está redefinindo a senha para o e-mail: <?php echo $email; ?></p>

            <!-- Formulário para redefinir a senha -->
            <form action="reset_password.php" method="post">
                <div class="input-group">
                    <input type="password" name="nova_senha" placeholder="Nova Senha" required>
                </div>

                <button type="submit" class="login-btn" name="submit">Redefinir Senha</button>
            </form>
    </div>

</body>
</html>
