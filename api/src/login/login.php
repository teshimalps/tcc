<?php
session_start();

// Limpa qualquer dado de sessão anterior
session_unset(); // Remove todas as variáveis da sessão
session_destroy(); // Finaliza a sessão

require "../administrativo/bnc.php";

// Inicialização de mensagens
$mensagem = "";
$tipoMensagem = "";

// Cadastro de usuário
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'register') {
    $user_nom = trim($_POST['user_nom']);
    $user_email = trim($_POST['user_email']);
    $user_pas = trim($_POST['user_pas']);
    
    if (empty($user_nom) || empty($user_email) || empty($user_pas)) {
        $mensagem = "Todos os campos são obrigatórios.";
        $tipoMensagem = "danger";
    } else {
        try {
            $pdo = Bnc::conectar();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Verifica se o e-mail já está em uso
            $sql = "SELECT * FROM tbusuarios WHERE USER_EMAIL = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(':email' => $user_email));
            $usuarioExistente = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuarioExistente) {
                $mensagem = "E-mail já está sendo utilizado.";
                $tipoMensagem = "danger";
            } else {
                // Criptografa a senha
                $hashedPassword = password_hash($user_pas, PASSWORD_DEFAULT);

                // Insere o novo usuário
                $sqlInsert = "INSERT INTO tbusuarios (USER_NOM, USER_EMAIL, USER_PAS, USER_TIP) 
                              VALUES (:nome, :email, :password, 'normal')";
                $stmtInsert = $pdo->prepare($sqlInsert);
                $stmtInsert->execute(array(':nome' => $user_nom, ':email' => $user_email, ':password' => $hashedPassword));

                $mensagem = "Usuário cadastrado com sucesso!";
                $tipoMensagem = "success";
            }

            Bnc::desconectar();
        } catch (Exception $e) {
            $mensagem = "Erro ao cadastrar o usuário: " . $e->getMessage();
            $tipoMensagem = "danger";
        }
    }
}

