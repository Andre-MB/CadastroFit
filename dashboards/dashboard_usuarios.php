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
    <title>Dashboard de Alunos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../css/dashboard.css">
</head>

<body>

    <div class="container">


        <div class="user_config">
            <div class="img_ola">
                <?php if ($_SESSION['isadmin']) : ?>
                    <img src="../img/Group 7.png" alt="">
                <?php endif; ?>
                <h2>Olá, <?php echo $_SESSION['nome_admin']; ?> </h2>
            </div>

            <div class="exit_config">
                <a href="../dashboards/dashboard.php"><ion-icon name="chevron-back-outline"></ion-icon></a>
            </div>

        </div>

        <div class="content">

            <div class="alunosAtivosebutao">
                <h3>Usuários Ativos</h3>
                <a href="../forms/cadastroUser.php"><button>+ Usuários</button></a>
            </div>

            <!-- Campo de pesquisa -->
            <div class="search-container">
                <input type="text" id="search" placeholder="Pesquisar por nome...">
            </div>

            <!-- Exibe a tabela de alunos -->
            <div class="tabela">
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($usuarios as $usuario):
                        ?> 
                            <?php if ($_SESSION['isadmin']): ?>
                                <tr onclick="window.location='detalhes_usuario.php?id=<?php echo $usuario['id']; ?>'">
                            <?php endif; ?>
                                <td><?= ucwords($usuario['nome']) ?></td>
                                <td><?= $usuario['email'] ?></td>
                                <td><?= $usuario['isadmin'] ? 'Sim' : 'Não'; ?></td>

                                <!-- Adicione o contêiner de exclusão diretamente na linha -->
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../script/dashboard.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>

</html>