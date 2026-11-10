<?php

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valido()) {
    http_response_code(403);
    exit('Solicitação inválida.');
}

$_SESSION = [];

session_destroy();

header('Location: index.php');
exit;

?>