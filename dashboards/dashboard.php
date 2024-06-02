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

$query = $conn->query("SELECT * FROM alunos");
$alunos = $query->fetchAll(PDO::FETCH_ASSOC);

// Consulta os usuários
$queryUsuarios = $conn->prepare("SELECT * FROM usuarios WHERE id = :usuario_id");
$queryUsuarios->bindParam(':usuario_id', $_SESSION['usuario_id']);
$queryUsuarios->execute();
$usuarios = $queryUsuarios->fetchAll(PDO::FETCH_ASSOC);

// Atribui o valor correto a $isadmin (por exemplo, se estiver armazenado em $_SESSION)
$isadmin = isset($_SESSION['isadmin']) ? $_SESSION['isadmin'] : false;

// Armazena o valor de isadmin na sessão
$_SESSION['isadmin'] = $isadmin;

// Armazena o valor do ID do usuário na sessão
$_SESSION['usuario_id'] = $usuarios[0]['id'];

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Alunos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../css/dashboard.css">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
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
                <a class="config" href="<?php echo $_SESSION['isadmin'] ? 'perfil.php?id=' . $_SESSION['usuario_id'] : '../dashboards/dashboard_usuarios.php?id=' . $_SESSION['usuario_id']; ?>"><ion-icon name="person"></ion-icon></a>
                <a href="../logout.php"><i class="fas fa-sign-out-alt"></i></a>
            </div>

        </div>

        <div class="content">

            <div class="alunosAtivosebutao">
                <h3>Alunos Ativos</h3>
                <a href="../forms/cadastroAluno.php"><button>+ Aluno</button></a>
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
                            <th>Telefone</th>
                            <th>Sexo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alunos as $aluno) : ?>
                            <tr onclick="window.location='detalhes_aluno.php?id=<?php echo $aluno['id']; ?>'">

                                <td><?= ucwords($aluno['nome']) ?></td>
                                <td><?= $aluno['telefone'] ?></td>
                                <td><?= ucwords($aluno['sexo']) ?></td>

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