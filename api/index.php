<?php
session_start();

// Verifica se o botão "sem login" foi pressionado
if (isset($_POST['action']) && $_POST['action'] == 'no_login') {
    // Limpa a sessão
    session_unset(); // Remove todas as variáveis da sessão
    session_destroy(); // Finaliza a sessão
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/style.css">
    <link rel="shortcut icon" href="Images/Dui.ico" type="image/x-icon">
    <script src="https://unpkg.com/scrollreveal"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <title>Dui Burguer Berr</title>
    <style>
        .index{
            background-color: #212121;
            width: 100%;
            height: 100vh;
            overflow-x: hidden;
        }
    </style>
</head>
<body>
    <div class="index">
        <div class="row">
            <div class="col-12 text-center">
                <img src="Images/BgTop.png" class="mt-0 mb-5">
                <div class="cont py-5">
                    <p class="font-padrao-semibold text-fade mt-4" style="font-size: 2rem;">Dui Burguer</p>
                    <div class="d-flex justify-content-center">
                        <img src="Images/IcoStore.png" width="1.5%" height="1.5%" class="mt-1">
                        <p class="font-padrao-semibold text-white" style="margin-left: 0.7rem; font-size: 1.3rem;">Lanches</p>
                    </div>
                    <button class="bg-fade py-3 font-padrao-bold mt-4" data-bs-target="#ModalLogin" data-bs-toggle="modal" style="border-radius: 50px; border: 0; color: #212121; padding-left: 5rem; padding-right: 5rem; font-size: 1.3rem;">Pedir por delivery ou retirada</button>
                </div>
                <img src="Images/BgDown.png" class="mb-0 mt-5">
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalLogin" aria-hidden="true" aria-labelledby="ModalLoginLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center" style="background-color: #212121; border-radius: 5px 5px 0px 0px ;">  
                    <h1 class="modal-title fs-5 text-white" id="ModalLoginLabel">Como deseja continuar?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div> 
                <div class="text-center" style="background-color: #212121; border-radius: 0px 0px 5px 5px;">  
                    <a href="src/login/login.php"><button class="bg-fade py-3 mt-5 font-padrao-bold" style="border-radius: 50px; border: 0; color: #212121; padding-left: 10rem; padding-right: 10rem; font-size: 1.3rem;">Entrar ou cadastrar</button></a>
                    <a href="src/home"><button class="bg-fade py-3 mt-4 mb-5 font-padrao-bold" style="border-radius: 50px; border: 0; color: #212121; padding-left: 10rem; padding-right: 10rem; font-size: 1.3rem;">Continuar sem login</button></a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>