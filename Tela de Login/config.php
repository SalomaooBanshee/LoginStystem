<?php
session_start();

$host = 'localhost';
$banco = 'sistemadelogin';
$usuario = 'root';
$senha = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    exit('Erro ao conectar ao banco de dados.');
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

function escapar($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function csrf_valido() {
    return isset($_POST['csrf'], $_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], $_POST['csrf']);
}

function mensagem($texto = null) {
    if ($texto !== null) {
        $_SESSION['mensagem'] = $texto;
        return;
    }

    $texto = $_SESSION['mensagem'] ?? '';
    unset($_SESSION['mensagem']);

    return $texto;
}