<?php
session_start();

// Inicializa variáveis para os dados do usuário
$usuarioLogado = false;
$user_tip = null; // 'master', 'normal', ou 'visitante'
$user_nom = null;
$user_email = null;

// Verifica se o usuário está logado (se tem a sessão do ID)
if (isset($_SESSION['USER_ID']) && $_SESSION['USER_ID'] !== null) {
    $usuarioLogado = true;
    $user_id = $_SESSION['USER_ID'];
    $user_tip = $_SESSION['USER_TIP'];

    // Conectar ao banco de dados
    require "../administrativo/bnc.php";
    try {
        $pdo = Bnc::conectar();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta as informações do usuário logado
        $sql = "SELECT USER_NOM, USER_EMAIL, USER_TIP FROM tbusuarios WHERE USER_ID = :userId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':userId' => $user_id));
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            $user_nom = $usuario['USER_NOM'];
            $user_email = $usuario['USER_EMAIL'];
            $user_tip = $usuario['USER_TIP'];
        } else {
            header("Location: ../login");
            exit();
        }

        Bnc::desconectar();
    } catch (Exception $e) {
        echo "Erro ao buscar dados do usuário: " . $e->getMessage();
        exit();
    }

    // Se o formulário for enviado, atualiza os dados
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update_profile') {
        $user_nom = trim($_POST['user_nom']);
        $user_email = trim($_POST['user_email']);
        $user_pas = trim($_POST['user_pas']);
        $user_tip = $_POST['user_tip'];

        // Verifica se a senha foi alterada
        if (!empty($user_pas)) {
            // Criptografa a nova senha
            $user_pas = password_hash($user_pas, PASSWORD_DEFAULT);
        } else {
            // Mantém o hash da senha atual
            try {
                $pdo = Bnc::conectar();
                $sql = "SELECT USER_PAS FROM tbusuarios WHERE USER_ID = :user_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(':user_id' => $user_id));
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                $user_pas = $user['USER_PAS']; // Mantém o hash original
                Bnc::desconectar();
            } catch (Exception $e) {
                $mensagem = "Erro ao carregar a senha: " . $e->getMessage();
                $tipoMensagem = "danger";
            }
        }

        // Atualiza os dados do usuário no banco
        try {
            $pdo = Bnc::conectar();
            $sqlUpdate = "UPDATE tbusuarios SET USER_NOM = :user_nom, USER_EMAIL = :user_email, USER_PAS = :user_pas, USER_TIP = :user_tip WHERE USER_ID = :user_id";
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $stmtUpdate->execute(array(
                ':user_nom' => $user_nom,
                ':user_email' => $user_email,
                ':user_pas' => $user_pas,
                ':user_tip' => $user_tip,
                ':user_id' => $user_id
            ));

            $mensagem = "Perfil atualizado com sucesso!";
            $tipoMensagem = "success";
            Bnc::desconectar();
        } catch (Exception $e) {
            $mensagem = "Erro ao atualizar o perfil: " . $e->getMessage();
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
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="shortcut icon" href="../../Images/Dui.ico" type="image/x-icon">
    <title>Dui - Menu</title>
</head>
<body class="bg-home text-dec-none">
    <div class="index text-white">
        <!--Header-->   
        <?php if (isset($mensagem) && $mensagem != ""): ?>
            <div class="alert alert-<?php echo $tipoMensagem; ?>" role="alert">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        <div class="header">
            <div class="nav">
                <header>
                    <a href="index.html" class="logo area-1"><img src="../../Images/Logo.png" alt="logo"></a>            
                    <ul class="navlist area-2 mt-3">
                        <li><a class="font-padrao-d" href="#bebidas">BEBIDAS</a></li>
                        <li><img src="../../Images/Barras.png" alt="Barras" class="mt-0-4r"></li>
                        <li><a class="font-padrao-d" href="#cardapio">CARDÁPIO</a></li>
                        <li><img src="../../Images/Barras.png" alt="Barras" class="mt-0-4r"></li>
                        <li><a class="font-padrao-d" href="#promododia" class="active">PROMO DO DIA</a></li>
                        <li><img src="../../Images/Barras.png" alt="Barras" class="mt-0-4r"></li>
                        <li><a class="font-padrao-d" href="#combos">COMBOS</a></li>
                        <li><img src="../../Images/Barras.png" alt="Barras" class="mt-0-4r"></li>
                        <li><a class="font-padrao-d" href="#avaliacoes">AVALIAÇÕES</a></li>
                    </ul>                    
                    <div class="nav-icons area-3">
                        <button class="border-none bg-transp" data-bs-target="#cartitens" data-bs-toggle="offcanvas"><i class="bx bx-cart"></i></button>
                        <?php if ($usuarioLogado): ?>
                            <!-- Usuário logado -->
                            <?php if ($user_tip == 'master'): ?>
                                <button style="border: 0; background-color: transparent;" data-bs-target="#menuperfilmaster" data-bs-toggle="modal"><i class="bx bx-cog"></i></button>
                            <?php elseif ($user_tip == 'normal'): ?>
                                <button style="border: 0; background-color: transparent;" data-bs-target="#menuperfillogado" data-bs-toggle="modal"><i class="bx bx-cog"></i></button>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- Usuário não logado -->
                            <button style="border: 0; background-color: transparent;" data-bs-target="#menuperfil" data-bs-toggle="modal"><i class="bx bx-cog"></i></button>
                        <?php endif; ?>
                        <div class="bx bx-menu d-md-none d-md-block" id="menu-icon"></div>
                    </div>                    
                </header>
            </div>
            <div class="cont">
                <div class="row">
                    <div class="col-2 col-md-2">
                        <img src="../../Images/line.png" width="8%" class="area-9 margin-line-header mt-150p ml-275p">
                    </div>
                    <div class="col-9 col-md-4 margin-cont-header ml-75p">
                        <p class="font-padrao-d area-9 font-subtitulo mt-160p fs-40p">Venha conhecer o</p>
                        <p class="font-padrao-d-extrabold area-10 font-titulo fs-130p mt-n70p">DUI BURGUER</p>   
                        <div class="d-flex"> 
                            <img src="../../Images/CurtLine.png" class="area-9 margin-sub">
                            <p class="font-padrao font-cont area-9 margin-curtline-font mt-20p ml-10p fs-15p">Dê um sabor à sua diversão! Na nossa lanchonete, cada <br>mordida é acompanhada pela emoção dos melhores <br>jogos. Venha se deliciar e se divertir conosco!</p>
                        </div>                     
                        <div class="d-flex margin-1-ico mt-50p">
                            <img src="../../Images/MapIco.png" width="5%" height="5%" class="area-9 ico-mob">
                            <p class="font-padrao font-cont area-9 font-icos mt-5p ml-15p fs-15p">R. Carlos de Campos, 510 - Centro, Piraju - SP, 18800-011</p>
                        </div>                     
                        <div class="d-flex margin-2-ico mt-20p">
                            <img src="../../Images/ServiceIco.png" width="5%" height="5%" class="area-9 ico-mob">
                            <p class="font-padrao font-cont area-9 font-icos mt-5p ml-15p fs-15p">Atendimento 18:30 às 23:30 - Aberto de quarta a domingo!</p>
                        </div>  
                        
                        <a href="#cardapio" class="d-none d-md-block"> <img src="../../Images/BtnHeader.png" width="35%" class="area-9 mt-50p"> </a>
                        
                    </div>
                    <div class="col-12 col-md-6">                        
                        
                    </div>
                    <div class="col-12 col-md-0 text-center">                        
                        <a href="#cardapio" class="d-md-none d-block"> <img src="../../Images/BtnHeader.png" width="35%" class="area-12 btn-header mt-50p"> </a>
                    </div>
                    <div class="col-12 col-md-12 text-center">                        
                        <div class="arrow-down"> <img src="../../Images/ArrowDown.png" class="area-12 width-arrow mt-50p"></div>
                    </div>
                </div>
            </div>
        </div>
        <!--Header-->
        <!--Promo do Dia-->
        <div class="promododia">
            <div class="margem-promodia h-25p" id="promododia" name="promododia"></div>
            <div class="text-center">
                <p class="text-fade font-padrao-semibold area-12 fs-2-5r mt-5 mb-0">Promoção do dia</p>
                <p class="text-white font-padrao-regular area-12 fs-1-6r">Veja a nossa promoção do dia!</p>
            </div>
            <div class="row">
                <div class="offset-md-3 offset-1 col-md-6 col-10">                
                    <div class="card-promo-dia area-12">
                        <div class="img-lanche">
                            <img src="../../Images/ImgFotoPromoDia.png">
                        </div>
                        <div class="row mt-2 px-3">
                            <div class="col-10">
                                <div class="row">
                                    <div class="col-12">
                                        <p class="text-white font-padrao-bold area-12 fs-1-6r mb-0">Dui Burguer</p>
                                        <div class="mb-2"><p class="text-white font-padrao-regular area-12 fs-1-2r mt-1 mb-0">Lanche Teste Para design e completar texto.</p></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <p class="text-fade font-padrao-bold area-12 mt-2 fs-1-4r">R$19.90</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <img src="../../Images/AddCartG.png" class="area-12 mt-2">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Promo do Dia-->
        <!--Cardápio-->        
        <div class="cardapio">
            <div class="margem-cardapio h-25p" id="cardapio" name="cardapio">
            </div>
            <div class="text-center">
                <p class="text-fade font-padrao-semibold area-12 fs-2-5r mt-5 mb-0">Cardápio</p>
                <p class="text-white font-padrao-regular area-12 fs-1-6r">Conheça todos os nossos produtos!</p>
            </div>
            <div class="row mt-4">
                <div class="offset-md-1 col-md-10">
                    <div class="row p-2">

                        <div class="col-12 col-md-6">
                            <div class="row bg-card h-100 br-50 mx-1">
                                <div class="col-md-3 p-0">
                                    <img src="../../Images/ImgComb.png" class="br-l50 h-100 w-100">
                                </div>
                                <div class="col-md-9 mt-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="text-white font-padrao-bold area-12 mb-0" style="font-size: 20px; margin-left: 20px;">Dui Burguer</p>
                                            <div class="h-4r"><p class="text-white font-padrao-regular area-12 mt-1" style="font-size: 16px; margin-right: 50px; margin-left: 20px;">Dui Burguer + Fritas Individuais + Refri 200ml. Dui Burguer + Fritas Individuais + Refri 200ml.Dui Burguer + Fritas Individuais + Refri 200ml.</p></div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-9">
                                            <p class="text-fade font-padrao-bold area-1 mt-2" style="font-size: 20px; margin-left: 20px;">R$23.00</p>
                                        </div>
                                        <div class="col-md-3">
                                            <div><button class="border-none bg-transp"><img src="../../Images/AddCart.png" class="area-12 ml-2"></button></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="row bg-card h-100 br-50 mx-1">
                                <div class="col-md-3 p-0">
                                    <img src="../../Images/ImgComb.png" class="br-l50 h-100 w-100">
                                </div>
                                <div class="col-md-9 mt-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="text-white font-padrao-bold area-12 mb-0" style="font-size: 20px; margin-left: 20px;">Dui Burguer</p>
                                            <div class="h-4r"><p class="text-white font-padrao-regular area-12 mt-1" style="font-size: 16px; margin-right: 50px; margin-left: 20px;">Dui Burguer + Fritas Individuais + Refri 200ml. Dui Burguer + Fritas Individuais + Refri 200ml.Dui Burguer + Fritas Individuais + Refri 200ml.</p></div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-9">
                                            <p class="text-fade font-padrao-bold area-1 mt-2" style="font-size: 20px; margin-left: 20px;">R$23.00</p>
                                        </div>
                                        <div class="col-md-3">
                                            <div><button class="border-none bg-transp"><img src="../../Images/AddCart.png" class="area-12 ml-2"></button></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>   
        </div>
        <!--Cardápio-->
        <!--Bebidas-->
        <div class="bebidas">
            <div class="margem-bebidas h-25p" id="bebidas" name="bebidas">
            </div>
            <div class="text-center">
                <p class="text-fade font-padrao-semibold area-12 fs-2-5r mt-5 mb-0">Bebidas</p>
                <p class="text-white font-padrao-regular area-12 fs-1-6r">Veja algumas de nossas bebidas!</p>
            </div>
            <div class="row mt-4">
                <div class="offset-md-1 col-md-5">
                    <div class="card-bebida item-1 area-12">
                        <div class="row">
                            <div class="col-md-2">
                                <img src="../../Images/ImgBebida1.png">
                            </div>
                            <div class="col-md-7" style="padding: 20px; padding-left: 40px; padding-top: 25px;">
                                <div>
                                    <p class="text-white font-padrao-bold area-12" style="font-size: 20px; margin-left: 20px;">Coca Cola Lata</p>
                                    <p class="text-fade font-padrao-bold area-12" style="font-size: 20px; margin-left: 20px;">R$5.00</p>
                                </div>
                            </div>
                            <div class="col-md-2" style="margin-top: -70px; padding-left: 80px;">
                                <div style="height: 110px;"></div>                                
                                <div><a href=""><img src="../../Images/AddCart.png" class="area-12"></a></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="card-bebida item-1 area-12">
                        <div class="row">
                            <div class="col-md-2">
                                <img src="../../Images/ImgBebida1.png">
                            </div>
                            <div class="col-md-7" style="padding: 20px; padding-left: 40px; padding-top: 25px;">
                                <div>
                                    <p class="text-white font-padrao-bold area-12" style="font-size: 20px; margin-left: 20px;">Coca Cola Lata Zero</p>
                                    <p class="text-fade font-padrao-bold area-12" style="font-size: 20px; margin-left: 20px;">R$5.00</p>
                                </div>
                            </div>
                            <div class="col-md-2" style="margin-top: -70px; padding-left: 80px;">
                                <div style="height: 110px;"></div>                                
                                <div><a href=""><img src="../../Images/AddCart.png" class="area-12"></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        <!--Bebidas-->
        <!--Combos Promocionais-->
        <div class="combos">
            <div class="margem-combos h-25p" id="combos" name="combos">
            </div>
            <div class="text-center">
                <p class="text-fade font-padrao-semibold area-12 fs-2-5r mt-5 mb-0">Combos Promocionais</p>
                <p class="text-white font-padrao-regular area-12 fs-1-6r">Conheça nossos combos promocionais incríveis!</p>
            </div>
            <div class="row mt-4">
                <div class="offset-md-1 col-md-5">
                    <div class="card-combo item-1 area-12">
                        <div class="row">
                            <div class="col-md-3">
                                <img src="../../Images/ImgComb.png">
                            </div>
                            <div class="col-md-7" style="padding: 20px;">
                                <div>
                                    <p class="text-white font-padrao-bold area-12" style="font-size: 20px; margin-left: 20px;">Dui Burguer</p>
                                    <div style="height: 40px;"><p class="text-white font-padrao-regular area-12" style="font-size: 14px; margin-right: 50px; margin-left: 20px;">Dui Burguer + Fritas Individuais + Refri 200ml.</p></div>
                                    <p class="text-fade font-padrao-bold area-12" style="font-size: 20px; margin-left: 20px;">R$23.00</p>
                                </div>
                            </div>
                            <div class="col-md-2" style="margin-top: 10px; padding-left: 40px;">
                                <div style="height: 90px;"><img src="../../Images/PromoIco.png" class="d-md-block d-none area-12"></div>   
                                <div><a href=""><img src="../../Images/AddCart.png" class="area-12"></a></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="card-combo item-1 area-12">
                        <div class="row">
                            <div class="col-md-3">
                                <img src="../../Images/ImgComb.png">
                            </div>
                            <div class="col-md-7" style="padding: 20px;">
                                <div>
                                    <p class="text-white font-padrao-bold area-12" style="font-size: 20px; margin-left: 20px;">Dui Vegetariano</p>
                                    <div style="height: 40px;"><p class="text-white font-padrao-regular area-12" style="font-size: 14px; margin-right: 50px; margin-left: 20px;">Dui Vegetariano + Fritas Individuais + Refri 200ml.</p></div>
                                    <p class="text-fade font-padrao-bold area-12" style="font-size: 20px; margin-left: 20px;">R$23.00</p>
                                </div>
                            </div>
                            <div class="col-md-2" style="margin-top: 10px; padding-left: 40px;">
                                <div style="height: 90px;"><img src="../../Images/PromoIco.png" class="d-md-block d-none area-12"></div>   
                                <div><a href=""><img src="../../Images/AddCart.png" class="area-12"></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>   
        </div>
        <!--Combos Promocionais-->
        <!--Avaliações-->
            <div class="avaliacoes">
                <div class="margem-avaliacoes h-25p" id="avaliacoes" name="avaliacoes">
                </div>
                <div class="text-center">
                    <p class="text-fade font-padrao-semibold area-12 fs-2-5r mt-5 mb-0">Avaliações</p>
                    <p class="text-white font-padrao-regular area-12 fs-1-6r">Veja algumas avaliações de nossos queridos clientes!</p>                
                </div>
                <div class="row mt-2">
                    <div class="offset-md-1 col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card-aval item-1 area-12">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <img src="../../Images/ImgFoto.png" class="area-12">
                                        </div>
                                        <div class="col-md-10">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p class="text-white font-padrao-semibold area-12" style="font-size: 18px;">Kauan Teshima</p>                
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <img src="../../Images/StarsIco.png" class="area-12" style="margin-top: -30px;">                
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p class="text-white font-padrao-regular area-12" style="font-size: 13px; margin-top: -10px;">Amei o lanche... Espaço super divertido com diversos jogos e videogames... a sensação de comer e jogar com meus filhos é surreal, Obrigado por me proporcionar momentos assim...</p>                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card-aval item-1 area-12">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <img src="../../Images/ImgFoto.png" class="area-12">
                                        </div>
                                        <div class="col-md-10">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p class="text-white font-padrao-semibold area-12" style="font-size: 18px;">Sthefanny Teshima</p>                
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <img src="../../Images/StarsIco.png" class="area-12" style="margin-top: -30px;">                
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p class="text-white font-padrao-regular area-12" style="font-size: 13px; margin-top: -10px;">O lanche foi ótimo! O lugar tem muitos jogos legais. Comer e brincar com meus filhos é incrível. Obrigado por isso.</p>                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card-aval item-1 area-12">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <img src="../../Images/ImgFoto.png" class="area-12">
                                        </div>
                                        <div class="col-md-10">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p class="text-white font-padrao-semibold area-12" style="font-size: 18px;">Sophia Camargo</p>                
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <img src="../../Images/StarsIco.png" class="area-12" style="margin-top: -30px;">                
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p class="text-white font-padrao-regular area-12" style="font-size: 13px; margin-top: -10px;">Amei o lanche! O espaço é super divertido, cheio de jogos e videogames. Comer e jogar com meus filhos é incrível. Obrigado por esses momentos especiais.</p>                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!--Avaliações-->
        <!--Sobre Nós-->
        <div class="sobre mb-5">
            <div class="margem-sobre h-25p" id="sobre" name="sobre">
            </div>
            <div class="text-center">
                <p class="text-fade font-padrao-semibold area-12 mb-0 mt-5 fs-2-5r">Sobre Nós</p>
                <p class="text-white font-padrao-regular area-12 fs-1-6r">Saiba um pouco sobre nossa empresa!</p>             
            </div>
            <div class="row text-center mb-3"> 
                <div class="offset-md-1 col-md-10 text-center">
                    <p class="text-white font-padrao-regular area-12 fs-1-3r mt-3">Aqui no Dui Burguer Beer, oferecemos uma experiência que não tem tamanho em diversão e sabor! Aqui, você encontrará uma ampla variedade de pratos deliciosos. Enquanto você se delicia com nossas iguarias, mergulhe em um mundo de entretenimento com nossa coleção de jogos. Venha descobrir uma experiência memorável na única Lanchonete Gamer da região, onde a diversão e o sabor se unem em grande estilo!</p>                
                </div>
                <div class="offset-md-1 col-md-10 text-center mt-5">
                    <img src="../../Images/Local.png" class="area-12 mr-1">
                    <img src="../../Images/Cel.png" class="area-12 mr-1 ml-1">
                    <img src="../../Images/Service.png" class="area-12 ml-1">
                </div>
            </div>
        </div>
        <!--Sobre Nós-->

        <!-- Visual Modal Perfil - logado -->
        <div class="modal fade" id="menuperfillogado" aria-hidden="true" aria-labelledby="menuperfillogadoLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <?php if ($usuarioLogado): ?>
                        <form method="POST" action="">
                            <div class="modal-header d-flex bg-bl" style="border-radius: 4px 4px 0px 0px;">                            
                                <p class="text-fade font-padrao-semibold modal-title" style="font-size: 1.4rem">Perfil do Usuário</p>
                                <button data-bs-dismiss="modal" aria-label="Close" class="btn-close"></button>
                            </div>
                            <div class="modal-body text-center align-perso bg-bl">
                                <input type="hidden" name="action" value="update_profile">
                                <input type="hidden" id="user_tip" name="user_tip" value="normal">
                                <!-- <img src="../../Images/ImgPhoto.png" style="width: 35%; height: 35%;"> -->
                                <div class="mb-3">
                                    <label for="user_nom" class="text-fade fw-bold fs-5">Nome:</label>
                                    <div class="bg-fade mt-2 div-input-style"><input type="text" id="user_nom" name="user_nom" class="py-3 font-padrao-semibold w-100 text-white input-style" value="<?php echo htmlspecialchars($user_nom); ?>" required></div>
                                </div>
                                <div class="mb-3">
                                    <label for="user_email" class="text-fade fw-bold fs-5">Email:</label>
                                    <div class="bg-fade mt-2 div-input-style"><input type="email" id="user_email" name="user_email" class="py-3 font-padrao-semibold w-100 text-white input-style" value="<?php echo htmlspecialchars($user_email); ?>" required></div>
                                </div>                                
                                <div class="mb-3">
                                    <label for="user_pas" class="text-fade fw-bold fs-5">Senha:</label>
                                    <div class="bg-fade mt-2 div-input-style"><input type="password" id="user_pas" name="user_pas" class="py-3 font-padrao-semibold w-100 text-white input-style"  placeholder="Digite a nova senha (se desejar alterar)"></div>
                                </div>                                
                            </div>
                            <div class="modal-footer align-perso bg-bl" style="border-radius: 0px 0px 4px 4px;">
                                <button type="submit" class="bg-fade py-2 font-padrao-semibold w-75" style="border: 0; border-radius: 50px; color: #212121;">Salvar informações</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Visual Modal Perfil - logado -->

        <!-- Visual Modal Perfil - Master -->
        <div class="modal fade" id="menuperfilmaster" aria-hidden="true" aria-labelledby="menuperfilmasterLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <?php if ($usuarioLogado): ?>
                        <form method="POST" action="">
                            <div class="modal-header d-flex bg-bl" style="border-radius: 4px 4px 0px 0px;">                            
                                <p class="text-fade font-padrao-semibold modal-title" style="font-size: 1.4rem">Perfil do Usuário</p>
                                <button data-bs-dismiss="modal" aria-label="Close" class="btn-close"></button>
                            </div>
                            <div class="modal-body text-center align-perso bg-bl">
                                <input type="hidden" name="action" value="update_profile">
                                <!-- <img src="../../Images/ImgPhoto.png" style="width: 35%; height: 35%;"> -->
                                <div class="mb-3">
                                    <label for="user_nom" class="text-fade fw-bold fs-5">Nome:</label>
                                    <div class="bg-fade mt-2 div-input-style"><input type="text" id="user_nom" name="user_nom" class="py-3 font-padrao-semibold w-100 text-white input-style" value="<?php echo htmlspecialchars($user_nom); ?>" required></div>
                                </div>         
                                <div class="mb-3">
                                    <label for="user_email" class="text-fade fw-bold fs-5">Email:</label>
                                    <div class="bg-fade mt-2 div-input-style"><input type="email" id="user_email" name="user_email" class="py-3 font-padrao-semibold w-100 text-white input-style" value="<?php echo htmlspecialchars($user_email); ?>" required></div>
                                </div>         
                                <div class="mb-3">
                                    <label for="user_pas" class="text-fade fw-bold fs-5">Senha:</label>
                                    <div class="bg-fade mt-2 div-input-style"><input type="password" id="user_pas" name="user_pas" class="py-3 font-padrao-semibold w-100 text-white input-style"  placeholder="Digite a nova senha (se desejar alterar)"></div>
                                </div>         
                                <div class="mb-3">
                                    <label for="user_tip" class="text-fade fw-bold fs-5">Tipo de Usuário</label>
                                    <div class="bg-fade mt-2 div-input-style"><select type="text" class="py-3 fw-semibold w-100 text-white input-style"  required spellcheck="false" id="user_tip" name="user_tip" autocomplete="off">
                                        <option class="text-white fw-semibold disabled" <?php echo $user_tip == 'master' ? 'selected' : ''; ?> value="master">Master</option>
                                    </select></div>
                                </div>         
                            </div>
                            <div class="modal-footer align-perso bg-bl" style="border-radius: 0px 0px 4px 4px;">
                                <button type="submit" class="bg-fade py-2 font-padrao-semibold w-75" style="border: 0; border-radius: 50px; color: #212121;">Salvar informações</button>
                                <div class="row"><a href="../administrativo/index.php" class="font-padrao-semibold text-fade">Administrativo</a></div>
                            </div>
                        </form>
                    <?php else: ?>
                        <p>Você não está logado.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Visual Modal Perfil - Master -->

        <!-- Visual Modal Perfil - Não Logado -->
        <div class="modal fade" id="menuperfil" aria-hidden="true" aria-labelledby="menuperfilLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header d-flex bg-bl" style="border-radius: 4px 4px 0px 0px;">                            
                        <p class="text-fade font-padrao-semibold modal-title" style="font-size: 1.4rem">Perfil do Usuário</p>
                        <button data-bs-dismiss="modal" aria-label="Close" class="btn-close"></button>
                    </div>
                    <div class="modal-body text-center align-perso py-5 bg-bl" style="border-radius: 0px 0px 4px 4px;">
                        <p class="text-white font-padrao-semibold modal-title" style="font-size: 1.2rem">Opss... Parece que você não tem uma conta. <a href="../login/login.php" class="text-fade font-padrao-bold" style="text-decoration: none;"><span>cadastre-se</span></a> agora em nosso site!</p>                         
                    </div>
                </div>
            </div>
        </div>
        <!-- Visual Modal Perfil - Não Logado -->

        <!-- Visual Carrinho -->
        <div class="offcanvas shadow-lg offcanvas-end" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="cartitens" aria-labelledby="cartitensLabel">
            <div class="offcanvas-header bg-bl">
                <p class="font-padrao-semibold text-fade mt-3 mb-0" style="font-size: 1.5rem;">Seu carrinho</p>
                <button type="button" class="btn-close my-0" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body bg-bl mt-0 pt-0">
                <div class="bg-fade" style="height: 1px;"></div>
                <p class="text-white mt-4"></p>
            </div>
            <div class="offcanvas-footer shadow-lg bg-bl" style="height: 4vh;">
                <a href="../order.php"><button class="bg-fade" style="width: 100%; height: 100%; border: 0;">
                    <div class="d-flex px-3">
                        <div class="text-start" style="width: 45%;"><p class="font-padrao-semibold m-0" style="color: #212121;">Meu pedido</p></div>
                        <div class="text-start" style="width: 30%;"><div class="d-flex"><img src="../../Images/IcoCart.png" width="15%" height="15%" class="mt-1"><p class="font-padrao-semibold mt-0 mb-0" style="color: #212121; margin-left: 0.4rem;">qtde</p></div></div>
                        <div class="text-end" style="width: 25%;"><p class="font-padrao-semibold m-0" style="color: #212121;">R$xx,xx</p></div>
                    </div>
                </button></a>
            </div>
        </div>
        <!-- Visual Carrinho -->
    </div>
    <script src="../script.js" ></script>  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>