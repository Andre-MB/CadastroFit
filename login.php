<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connectionDB/banco.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = conectarAoBanco();

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    try {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Login bem-sucedido
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['isadmin'] = $usuario['isadmin'];
            $_SESSION['nome_admin'] = $usuario['nome'];

            // Redireciona para a página do painel
            header('Location: dashboards/dashboard.php');
            exit;
        } else {
            // Login falhou
            $_SESSION['login_error'] = "E-mail ou senha inválidos.";
            // Redireciona de volta para a página de login
            header('Location: index.php');
            exit;
        }
    } catch (Exception $e) {
        echo "Erro na execução da consulta: " . $e->getMessage();
    }
}
