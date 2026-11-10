<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'config.php';

if (isset($_SESSION['usuario'])) {
    header('Location: painel.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido()) {
        $erro = 'Solicitação inválida. Atualize a página e tente novamente.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $senhaDigitada = $_POST['senha'] ?? '';

        $sql = $pdo->prepare(
            'SELECT * FROM usuarios WHERE email = ?'
        );

        $sql->execute([$email]);
        $usuarioEncontrado = $sql->fetch();

        if (
            $usuarioEncontrado &&
            password_verify($senhaDigitada, $usuarioEncontrado['senha'])
        ) {
            session_regenerate_id(true);

            $_SESSION['usuario'] = [
                'id' => $usuarioEncontrado['id'],
                'nome' => $usuarioEncontrado['nome'],
                'email' => $usuarioEncontrado['email']
            ];

            header('Location: painel.php');
            exit;
        } else {
            $erro = 'E-mail ou senha incorretos.';
        }
    }
}

$sucesso = mensagem();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Meu Sistema</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <main class="container">
        <section class="card">

            <h1>Bem-vindo!</h1>
            <p class="subtitulo">Entre na sua conta para continuar.</p>

            <?php if ($sucesso): ?>
                <div class="mensagem sucesso">
                    <?= escapar($sucesso) ?>
                </div>
            <?php endif; ?>

            <?php if ($erro): ?>
                <div class="mensagem erro">
                    <?= escapar($erro) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php">

                <input type="hidden" name="csrf"
                    value="<?= escapar($_SESSION['csrf']) ?>">

                <label for="email">E-mail</label>
                <input type="email" id="email" name="email"
                    placeholder="Digite seu e-mail" required>

                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha"
                    placeholder="Digite sua senha" required>

                <button type="submit">Entrar</button>

            </form>

            <p class="cadastro">
                Ainda não possui uma conta?
                <a href="cadastro.php">Cadastre-se</a>
            </p>

        </section>
    </main>

</body>
</html>