// Login de usuário
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'login') {
    $user_email = trim($_POST['user_email']);
    $user_pas = trim($_POST['user_pas']);

    if (empty($user_email) || empty($user_pas)) {
        $mensagem = "E-mail e senha são obrigatórios.";
        $tipoMensagem = "danger";
    } else {
        try {
            $pdo = Bnc::conectar();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Consulta o usuário pelo e-mail
            $sql = "SELECT * FROM tbusuarios WHERE USER_EMAIL = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(':email' => $user_email));
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                // Verifica a senha criptografada
                if (password_verify($user_pas, $usuario['USER_PAS'])) {
                    // Login bem-sucedido, armazena informações na sessão
                    session_start();
                    $_SESSION['USER_ID'] = $usuario['USER_ID'];
                    $_SESSION['USER_TIP'] = $usuario['USER_TIP']; // Normal ou master

                    // Redireciona para a tela home
                    header("Location: ../home");
                    exit();
                } else {
                    // Se a senha não bate, mas é uma senha simples (não criptografada)
                    if ($usuario['USER_PAS'] === $user_pas) {
                        // Migra a senha para o formato hash
                        $hashedPassword = password_hash($user_pas, PASSWORD_DEFAULT);
                        $sqlUpdate = "UPDATE tbusuarios SET USER_PAS = :newPassword WHERE USER_ID = :userId";
                        $stmtUpdate = $pdo->prepare($sqlUpdate);
                        $stmtUpdate->execute(array(':newPassword' => $hashedPassword, ':userId' => $usuario['USER_ID']));

                        // Agora faz o login com a senha hasheada
                        session_start();
                        $_SESSION['USER_ID'] = $usuario['USER_ID'];
                        $_SESSION['USER_TIP'] = $usuario['USER_TIP']; // Normal ou master
                        
                        // Redireciona para a tela home
                        header("Location: ../home");
                        exit();
                    } else {
                        $mensagem = "Senha incorreta.";
                        $tipoMensagem = "danger";
                    }
                }
            } else {
                $mensagem = "E-mail não encontrado.";
                $tipoMensagem = "danger";
            }

            Bnc::desconectar();
        } catch (Exception $e) {
            $mensagem = "Erro ao realizar o login: " . $e->getMessage();
            $tipoMensagem = "danger";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <title>Dui Burguer Berr</title>
    <style>
        .alert {
            position: absolute;
            top: 10px;
            right: 40px;
            z-index: 1050;
        }
    </style>
</head>
<body>
        <?php if ($mensagem): ?>
            <div class="alert alert-<?= $tipoMensagem ?> alert-dismissible fade show" role="alert">
                <?= $mensagem ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <div class="index">
            <div class="row">
                <div class="col-12 text-center">
                    <img src="../../Images/BgTop.png" class="m-0">
                    <div class="row cont bg-bl">
                        <form action="login.php" method="POST">
                            <div class="offset-4 col-4">
                                <p class="fw-bold text-fade fs-2r">Login</p>
                                <div class="mb-4">
                                    <label for="user_email" class="text-fade fw-bold fs-5">Seu e-mail</label>
                                    <div class="bg-fade mt-2 div-input-style"><input type="text" class="py-3 fw-semibold w-100 text-white input-style"  required spellcheck="false" id="user_email" name="user_email" autocomplete="off"></div>
                                </div>
                                <div class="mb-4">
                                    <label for="user_pas" class="text-fade fw-bold fs-5">Sua Senha</label>
                                    <div class="bg-fade mt-2 div-input-style"><input type="text" class="py-3 fw-semibold w-100 text-white input-style"  required spellcheck="false" id="user_pas" name="user_pas" autocomplete="off"></div>
                                </div>
                                <p class="fw-semibold text-white fs-1r">Não tem uma conta? <button class="btn-none" data-bs-target="#ModalCadastro" data-bs-toggle="modal"><span class="text-fade fw-bold">cadastre-se</span></button> agora em nosso site! Venha conhecer nossos lanches deliciosos!</p>
                                <button class="bg-fade py-3 fw-bold br-50 border-0 color-bl px-10 fs-1-3r" type="submit" name="action" value="login">Login</button>
                                <a href="../home/" class="text-dec-none"><p class="fw-semibold text-white mt-3 fs-1r mb-0">Continuar sem logar</p></a>
                            </div>
                        </form>
                    </div>
                    <img src="../../Images/BgDown.png" class="m-0">
                </div>
            </div>
        </div>

    <div class="modal fade" id="ModalCadastro" aria-hidden="true" aria-labelledby="ModalCadastroLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center bg-bl br-top-modal">  
                    <h1 class="modal-title fs-5 text-fade fw-bold fs-3" id="ModalCadastroLabel">Cadastre uma nova conta</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="text-center px-4 bg-bl br-bottom-modal">  
                    <form action="login.php" method="POST">
                        <div class="mt-2 mb-4">
                            <label for="user_nom" class="text-fade fw-bold fs-5">Seu nome</label>
                            <div class="bg-fade mt-2 div-input-style"><input type="text" class="py-3 fw-semibold w-100 text-white input-style"  required spellcheck="false" id="user_nom" name="user_nom" autocomplete="off"></div>
                        </div>
                        <div class="mb-4">
                            <label for="user_email" class="text-fade fw-bold fs-5">Seu e-mail</label>
                            <div class="bg-fade mt-2 div-input-style"><input type="text" class="py-3 fw-semibold w-100 text-white input-style"  required spellcheck="false" id="user_email" name="user_email" autocomplete="off"></div>
                        </div>
                        <div class="mb-4">
                            <label for="user_pas" class="text-fade fw-bold fs-5">Sua Senha</label>
                            <div class="bg-fade mt-2 div-input-style"><input type="text" class="py-3 fw-semibold w-100 text-white input-style"  required spellcheck="false" id="user_pas" name="user_pas" autocomplete="off"></div>
                        </div>
                        <button class="bg-fade fw-bold py-3 mb-4 text-dec-none fs-1-3r color-bl w-50 border-0 br-50" type="submit" name="action" value="register">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>