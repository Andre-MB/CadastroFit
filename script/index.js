 // Função para inicializar o estado do campo de senha e do ícone
 function initializePasswordVisibility() {
    var passwordInput = document.getElementById('password');
    var eyeIcon = document.getElementById('togglePassword').querySelector('i');

    // Inicializa o campo de senha como oculto
    passwordInput.type = 'password';
    // Inicializa o ícone como "olho cortado"
    eyeIcon.classList.remove('fa-eye');
    eyeIcon.classList.add('fa-eye-slash');
  }

  // Função para alternar a visibilidade da senha
  function togglePasswordVisibility() {
    var passwordInput = document.getElementById('password');
    var eyeIcon = document.getElementById('togglePassword').querySelector('i');

    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      eyeIcon.classList.remove('fa-eye-slash');
      eyeIcon.classList.add('fa-eye');
    } else {
      passwordInput.type = 'password';
      eyeIcon.classList.remove('fa-eye');
      eyeIcon.classList.add('fa-eye-slash');
    }
  }

  // Inicializa o estado no carregamento da página
  document.addEventListener('DOMContentLoaded', function () {
    initializePasswordVisibility();
  });