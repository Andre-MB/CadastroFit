<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../connectionDB/banco.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

$conn = conectarAoBanco();

if (!$conn) {
    die("Falha na conexão com o banco de dados");
}

// Verifica se o ID do usuário foi passado pela URL
if (isset($_GET['id'])) {
    $usuario_id = $_GET['id'];
} else {
    die("ID do usuário não fornecido");
}

// Consulta os detalhes do usuário específico
$queryUsuario = $conn->prepare("SELECT * FROM usuarios WHERE id = :usuario_id");
$queryUsuario->bindParam(':usuario_id', $usuario_id);
$queryUsuario->execute();
$usuario = $queryUsuario->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Usuário não encontrado");
}

// Atribui o valor correto a $isadmin (por exemplo, se estiver armazenado em $_SESSION)
$isadmin = isset($_SESSION['isadmin']) ? $_SESSION['isadmin'] : false;

// Armazena o valor de isadmin na sessão
$_SESSION['isadmin'] = $isadmin;

// Armazena o valor do ID do usuário na sessão
$_SESSION['usuario_id'] = $_SESSION['usuario_id'];

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Aluno</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-B4dV3bKGWD7BGNl1zMMovMAf1fQ7Xf4e2MlSz9rF4zmz7xllYcP3sSttu7W5oA9bNUqR8AgwsRNXZEjSbeFaR2A==" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel="stylesheet" type="text/css" href="../css/dashboard_detalhes_user.css">
</head>

<body>

    <div class="container">


        <div class="content">
            <h2>Detalhes do Usuário</h2>

            <table class="student-table">
                <tr>
                    <td class="student-info">
                        <strong>Nome:</strong> <?= ucwords($usuario['nome']) ?>
                    </td>
                </tr>
                <tr>
                    <td class="student-info">
                        <strong>E-mail:</strong> <?= $usuario['email'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="student-info">
                        <strong>Administrador:</strong> <?= $usuario['isadmin'] ? 'Sim' : 'Não'; ?>
                    </td>
                </tr>

            </table>

            <div class="navBar">

                <div class="cont_user">
                <li class="smooth-hover"><a href="#" onclick="redirecionarVoltar()"><i class="fas fa-arrow-left"></i> Voltar</a></li>
                <li class="smooth-hover"><a href="../resetPassword/trocaSenha.php?id=<?= $usuario['id']; ?>"><i class="fi fi-rr-lock"></i>Redefinir Senha</a></li>
                    <?php if ($_SESSION['isadmin']): ?>
                <li class="smooth-hover"><a href="#" onclick="confirmarExclusao(<?= $usuario['id']; ?>)"><i class="fas fa-trash-alt" style="color: red;"></i>Excluir Usuário</a></li>
                <?php endif; ?>
                </div>
            </div>


        </div>
    </div>

    <script>
function confirmarExclusao(idUsuario) {
    var confirmar = confirm("Tem certeza que deseja excluir este usuário?");
        if (confirmar) {
            window.location.href = '../delete/excluirUsuario.php?id=' + idUsuario;
        }
    }

function redirecionarVoltar() {
    // Verifica se é um administrador
    if (<?php echo $_SESSION['isadmin'] ? 'true' : 'false'; ?>) {
        // Se for um administrador, direciona para perfil.php
        window.location.href = 'dashboard_usuarios.php?id=<?php echo $_SESSION['usuario_id']; ?>';
    } else {
        // Se não for um administrador, direciona para adminDashboard.php
        window.location.href = 'dashboard.php';
    }
}
</script>

</body>

</html>