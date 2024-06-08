<?php
// Inicie a sessão para acessar as variáveis de sessão
session_start();
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
    <link rel="stylesheet" href="../css/cadastroAluno.css">
</head>

<body>
    <!-- DIV Principal -->
    <div class="container col-11 col-md-9" id="form-container">

        <div class="row align-items-center gx-5">
            <!-- DIV do formulario -->
            <div class="col-md-6 order-md-2">
                <h2>Cadastro de Aluno</h2>
                <form class="row g-3" action="../database/aluno.php" method="post">
                    <div class="col-12 mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome do aluno" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="dataNascimento" class="form-label">Data de Nascimento</label>
                        <input type="date" class="form-control" id="" name="dataNascimento" requered>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="" name="telefone" placeholder="(XX) XXXXX-XXXX" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="sexo" class="form-label">Gênero</label>
                        <select class="form-select" name="sexo" id="" required>
                            <option selected>Escolha o gênero do aluno</option>
                            <option value="feminino">Feminino</option>
                            <option value="masculino">Masculino</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="" name="email" placeholder="Digite o email do aluno" required>
                    </div>
                    <?php
                    if (isset($_SESSION['error_message'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
                        unset($_SESSION['error_message']); // Clear the session variable
                    }
                    ?>
                    <div class="div_btn col-12 mb-3 ">
                        <button type="submit" class="btn btn-danger">Cadastrar</button>
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