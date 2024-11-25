<?php
require "bnc.php"; // Conexão com o banco de dados
$mensagem = "";
$tipoMensagem = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['last_action']) && $_SESSION['last_action'] == $_SERVER['REQUEST_URI']) {
        exit();
    }
    $_SESSION['last_action'] = $_SERVER['REQUEST_URI'];

    $pdo = Bnc::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
        // Cadastrar Categoria
        if (isset($_POST['acao']) && $_POST['acao'] == 'tbcategorias') {
            $nomecat = trim($_POST['catnome']);
            if (empty($nomecat)) {
                throw new Exception("O nome da categoria não pode estar vazio.");
            }
            $sqlCheck = "SELECT COUNT(*) FROM tbcategorias WHERE CAT_NOM = :nomecat";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute(array(':nomecat' => $nomecat));
            if ($stmtCheck->fetchColumn() > 0) {
                throw new Exception("Categoria já existente.");
            }
            $sql = "INSERT INTO tbcategorias (CAT_NOM) VALUES (:nomecat)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(':nomecat' => $nomecat));
            $mensagem = "Categoria salva com sucesso!";
            $tipoMensagem = "success";
        } 

        // Cadastrar Forma de Pagamento
        elseif (isset($_POST['acao']) && $_POST['acao'] == 'tbformapag') {
            $form_pag_desc = trim($_POST['form_pag_desc']);
            if (empty($form_pag_desc)) {
                throw new Exception("A descrição da forma de pagamento não pode estar vazia.");
            }
            $sqlCheck = "SELECT COUNT(*) FROM tbformapag WHERE FORM_PAG_DESC = :form_pag_desc";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute(array(':form_pag_desc' => $form_pag_desc));
            if ($stmtCheck->fetchColumn() > 0) {
                throw new Exception("Forma de pagamento já existente.");
            }
            $sql = "INSERT INTO tbformapag (FORM_PAG_DESC) VALUES (:form_pag_desc)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(':form_pag_desc' => $form_pag_desc));
            $mensagem = "Forma de pagamento salva com sucesso!";
            $tipoMensagem = "success";
        } 

        // Cadastrar Produto
        elseif (isset($_POST['acao']) && $_POST['acao'] == 'tbprodutos') {
            $cat_id = trim($_POST['cat_id']);
            $prod_nom = trim($_POST['prod_nom']);
            $prod_sub_nom = trim($_POST['prod_sub_nom']);
            $prod_desc = trim($_POST['prod_desc']);
            $prod_val = trim($_POST['prod_val']);
            $prod_comb = trim($_POST['prod_comb']);

            if (empty($prod_nom) || empty($prod_val)) {
                throw new Exception("Nome e valor do produto não podem estar vazios.");
            }
            $sqlCheckCat = "SELECT COUNT(*) FROM tbcategorias WHERE CAT_ID = :cat_id";
            $stmtCheckCat = $pdo->prepare($sqlCheckCat);
            $stmtCheckCat->execute(array(':cat_id' => $cat_id));
            if ($stmtCheckCat->fetchColumn() == 0) {
                throw new Exception("Categoria inválida. Por favor, selecione uma categoria existente.");
            }
            $sqlCheckProd = "SELECT COUNT(*) FROM tbprodutos WHERE PROD_NOM = :prod_nom";
            $stmtCheckProd = $pdo->prepare($sqlCheckProd);
            $stmtCheckProd->execute(array(':prod_nom' => $prod_nom));
            if ($stmtCheckProd->fetchColumn() > 0) {
                throw new Exception("Produto já existente.");
            }
            $sql = "INSERT INTO tbprodutos (CAT_ID, PROD_NOM, PROD_SUB_NOM, PROD_DESC, PROD_VAL, PROD_COMB) 
                    VALUES (:cat_id, :prod_nom, :prod_sub_nom, :prod_desc, :prod_val, :prod_comb)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':cat_id' => $cat_id,
                ':prod_nom' => $prod_nom,
                ':prod_sub_nom' => $prod_sub_nom,
                ':prod_desc' => $prod_desc,
                ':prod_val' => $prod_val,
                ':prod_comb' => $prod_comb
            ));
            $mensagem = "Produto salvo com sucesso!";
            $tipoMensagem = "success";
        } 

        // Cadastrar Usuário
        elseif (isset($_POST['acao']) && $_POST['acao'] == 'tbusuarios') {
            $user_tip = isset($_POST['user_tip']) ? trim($_POST['user_tip']) : '';
            $user_nom = trim($_POST['user_nom']);
            $user_email = trim($_POST['user_email']);
            $user_pas = trim($_POST['user_pas']);

            if (empty($user_nom) || empty($user_email) || empty($user_pas)) {
                throw new Exception("Nome, e-mail e senha do usuário não podem estar vazios.");
            }

            // Verificar se o e-mail já está cadastrado
            $sqlCheck = "SELECT COUNT(*) FROM tbusuarios WHERE USER_EMAIL = :user_email";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute(array(':user_email' => $user_email));
            if ($stmtCheck->fetchColumn() > 0) {
                throw new Exception("O e-mail já está cadastrado.");
            }

            // Criptografar a senha com hash
            $user_pas_hash = password_hash($user_pas, PASSWORD_DEFAULT);

            // Inserir usuário no banco de dados
            $sql = "INSERT INTO tbusuarios (USER_TIP, USER_NOM, USER_EMAIL, USER_PAS) VALUES (:user_tip, :user_nom, :user_email, :user_pas)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':user_tip' => $user_tip,
                ':user_nom' => $user_nom,
                ':user_email' => $user_email,
                ':user_pas' => $user_pas_hash // Usando a senha criptografada
            ));

            $mensagem = "Usuário salvo com sucesso!";
            $tipoMensagem = "success";
        }

        // Editar Categoria
        elseif (isset($_POST['acao']) && $_POST['acao'] == 'editar_categoria') {
            $cat_id = trim($_POST['cat_id']);
            $cat_nom = trim($_POST['cat_nom']);

            $sql = "UPDATE tbcategorias SET CAT_NOM = :cat_nom WHERE CAT_ID = :cat_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(':cat_nom' => $cat_nom, ':cat_id' => $cat_id));
            $mensagem = "Categoria atualizada com sucesso!";
            $tipoMensagem = "success";        

        // Editar Forma de Pagamento
        } elseif (isset($_POST['acao']) && $_POST['acao'] == 'editar_formpag') {
            $form_pag_id = trim($_POST['form_pag_id']);
            $form_pag_desc = trim($_POST['form_pag_desc']);
        
            $sql = "UPDATE tbformapag SET FORM_PAG_DESC = :form_pag_desc WHERE FORM_PAG_ID = :form_pag_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(':form_pag_desc' => $form_pag_desc, ':form_pag_id' => $form_pag_id));
            $mensagem = "Forma de pagamento atualizada com sucesso!";
            $tipoMensagem = "success";
    

        // Editar Produto
        } elseif (isset($_POST['acao']) && $_POST['acao'] == 'editar_produto') {
            $prod_id = $_POST['prod_id'];
            $cat_id = trim($_POST['cat_id']);
            $prod_nom = trim($_POST['prod_nom']);
            $prod_sub_nom = trim($_POST['prod_sub_nom']);
            $prod_desc = trim($_POST['prod_desc']);
            $prod_val = trim($_POST['prod_val']);
            $prod_comb = trim($_POST['prod_comb']);

            $sql = "UPDATE tbprodutos SET CAT_ID = :cat_id, PROD_NOM = :prod_nom, PROD_SUB_NOM = :prod_sub_nom, 
                    PROD_DESC = :prod_desc, PROD_VAL = :prod_val, PROD_COMB = :prod_comb WHERE PROD_ID = :prod_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':cat_id' => $cat_id,
                ':prod_nom' => $prod_nom,
                ':prod_sub_nom' => $prod_sub_nom,
                ':prod_desc' => $prod_desc,
                ':prod_val' => $prod_val,
                ':prod_comb' => $prod_comb,
                ':prod_id' => $prod_id
            ));
            $mensagem = "Produto atualizado com sucesso!";
            $tipoMensagem = "success";
        
        // Editar Usuario
        } elseif (isset($_POST['acao']) && $_POST['acao'] == 'editar_usuario') {
            $user_id = trim($_POST['user_id']);
            $user_tip = isset($_POST['user_tip']) ? trim($_POST['user_tip']) : '';
            $user_nom = trim($_POST['user_nom']);
            $user_email = trim($_POST['user_email']);
            $user_pas = trim($_POST['user_pas']);

            $sql = "UPDATE tbusuarios SET USER_TIP = :user_tip, USER_NOM = :user_nom, USER_EMAIL = :user_email, USER_PAS = :user_pas WHERE USER_ID = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':user_tip' => $user_tip,
                ':user_nom' => $user_nom,
                ':user_email' => $user_email,
                ':user_pas' => $user_pas,
                ':user_id' => $user_id
            ));
            $mensagem = "Usuário atualizado com sucesso!";
            $tipoMensagem = "success";
        } 
        
        // Excluir Categoria
        elseif (isset($_POST['acao']) && $_POST['acao'] == 'excluir_categoria') {
            $cat_id = trim($_POST['cat_id']);
            $sql = "DELETE FROM tbcategorias WHERE CAT_ID = :cat_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(':cat_id' => $cat_id));
            $mensagem = "Categoria excluída com sucesso!";
            $tipoMensagem = "success";
        
        // Excluir Forma de Pagamento
        } elseif (isset($_POST['acao']) && $_POST['acao'] == 'excluir_formpag') {
            $form_pag_id = trim($_POST['form_pag_id']);
            $sql = "DELETE FROM tbformapag WHERE FORM_PAG_ID = :form_pag_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(':form_pag_id' => $form_pag_id));
            $mensagem = "Forma de pagamento excluída com sucesso!";
            $tipoMensagem = "success";

        // Excluir Produto
        } elseif (isset($_POST['acao']) && $_POST['acao'] == 'excluir_produto') {
            $prod_id = trim($_POST['prod_id']);
            $sql = "DELETE FROM tbprodutos WHERE PROD_ID = :prod_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(':prod_id' => $prod_id));
            $mensagem = "Produto excluído com sucesso!";
            $tipoMensagem = "success";

        // Excluir Usuário
        } elseif (isset($_POST['acao']) && $_POST['acao'] == 'excluir_usuario') {
            $user_id = trim($_POST['user_id']);
            $sql = "DELETE FROM tbusuarios WHERE USER_ID = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(':user_id' => $user_id));
            $mensagem = "Usuário excluído com sucesso!";
            $tipoMensagem = "success";
        }

    } catch (Exception $e) {
        $mensagem = $e->getMessage();
        $tipoMensagem = "danger";
    }
    
    Bnc::desconectar();
}

