<?php
session_start();
include '../connectionDB/banco.php';

$conn = conectarAoBanco();
$mensagem = '';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

if (isset($_SESSION['isadmin']) && $_SESSION['isadmin']) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['cadastrarUsuario'])) {
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha = $_POST['senha'];
            $isAdmin = isset($_POST['isAdmin']);

    // Adicione esta linha para verificar o valor da checkbox
    var_dump($isAdmin);

            $verificarEmail = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
            $verificarEmail->bindParam(':email', $email);
            $verificarEmail->execute();

            if ($verificarEmail->rowCount() > 0) {
                $mensagem = 'Erro: O e-mail já está em uso. Escolha outro e-mail.';
            } else {
                $inserirUsuario = $conn->prepare("INSERT INTO usuarios (nome, email, senha, isadmin) VALUES (:nome, :email, :senha, :isadmin)");
                $inserirUsuario->bindParam(':nome', $nome);
                $inserirUsuario->bindParam(':email', $email);
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                $inserirUsuario->bindParam(':senha', $senhaHash);
                $inserirUsuario->bindParam(':isadmin', $isAdmin, PDO::PARAM_BOOL); // Informa que é um parâmetro booleano
                var_dump($isAdmin);

                if ($inserirUsuario->execute()) {
                    $mensagem = 'Novo usuário cadastrado com sucesso!';
                    header("Location: perfil.php?id={$_SESSION['usuario_id']}");
                    exit;
                } else {
                    $mensagem = 'Erro ao cadastrar novo usuário. Tente novamente.';
                }
            }
        }
    }
} else {
    header("Location: perfil.php?id={$_SESSION['usuario_id']}");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Novo Usuário</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-B4dV3bKGWD7BGNl1zMMovMAf1fQ7Xf4e2MlSz9rF4zmz7xllYcP3sSttu7W5oA9bNUqR8AgwsRNXZEjSbeFaR2A==" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../css/cadastrarUsuario.css">
</head>
<body>

    <div class="container">
        <nav class="sidebar">
            <img src="../img/estacio.png" alt="Logo">
            <ul>
                <li class="smooth-hover"><a href="perfil.php?id=<?php echo $_SESSION['usuario_id'];?>"><i class="fas fa-arrow-left"></i> Voltar</a></li>
            </ul>
        </nav>

            <div class="content">
                <h1>Cadastrar Novo Usuário</h1>
            <div>

            <?php if ($mensagem): ?>
                <p><?php echo $mensagem; ?></p>
            <?php endif; ?>
                
            <!-- Formulário para cadastrar usuário -->
            <div class="login-container">
                <form action="cadastrarUsuario.php" method="post">
                    <div class="input-group">
                        <input type="text" name="nome" placeholder="Nome" required>
                    <div>
                    <div class="input-group">
                        <input type="email" name="email" placeholder="E-mail" required>
                    <div>
                    <div class="login-container">
                        <input type="password" name="senha" placeholder="Senha" required>
                    </div>
                    <label class="custom-checkbox" for="isAdmin">
                        <input type="checkbox" id="isAdmin" name="isAdmin">
                        <span class="checkmark"></span>
                        Privilégios de Administrador
                    </label>
                        <button type="submit" class="login-btn" name="cadastrarUsuario">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
