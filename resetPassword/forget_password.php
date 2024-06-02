<?php
session_start();
include '../connectionDB/banco.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $email = $_POST['email'];
    $conn = conectarAoBanco();

    // Verifica se o e-mail é válido
    $query = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
    $query->bindParam(':email', $email);
    $query->execute();
    $usuario = $query->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Gera um token único
        $token = bin2hex(random_bytes(32));

        // Insere um registro na tabela de pedidos de redefinição de senha
        $expiracao = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $query = $conn->prepare("UPDATE usuarios SET token = :token, data_expiracao = :expiracao WHERE email = :email");
        $query->bindParam(':token', $token);
        $query->bindParam(':expiracao', $expiracao);
        $query->bindParam(':email', $email);
        $query->execute();

        // Armazena o token na sessão (simulando o envio por e-mail)
        $_SESSION['reset_token'] = $token;
        $_SESSION['reset_email'] = $email;

        // Redireciona para a página de redefinição de senha
        header('Location: reset_password.php');
        exit;
    } else {
        $erro = "E-mail não encontrado. Tente novamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+Wy6p9w1YY6L/EpuPbZ+8Fl/VPBk2ZIbp44" crossorigin="anonymous">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Esqueceu a Senha</title>
    <link rel="stylesheet" type="text/css" href="../css/forgetPassword.css">
</head>

<body>

    <div class="login-container">
            <h1>Redefinição de Senha</h1>
            <p>Informe seu endereço de e-mail para redefinir sua senha.</p>

            <!-- Formulário para inserir o e-mail -->
            <form action="forget_password.php" method="post">
                <div class="input-group">
                    <input type="email" name="email" placeholder="E-mail" required>
                </div>

                <button type="submit" class="login-btn" name="submit">Redefinir de Senha</button>
            </form>

           <?php if (isset($erro)) { echo '<div class="alert alert-danger text-center" role="alert" style="width: 90%; margin: 0 auto;">'. $erro .'</div>'; } ?>
        </div>
    </div>

</body>
</html>
