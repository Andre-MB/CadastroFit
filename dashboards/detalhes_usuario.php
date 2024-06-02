<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../connectionDB/banco.php';

// Verifica se o usuário está autenticado
// if (!isset($_SESSION['usuario_id'])) {
//     header('Location: ../index.php');
//     exit;
// }

$conn = conectarAoBanco();

//Verifica se a chave 'isAdmin' está definida na sessão
// if (!isset($_SESSION['isadmin'])) {
//     $_SESSION['isadmin'] = false; // Defina um valor padrão se não estiver definido

//     // Verifica se $_SESSION['isAdmin'] está definido e é verdadeiro
//     if (isset($_SESSION['isadmin']) && $_SESSION['isadmin']) {
//         // Obtém informações do administrador
//         $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = :id");
//         $stmt->bindParam(':id', $_SESSION['usuario_id']);
//         $stmt->execute();

//         $adminInfo = $stmt->fetch(PDO::FETCH_ASSOC);

//         // Armazena a informação do administrador na sessão
//         $_SESSION['isadmin'] = $adminInfo['isadmin'];
//         $_SESSION['nome_admin'] = $adminInfo['nome'];
//     } else {
//         // Se $_SESSION['isAdmin'] não estiver definido ou não for verdadeiro, redireciona para o painel do usuário
//         header('Location: ../index.php');
//         exit;
//     }
// }

//Verifica se um ID de usuário foi fornecido na URL
if (!isset($_GET['id'])) {
    echo "ID do usuário não fornecido.";
    exit;
}

$idUsuario = $_GET['id'];

// Consulta o banco de dados para obter os detalhes do usuário
$stmt = $conn->prepare("SELECT id, nome, email, isadmin FROM usuarios WHERE id = :id");
$stmt->bindParam(':id', $idUsuario);
$stmt->execute();
$detalhesUsuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o usuário foi encontrado
if (!$detalhesUsuario) {
    echo "Usuário não encontrado.";
    exit;
}

// Armazena os dados na sessão antes do loop
$_SESSION['usuarios'] = $detalhesUsuario;

// Adiciona o nome do usuário à sessão
$_SESSION['nome_usuario'] = $detalhesUsuario['nome'];

//var_dump($_SESSION['isadmin']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Usuário</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-B4dV3bKGWD7BGNl1zMMovMAf1fQ7Xf4e2MlSz9rF4zmz7xllYcP3sSttu7W5oA9bNUqR8AgwsRNXZEjSbeFaR2A==" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../css/detalhes_usuario.css">
</head>

<body>
    <div class="container">
        <nav class="sidebar">
            <img src="../img/estacio.png" alt="Logo">
            <ul>
                <li class="smooth-hover"><a href="../resetPassword/trocaSenha.php?id=<?php echo $detalhesUsuario['id']; ?>"><i class="fas fa-user"></i> Trocar Senha</a></li>
                <?php if ($_SESSION['isadmin']) : ?>
                    <li class="smooth-hover"><a href="#" onclick="confirmarExclusao(<?= $detalhesUsuario['id']; ?>)"><i class="fas fa-trash-alt" style="color: red;"></i>Excluir Usuário</a></li>
                <?php endif; ?>
                <li class="smooth-hover"><a href="#" onclick="redirecionarVoltar()"><i class="fas fa-arrow-left"></i> Voltar</a></li>
            </ul>
        </nav>

        <div class="content">
            <h1>Detalhes do Usuário</h1>

            <!-- Exibe os detalhes do usuário -->
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Administrador</th>
                </tr>
                <tr>
                    <td><?= $detalhesUsuario['id'] ?></td>
                    <td><?= $detalhesUsuario['nome'] ?></td>
                    <td><?= $detalhesUsuario['email'] ?></td>
                    <td><?= $detalhesUsuario['isadmin'] ? 'Sim' : 'Não'; ?></td>
                </tr>
            </table>
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
                window.location.href = 'perfil.php?id=<?php echo $_SESSION['usuario_id']; ?>';
            } else {
                // Se não for um administrador, direciona para adminDashboard.php
                window.location.href = 'dashboard.php';
            }
        }
    </script>
</body>

</html>