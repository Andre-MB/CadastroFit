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
                    header("Location: ../dashboards/dashboard_usuarios.php?id={$_SESSION['usuario_id']}");
                    exit;
                } else {
                    $mensagem = 'Erro ao cadastrar novo usuário. Tente novamente.';
                }
            }
        }
    }
} else {
    header("Location:../dashboards/dashboard_usuarios.php?id={$_SESSION['usuario_id']}");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Aluno</title>
    <!-- CDN do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/cadastroUser.css">
</head>

<body>
    <!-- DIV Principal -->
    <div class="container col-11 col-md-9" id="form-container">

        <div class="row align-items-center gx-5">
            <!-- DIV do formulario -->
            <div class="col-md-6 order-md-2">
                <h2>Cadastro de Usuário</h2>
                <form class="row g-3" action="cadastroUser.php" method="post">
                    <div class="col-12 mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome do usuário" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="telefone" class="form-label">Senha</label>
                        <input type="text" class="form-control" id="" name="senha" placeholder="Digite a senha do usuário" required>
                    </div>

                    <div class=" col-md-6 mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" name="email" placeholder="Digite o email do usuário" required>
                    </div>

                    <div class="admin">
                        <input type="checkbox" id="isAdmin" name="isAdmin">
                        <label for="Sim">Privilégio de Administrador</label>
                    </div>


                    <?php if ($mensagem): ?>
                        <p><?php echo $mensagem; ?></p>
                    <?php endif; ?>
                    <div class="div_btn col-12 mb-3 ">
                        <button type="submit" class="btn btn-danger" name="cadastrarUsuario">Cadastrar</button>
                    </div>
                </form>
            </div>
            <!-- DIV da imagem -->
            <div class="col-md-6 order-md-1">
                <div class="col-12">
                    <img src="https://source.unsplash.com/1600x1600/?fitness,exercise" alt="" class="img-fluid">
                </div>
            </div>

        </div>

    </div>
</body>

</html>