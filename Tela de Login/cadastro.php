<?php
require_once 'config.php';

if (isset($_SESSION['usuario'])) {
    header('Location: painel.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido()) {
        $erro = 'Solicitação inválida. Atualize a página.';
    } else {
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $confirmarSenha = $_POST['confirmar_senha'] ?? '';

        if ($nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erro = 'Informe um nome e um e-mail válido.';
        } elseif (strlen($senha) < 8) {
            $erro = 'A senha precisa ter pelo menos 8 caracteres.';
        } elseif ($senha !== $confirmarSenha) {
            $erro = 'As senhas não coincidem.';
        } else {
            $consulta = $pdo->prepare(
                'SELECT id FROM usuarios WHERE email = ?'
            );

            $consulta->execute([$email]);

            if ($consulta->fetch()) {
                $erro = 'Este e-mail já está cadastrado.';
            } else {
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                $inserir = $pdo->prepare(
                    'INSERT INTO usuarios (nome, email, senha)
                     VALUES (?, ?, ?)'
                );

                $inserir->execute([$nome, $email, $senhaHash]);

                mensagem('Cadastro realizado com sucesso! Faça seu login.');

                header('Location: index.php');
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro | Meu Sistema</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <main class="container">
        <section class="card">

            <h1>Criar conta</h1>
            <p class="subtitulo">Preencha os dados abaixo.</p>

            <?php if ($erro): ?>
                <div class="mensagem erro">
                    <?= escapar($erro) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="cadastro.php">

                <input type="hidden" name="csrf"
                    value="<?= escapar($_SESSION['csrf']) ?>">

                <label for="nome">Nome completo</label>
                <input type="text" id="nome" name="nome"
                    placeholder="Seu nome" maxlength="100" required>

                <label for="email">E-mail</label>
                <input type="email" id="email" name="email"
                    placeholder="Seu e-mail" maxlength="150" required>

                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha"
                    placeholder="Mínimo de 8 caracteres"
                    minlength="8" required>

                <label for="confirmar_senha">Confirmar senha</label>
                <input type="password" id="confirmar_senha"
                    name="confirmar_senha"
                    placeholder="Digite a senha novamente"
                    minlength="8" required>

                <button type="submit">Cadastrar</button>

            </form>

            <p class="cadastro">
                Já possui uma conta?
                <a href="index.php">Voltar ao login</a>
            </p>

        </section>
    </main>

</body>
</html>