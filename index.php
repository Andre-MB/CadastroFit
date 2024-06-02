<?php
// Inicie a sessão para acessar as variáveis de sessão
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>CadastroFit</title>
  <link rel="icon" href="img/icon.png" type="image/x-icon">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+Wy6p9w1YY6L/EpuPbZ+8Fl/VPBk2ZIbp44" crossorigin="anonymous">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="css/indexa.css">
</head>

<body>

  <main>
    <!-- Formulário de Login -->

    <div class="cont_main">
      <div class="col-lg-5 ">
        <div class="">
          <div class="card-body">

            <h3 class="card-title">Login</h3>

            <!-- Adicione o formulário de login aqui -->
            <form action="login.php" method="post">

              <div class="form-group">
                <input type="text" class="form-control" id="username" placeholder="E-mail" name="email" requered>
              </div>

              <div class="form-group position-relative">
                <input type="password" class="form-control" id="password" placeholder="Senha" name="senha" autocomplete="current-password" requered>
                <span class="eye-icon position-absolute" id="togglePassword" onclick="togglePasswordVisibility()">
                  <i class="fa fa-eye" aria-hidden="true"></i>
                </span>
              </div>

              <!-- Move a mensagem de erro abaixo do input de senha com margem à esquerda -->
              <div> <!-- Adjusted margin-left using Bootstrap utility class -->
                <?php
                // Verifique se há uma mensagem de erro
                if (isset($_SESSION['login_error'])) {
                  // Exiba a mensagem de erro em vermelho
                  echo '<div id="loginErrorMessage" class="alert alert-danger text-center alert-sm" role="alert">' . $_SESSION['login_error'] . '</div>';
                  // Limpe a variável de sessão
                  unset($_SESSION['login_error']);
                }
                ?>

                <script>
                  // Função para remover a mensagem de erro após 5 segundos
                  setTimeout(function() {
                    var errorMessage = document.getElementById('loginErrorMessage');
                    if (errorMessage) {
                      errorMessage.style.display = 'none';
                    }
                  }, 3000); // 3000 milissegundos = 3 segundos
                </script>

              </div>

              <div class=" text-center "> <!-- Adjusted margin-top using Bootstrap utility class -->
                <a href=" resetPassword/forget_password.php">Esqueceu a senha?</a>
              </div>

              <div class="form-group text-center"> <!-- Adicionado 'text-center' aqui -->
                <button type="submit" class="">Entrar</button>
              </div>

            </form>

          </div>
        </div>
      </div>


      <!-- Carrossel de Imagens -->
      <div id="carouselExample" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="https://source.unsplash.com/1600x1600/?fitness,exercise" class="d-block w-100 img-fluid" alt="Ilustração do curso de Educação Física - Imagem 1">
            <div class="carousel-caption d-none d-md-block">
            </div>
          </div>
          <div class="carousel-item">
            <img src="https://source.unsplash.com/1600x1600/?instructor,fitness" class="d-block w-100 img-fluid" alt="Ilustração do curso de Educação Física - Imagem 2">
            <div class="carousel-caption d-none d-md-block">
            </div>
          </div>
          <div class="carousel-item">
            <img src="https://source.unsplash.com/1600x1600/?fitness,goal" class="d-block w-100 img-fluid" alt="Ilustração do curso de Educação Física - Imagem 3">
            <div class="carousel-caption d-none d-md-block">
            </div>
          </div>
        </div>
      </div>

    </div>

  </main>



  <div class="footer">
    <p>CadastroFit &copy; 2024 <a href="https://github.com/Andre-MB/CadastroFit" target="_blank">LTD</a> </p>
  </div>

  <div class="logo_estacio">
    <img src="./img/estaciologo.png" height="60px">
  </div>

  <!-- Bootstrap JavaScript and dependências -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="script/index.js"></script>
</body>

</html>