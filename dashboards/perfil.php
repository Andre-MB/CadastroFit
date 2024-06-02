<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../connectionDB/banco.php';

// Verifica se o usuário está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

$conn = conectarAoBanco();

// Verifica se a chave 'isAdmin' está definida na sessão
if (!isset($_SESSION['isadmin'])) {
    $_SESSION['isadmin'] = false; // Defina um valor padrão se não estiver definido

    // Verifica se $_SESSION['isAdmin'] está definido e é verdadeiro
    if (isset($_SESSION['isadmin']) && $_SESSION['isadmin']) {
        // Obtém informações do administrador
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $_SESSION['usuario_id']);
        $stmt->execute();

        $adminInfo = $stmt->fetch(PDO::FETCH_ASSOC);

        // Armazena a informação do administrador na sessão
        $_SESSION['isadmin'] = $adminInfo['isadmin'];
        $_SESSION['nome_admin'] = $adminInfo['nome'];
    } else {
        // Se $_SESSION['isAdmin'] não estiver definido ou não for verdadeiro, redireciona para o painel do usuário
        header('Location: ../index.php');
        exit;
    }
}

// Consulta o banco de dados para obter os dados dos usuários
$queryUsuarios = $conn->query("SELECT id, nome, email, isadmin FROM usuarios");
$usuarios = $queryUsuarios->fetchAll(PDO::FETCH_ASSOC);

// Armazena os dados na sessão antes do loop
$_SESSION['usuarios'] = $usuarios;

//var_dump($_SESSION['isadmin']);

// Adiciona outro echo para mostrar o valor após atribuir à sessão
//echo "ID do usuário após atribuir à sessão: " . $_SESSION['usuario_id'] . "<br>";


// Adiciona outro echo para mostrar o valor após atribuir à sessão
//echo "Valor de isadmin após atribuir à sessão: " . ($_SESSION['isadmin'] ? 'Sim' : 'Não') . "<br>";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Administrador</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-B4dV3bKGWD7BGNl1zMMovMAf1fQ7Xf4e2MlSz9rF4zmz7xllYcP3sSttu7W5oA9bNUqR8AgwsRNXZEjSbeFaR2A==" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../css/perfil.css">
</head>
<body>

    <div class="container">

        <div class="content">
            <h1>Usuários Cadastrados</h1>
            <div class="usuariosAtivosebutao">
                <div class="exit_config">
                    <a href="dashboard.php"><button class="fas fa-arrow-left"> Voltar</button></a>
                </div>
                
                <?php if ($_SESSION['isadmin']): ?>
                 <a href="cadastrarUsuario.php"><button> + Usuário</button></a>
                <?php endif; ?>

            </div>

            <div class="tabela">
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($usuarios as $usuario):
                        ?>  
                            <?php if ($_SESSION['isadmin']): ?>
                                <tr onclick="window.location='detalhes_usuario.php?id=<?php echo $usuario['id']; ?>'">
                            <?php endif; ?>
                                <td><?= ucwords ($usuario ['nome']) ?></td>
                                <td><?= $usuario ['email'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="../script/dashboard.js"></script>
</body>
</html>