function obterCategorias($pdo) {
    $sql = "SELECT * FROM tbcategorias";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obterFormPags($pdo) {
    $sql = "SELECT * FROM tbformapag";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obterProdutos($pdo) {
    $sql = "SELECT * FROM tbprodutos";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obterUsuarios($pdo) {
    $sql = "SELECT * FROM tbusuarios";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <title>Dui - Administrativo</title>
    <link rel="shortcut icon" href="../../Images/Dui.ico" type="image/x-icon">
    <style>
        .alert {
            position: absolute;
            top: 10px;
            right: 40px;
            z-index: 1050;
        }
    </style>
</head>
<body class="bg-bl">
    <div class="container mt-5">
        <?php if ($mensagem): ?>
            <div class="alert alert-<?= $tipoMensagem ?> alert-dismissible fade show" role="alert" id="alertMessage">
                <?= $mensagem ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        

        <!-- Formulário de Cadastro de Categoria -->
        <div class="mb-4">
            <h2 class="fw-bold text-fade mb-3">Cadastrar Categoria</h2>
            <form method="post">
                <input type="hidden" name="acao" value="tbcategorias">
                <div class="mb-3">
                    <label for="catnome" class="text-fade fw-bold">Nome da Categoria</label>
                    <div class="bg-fade mt-2 div-input-style"><input type="text" class="py-3 fw-semibold w-100 text-white input-style"  required spellcheck="false" id="catnome" name="catnome" autocomplete="off"></div>
                </div>
                <button type="submit" class="btn-fade">Salvar</button>
            </form>
        </div>

        <!-- Formulário de Cadastro de Forma de Pagamento -->
        <div class="mb-4">
            <h2 class="fw-bold text-fade mb-3">Cadastrar Forma de Pagamento</h2>
            <form method="post">
                <input type="hidden" name="acao" value="tbformapag">
                <div class="mb-3">
                    <label for="form_pag_desc" class="text-fade fw-bold">Descrição da Forma de Pagamento</label>        
                    <div class="bg-fade mt-2 div-input-style"><input type="text" class="py-3 fw-semibold w-100 text-white input-style"  required spellcheck="false" id="form_pag_desc" name="form_pag_desc" autocomplete="off"></div>
                </div>
                <button type="submit" class="btn-fade">Salvar</button>
            </form>
        </div>

        <!-- Formulário de Cadastro de Produto -->
        <div class="mb-4">
            <h2 class="fw-bold text-fade mb-3">Cadastrar Produto</h2>
            <form method="post">
                <input type="hidden" name="acao" value="tbprodutos">
                <div class="mb-3">
                    <label for="cat_id" class="text-fade fw-bold">Categoria</label>
                    <div class="bg-fade mt-2 div-input-style"><select type="text" class="py-3 fw-semibold w-100 text-white input-style" required spellcheck="false" id="cat_id" name="cat_id" autocomplete="off">
                        <option selected disabled></option>
                        <?php
                        $pdo = Bnc::conectar();
                        $categorias = obterCategorias($pdo);
                        foreach ($categorias as $categoria): ?>
                            <option value="<?= $categoria['CAT_ID'] ?>"><?= $categoria['CAT_NOM'] ?></option>
                        <?php endforeach; ?>
                    </select></div>
                </div>
                <div class="mb-3">
                    <label for="prod_nom" class="text-fade fw-bold">Nome do Produto</label>
                    <div class="bg-fade mt-2 div-input-style"><input type="text" class="py-3 fw-semibold w-100 text-white input-style"  required spellcheck="false" id="prod_nom" name="prod_nom" autocomplete="off"></div>
                </div>
                <div class="mb-3">
                    <label for="prod_comb" class="text-fade fw-bold">É Combo</label>
                    <div class="bg-fade mt-2 div-input-style">
                        <select type="text" class="py-3 fw-semibold w-100 text-white input-style" required spellcheck="false" id="prod_comb" name="prod_comb" autocomplete="off">
                            <option selected disabled></option>
                            <option class="text-white fw-semibold" value="sim">Sim</option>
                            <option class="text-white fw-semibold" value="não">Não</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3" id="subNameDiv" style="display: none;">
                    <label for="prod_sub_nom" class="text-fade fw-bold">Sub Nome do Produto</label>
                    <div class="bg-fade mt-2 div-input-style">
                        <input type="text" class="py-3 fw-semibold w-100 text-white input-style" required spellcheck="false" id="prod_sub_nom" name="prod_sub_nom" autocomplete="off">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="prod_desc" class="text-fade fw-bold">Descrição do Produto</label>
                    <div class="bg-fade mt-2 div-input-style"><textarea type="text" class="py-3 fw-semibold w-100 text-white input-style" required spellcheck="false" id="prod_desc" name="prod_desc" autocomplete="off"></textarea></div>
                </div>
                <div class="mb-3">
                    <label for="prod_val" class="text-fade fw-bold">Valor do Produto</label>
                    <div class="bg-fade mt-2 div-input-style"><input type="text" class="py-3 fw-semibold w-100 text-white input-style"  required spellcheck="false" id="prod_val" name="prod_val" autocomplete="off"></div>
                </div>
                <button type="submit" class="btn-fade">Salvar</button>
            </form>
        </div>

        <!-- Formulário de Cadastro de Usuário -->
        <div class="mb-4">
            <h2 class="fw-bold text-fade mb-3">Cadastrar Usuário</h2>
            <form method="post">
                <input type="hidden" name="acao" value="tbusuarios">
                <div class="mb-3">
                    <label for="user_tip" class="text-fade fw-bold">Tipo de Usuário</label>
                    <div class="bg-fade mt-2 div-input-style"><select type="text" class="py-3 fw-semibold w-100 text-white input-style"  required spellcheck="false" id="user_tip" name="user_tip" autocomplete="off">
                        <option selected disabled></option>
                        <option class="text-white fw-semibold" value="master">Master</option>
                        <option class="text-white fw-semibold" value="normal">Normal</option>
                    </select></div>
                </div>
                <div class="mb-3">
                    <label for="user_nom" class="text-fade fw-bold">Nome do Usuário</label>
                    <div class="bg-fade mt-2 div-input-style"><input type="text" class="py-3 fw-semibold w-100 text-white input-style"  required spellcheck="false" id="user_nom" name="user_nom" autocomplete="off"></div>
                </div>
                <div class="mb-3">
                    <label for="user_email" class="text-fade fw-bold">E-mail</label>
                    <div class="bg-fade mt-2 div-input-style"><input type="text" class="py-3 fw-semibold w-100 text-white input-style"  required spellcheck="false" id="user_email" name="user_email" autocomplete="off"></div>
                </div>
                <div class="mb-3">
                    <label for="user_pas" class="text-fade fw-bold">Senha</label>
                    <div class="bg-fade mt-2 div-input-style"><input type="text" class="py-3 fw-semibold w-100 text-white input-style"  required spellcheck="false" id="user_pas" name="user_pas" autocomplete="off"></div>
                </div>
                <button type="submit" class="btn-fade">Salvar</button>
            </form>
        </div>

        <!-- Listagem de Categorias -->
        <div class="mb-4">
            <h2 class="fw-bold text-fade mb-3">Categorias Cadastrados</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $categorias = obterCategorias($pdo);
                    foreach ($categorias as $categoria): ?>
                        <tr>
                            <td><?= $categoria['CAT_NOM'] ?></td>
                            <td>
                                <button class="btn-fade-table" data-bs-toggle="modal" data-bs-target="#modalEditarCategoria" 
                                        data-cat_id="<?= $categoria['CAT_ID'] ?>" 
                                        data-cat_nom="<?= $categoria['CAT_NOM'] ?>">Editar</button>
                                <form method="post" class="d-inline">
                                    <input type="hidden" name="acao" value="excluir_categoria">
                                    <input type="hidden" name="cat_id" value="<?= $categoria['CAT_ID'] ?>">
                                    <button type="submit" class="btn-delete-table" onclick="return confirm('Você tem certeza que deseja excluir?');">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Listagem de Formas de Pagamento -->
        <div class="mb-4">
            <h2 class="fw-bold text-fade mb-3">Formas de Pagamentos Cadastrados</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $formpags = obterFormPags($pdo);
                    foreach ($formpags as $formpag): ?>
                        <tr>
                            <td><?= $formpag['FORM_PAG_DESC'] ?></td>
                            <td>
                                <button class="btn-fade-table" data-bs-toggle="modal" data-bs-target="#modalEditarFormPag" 
                                        data-form_pag_id="<?= $formpag['FORM_PAG_ID'] ?>" 
                                        data-form_pag_desc="<?= $formpag['FORM_PAG_DESC'] ?>">Editar</button>
                                <form method="post" class="d-inline">
                                    <input type="hidden" name="acao" value="excluir_formpag">
                                    <input type="hidden" name="form_pag_id" value="<?= $formpag['FORM_PAG_ID'] ?>">
                                    <button type="submit" class="btn-delete-table" onclick="return confirm('Você tem certeza que deseja excluir?');">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Listagem de Produtos -->
        <div class="mb-4">
            <h2 class="fw-bold text-fade mb-3">Produtos Cadastrados</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Categoria</th>
                        <th>Valor</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $produtos = obterProdutos($pdo);
                    foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?= $produto['PROD_NOM'] ?></td>
                            <td><?= $produto['CAT_ID'] ?></td>
                            <td><?= $produto['PROD_VAL'] ?></td>
                            <td>
                                <button class="btn-fade-table" data-bs-toggle="modal" data-bs-target="#modalEditarProduto" 
                                        data-prod_id="<?= $produto['PROD_ID'] ?>" 
                                        data-cat_id="<?= $produto['CAT_ID'] ?>" 
                                        data-prod_nom="<?= $produto['PROD_NOM'] ?>" 
                                        data-prod_sub_nom="<?= $produto['PROD_SUB_NOM'] ?>" 
                                        data-prod_desc="<?= $produto['PROD_DESC'] ?>" 
                                        data-prod_val="<?= $produto['PROD_VAL'] ?>" 
                                        data-prod_comb="<?= $produto['PROD_COMB'] ?>">Editar</button>
                                <form method="post" class="d-inline">
                                    <input type="hidden" name="acao" value="excluir_produto">
                                    <input type="hidden" name="prod_id" value="<?= $produto['PROD_ID'] ?>">
                                    <button type="submit" class="btn-delete-table" onclick="return confirm('Você tem certeza que deseja excluir?');">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Listagem de Usuarios -->
        <div class="mb-4">
            <h2 class="fw-bold text-fade mb-3">Usuários Cadastrados</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Nome</th>
                        <th>E-Mail</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $usuarios = obterUsuarios($pdo);
                    foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= $usuario['USER_TIP'] ?></td>
                            <td><?= $usuario['USER_NOM'] ?></td>
                            <td><?= $usuario['USER_EMAIL'] ?></td>
                            <td>
                                <button class="btn-fade-table" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario" 
                                        data-user_id="<?= $usuario['USER_ID'] ?>" 
                                        data-user_tip="<?= $usuario['USER_TIP'] ?>" 
                                        data-user_nom="<?= $usuario['USER_NOM'] ?>" 
                                        data-user_email="<?= $usuario['USER_EMAIL'] ?>" 
                                        data-user_pas="<?= $usuario['USER_PAS'] ?>">Editar</button>
                                <form method="post" class="d-inline">
                                    <input type="hidden" name="acao" value="excluir_usuario">
                                    <input type="hidden" name="user_id" value="<?= $usuario['USER_ID'] ?>">
                                    <button type="submit" class="btn-delete-table" onclick="return confirm('Você tem certeza que deseja excluir?');">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de Edição de Categoria -->
    <div class="modal fade" id="modalEditarCategoria" tabindex="-1" aria-labelledby="modalEditarCategoriaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-bl">
                <div class="modal-header">
                    <h3 class="modal-title fw-bold text-fade" id="modalEditarCategoriaLabel">Editar Categoria</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="formEditarCategoria">
                        <input type="hidden" name="acao" value="editar_categoria">
                        <input type="hidden" name="cat_id" id="cat_id">
                        <div class="mb-3">
                            <label for="cat_nom" class="text-fade fw-bold">Nome da Categoria</label>
                            <div class="bg-fade mt-2 div-input-style"><input type="text" class="py-3 fw-semibold w-100 text-white input-style" required spellcheck="false" id="cat_nom" name="cat_nom" autocomplete="off"></div>
                        </div>
                        <button type="submit" class="btn-fade">Atualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Edição de Forma de Pagamento -->
    <div class="modal fade" id="modalEditarFormPag" tabindex="-1" aria-labelledby="modalEditarFormPagLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-bl">
                <div class="modal-header">
                    <h3 class="modal-title fw-bold text-fade" id="modalEditarFormPagLabel">Editar Forma de Pagamento</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="formEditarFormPag">
                        <input type="hidden" name="acao" value="editar_formpag">
                        <input type="hidden" name="form_pag_id" id="form_pag_id">
                        <div class="mb-3">
                            <label for="form_pag_desc" class="text-fade fw-bold">Descrição da Forma de Pagamento</label>
                            <div class="bg-fade mt-2 div-input-style"><input type="text" class="py-3 fw-semibold w-100 text-white input-style" required spellcheck="false" id="form_pag_desc" name="form_pag_desc" autocomplete="off"></div>
                        </div>
                        <button type="submit" class="btn-fade">Atualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Edição de Produto -->
    <div class="modal fade" id="modalEditarProduto" tabindex="-1" aria-labelledby="modalEditarProdutoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-bl">
                <div class="modal-header">
                    <h3 class="modal-title fw-bold text-fade" id="modalEditarProdutoLabel">Editar Produto</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="formEditarProduto">
                        <input type="hidden" name="acao" value="editar_produto">
                        <input type="hidden" name="prod_id" id="prod_id">
                        <div class="mb-3">
                        <label for="cat_id" class="text-fade fw-bold">Categoria</label>
                        <div class="bg-fade mt-2 div-input-style"><select type="text" class="py-3 fw-semibold w-100 text-white input-style" required spellcheck="false" id="cat_id" name="cat_id" autocomplete="off">
                                <option selected disabled>Selecione uma categoria</option>
                                <?php foreach ($categorias as $categoria): ?>
                                    <option class="text-white fw-semibold" value="<?= $categoria['CAT_ID'] ?>"><?= $categoria['CAT_NOM'] ?></option>
                                <?php endforeach; ?>
                            </select></div>
                        </div>
                        <div class="mb-3">
                            <label for="prod_nom" class="text-fade fw-bold">Nome do Produto</label>
                            <div class="bg-fade mt-2 div-input-style"><input type="text" class="py-3 fw-semibold w-100 text-white input-style" required spellcheck="false" id="prod_nom" name="prod_nom" autocomplete="off"></div>
                        </div>
                        <div class="mb-3">
                            <label for="prod_comb" class="text-fade fw-bold">É Combo:</label>
                            <div class="bg-fade mt-2 div-input-style"><select type="text" class="py-3 fw-semibold w-100 text-white input-style" required spellcheck="false" id="prod_comb" name="prod_comb" autocomplete="off">
                                <option class="fw-semibold" value="Sim">Sim</option>
                                <option class="fw-semibold" value="Não">Não</option>
                            </select></div>
                        </div>
                        <div class="mb-3">
                            <label for="prod_sub_nom" class="text-fade fw-bold">Sub Nome do Produto</label>
                            <div class="bg-fade mt-2 div-input-style"><input type="text" class="py-3 fw-semibold w-100 text-white input-style" required spellcheck="false" id="prod_sub_nom" name="prod_sub_nom" autocomplete="off"></div>
                        </div>
                        <div class="mb-3">
                            <label for="prod_desc" class="text-fade fw-bold">Descrição do Produto</label>
                            <div class="bg-fade mt-2 div-input-style"><input type="text" class="py-3 fw-semibold w-100 text-white input-style" required spellcheck="false" id="prod_desc" name="prod_desc" autocomplete="off"></div>
                        </div>
                        <div class="mb-3">
                            <label for="prod_val" class="text-fade fw-bold">Valor do Produto</label>
                            <div class="bg-fade mt-2 div-input-style"><input type="text" class="py-3 fw-semibold w-100 text-white input-style" required spellcheck="false" id="prod_val" name="prod_val" autocomplete="off"></div>
                        </div>
                        <button type="submit" class="btn-fade">Atualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Edição de Usuário -->
    <div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-bl">
                <div class="modal-header">
                    <h3 class="modal-title fw-bold text-fade" id="modalEditarUsuarioLabel">Editar Usuário</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="formEditarUsuario">
                        <input type="hidden" name="acao" value="editar_usuario">
                        <input type="hidden" name="user_id" id="user_id">
                        <div class="mb-3">
                            <label for="user_tip" class="text-fade fw-bold">Tipo do Usuário</label>
                            <div class="bg-fade mt-2 div-input-style"><select type="text" class="py-3 fw-semibold w-100 text-white input-style" required spellcheck="false" id="user_tip" name="user_tip" autocomplete="off">
                                <option class="text-white fw-semibold" value="master">Master</option>
                                <option class="text-white fw-semibold" value="normal">Normal</option>
                            </select></div>
                        </div>
                        <div class="mb-3">
                            <label for="user_nom" class="text-fade fw-bold">Nome do Usuário</label>
                            <div class="bg-fade mt-2 div-input-style"><input type="text" class="py-3 fw-semibold w-100 text-white input-style" required spellcheck="false" id="user_nom" name="user_nom" autocomplete="off"></div>
                        </div>
                        <div class="mb-3">
                            <label for="user_email" class="text-fade fw-bold">E-Mail do Usuário</label>
                            <div class="bg-fade mt-2 div-input-style"><input type="text" class="py-3 fw-semibold w-100 text-white input-style" required spellcheck="false" id="user_email" name="user_email" autocomplete="off"></div>
                        </div>
                        <div class="mb-3">
                            <label for="user_pas" class="text-fade fw-bold">Senha do Usuário</label>
                            <div class="bg-fade mt-2 div-input-style"><input type="text" class="py-3 fw-semibold w-100 text-white input-style" required spellcheck="false" id="user_pas" name="user_pas" autocomplete="off"></div>
                        </div>
                        <button type="submit" class="btn-fade">Atualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Editar da categoria //
        var modalEditarCategoria = document.getElementById('modalEditarCategoria');
        modalEditarCategoria.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var catId = button.getAttribute('data-cat_id');
            var catNom = button.getAttribute('data-cat_nom');
            
            var modalCatId = modalEditarCategoria.querySelector('#cat_id');
            var modalCatNom = modalEditarCategoria.querySelector('#cat_nom');
            
            modalCatId.value = catId;
            modalCatNom.value = catNom;
        });

        // Editar da forma de pagamento //
        var modalEditarFormPag = document.getElementById('modalEditarFormPag');
        modalEditarFormPag.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var formPagId = button.getAttribute('data-form_pag_id');
            var formPagDesc = button.getAttribute('data-form_pag_desc');
            
            var modalFormPagId = modalEditarFormPag.querySelector('#form_pag_id');
            var modalFormPagDesc = modalEditarFormPag.querySelector('#form_pag_desc');
            
            modalFormPagId.value = formPagId;
            modalFormPagDesc.value = formPagDesc;
        });
        
        // Editar do produto //
        var modalEditarProduto = document.getElementById('modalEditarProduto');
        modalEditarProduto.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var prodId = button.getAttribute('data-prod_id');
            var catId = button.getAttribute('data-cat_id');
            var prodNom = button.getAttribute('data-prod_nom');
            var prodSubNom = button.getAttribute('data-prod_sub_nom');
            var prodDesc = button.getAttribute('data-prod_desc');
            var prodVal = button.getAttribute('data-prod_val');
            var prodComb = button.getAttribute('data-prod_comb');

            var modalProdId = modalEditarProduto.querySelector('#prod_id');
            var modalCatId = modalEditarProduto.querySelector('#cat_id');
            var modalProdNom = modalEditarProduto.querySelector('#prod_nom');
            var modalProdSubNom = modalEditarProduto.querySelector('#prod_sub_nom');
            var modalProdDesc = modalEditarProduto.querySelector('#prod_desc');
            var modalProdVal = modalEditarProduto.querySelector('#prod_val');
            var modalProdComb = modalEditarProduto.querySelector('#prod_comb');

            modalProdId.value = prodId;
            modalCatId.value = catId;
            modalProdNom.value = prodNom;
            modalProdSubNom.value = prodSubNom;
            modalProdDesc.value = prodDesc;
            modalProdVal.value = prodVal;
            modalProdComb.value = prodComb;
        });

        // Editar do usuário //
        var modalEditarUsuario = document.getElementById('modalEditarUsuario');
        modalEditarUsuario.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var userId = button.getAttribute('data-user_id');
            var userTip = button.getAttribute('data-user_tip');
            var userNom = button.getAttribute('data-user_nom');
            var userEmail = button.getAttribute('data-user_email');
            var userPas = button.getAttribute('data-user_pas');

            var modalUserId = modalEditarUsuario.querySelector('#user_id');
            var modalUserTip = modalEditarUsuario.querySelector('#user_tip');
            var modalUserNom = modalEditarUsuario.querySelector('#user_nom');
            var modalUserEmail = modalEditarUsuario.querySelector('#user_email');
            var modalUserPas = modalEditarUsuario.querySelector('#user_pas');

            modalUserId.value = userId;
            modalUserTip.value = userTip;
            modalUserNom.value = userNom;
            modalUserEmail.value = userEmail;
            modalUserPas.value = userPas;
        });

        </script>

        <?php if ($mensagem): ?>
            <script>
                const alertMessage = document.getElementById('alertMessage');
                setTimeout(function() {
                    if (alertMessage) {
                        alertMessage.classList.remove('show');
                        alertMessage.classList.add('fade');
                    }
                }, 3000);
            </script>
        <?php endif; ?>

        <script>
            // Seleciona o elemento do select e a div
            const prodComb = document.getElementById('prod_comb');
            const subNameDiv = document.getElementById('subNameDiv');

            // Função para exibir ou esconder a div com base no valor selecionado
            function toggleSubNameDiv() {
                if (prodComb.value === "sim") {
                    subNameDiv.style.display = 'block'; // Exibe a div se "sim" for selecionado
                } else {
                    subNameDiv.style.display = 'none'; // Esconde a div para qualquer outro valor
                }
            }

            // Adiciona evento ao select para monitorar mudanças
            prodComb.addEventListener('change', toggleSubNameDiv);

            // Chama a função inicialmente para definir o estado correto ao carregar a página
            toggleSubNameDiv();
        </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>  
