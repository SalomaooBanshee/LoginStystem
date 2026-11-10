<?php
require_once 'config.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

$usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel | Meu Sistema</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <main class="container">
        <section class="card painel">

            <h1>Login realizado!</h1>

            <p class="subtitulo">
                Seja bem-vindo ao seu painel.
            </p>

            <div class="dados">
                <p><strong>Nome:</strong>
                    <?= escapar($usuario['nome']) ?>
                </p>

                <p><strong>E-mail:</strong>
                    <?= escapar($usuario['email']) ?>
                </p>

                <p><strong>ID:</strong>
                    <?= escapar($usuario['id']) ?>
                </p>
            </div>

            <form action="logout.php" method="POST">

                <input type="hidden" name="csrf"
                    value="<?= escapar($_SESSION['csrf']) ?>">

                <button type="submit" class="botao-sair">
                    Sair da conta
                </button>

            </form>

        </section>
    </main>

</body>
</html>