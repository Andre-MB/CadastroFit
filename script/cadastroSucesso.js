// Obtém o modal
var modal = document.getElementById("myModal");

// Mostra o modal
modal.style.display = "block";

// Redireciona para index.html após 3 segundos
setTimeout(function () {
    window.location.href = "../dashboards/dashboard.php";
}, 3000); // 3000 milissegundos = 3 segundos