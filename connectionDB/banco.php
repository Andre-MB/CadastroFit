<?php
function conectarAoBanco()
{
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=efitnessdb', 'root', '');
        // Configurar o PDO para relatar erros. 
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo "Erro de Conexão: " . $e->getMessage();
        return null;
    }
}
