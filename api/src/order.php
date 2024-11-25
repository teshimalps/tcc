<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="shortcut icon" href="../Images/Dui.ico" type="image/x-icon">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.8/inputmask.min.js"></script>
    <title>Seu Pedido</title>
</head>
<body>
    <div class="index bg-bl h-100">
        <div class="row">
            <div class="offset-md-3 col-md-6 offset-1 col-10 mt-4 d-flex justify-content-between">
                <a href="home/index.php" class="bx bx-arrow-back bx-md text-fade text-dec-none"></a>
                <p class="text-fade fw-semibold fs-1-4r">Meu pedido</p>
                <a href="" class="bx bx-arrow-back bx-md text-dec-none color-transp"></a>
            </div>
            <div class="offset-md-3 col-md-6 offset-1 col-10 bg-fade h-1"></div>
            <div class="offset-md-3 col-md-6 offset-1 col-10 mt-4 d-flex justify-content-between mb-0">
                <p class="text-white font-padrao-medium mb-0 fs-1-4r">Cont</p>
            </div>
            <div class="offset-md-3 col-md-6 offset-1 col-10 mt-4">
                <a href="home/index.php"><button class="bg-fade py-3 fw-bold w-100 color-bl fs-1r border-none br-20">Continuar comprando</button></a>
            </div>
            <div class="offset-md-3 col-md-6 offset-1 col-10 mt-4 mb-3 d-flex justify-content-between">
                <p class="text-fade fw-bold fs-1-4r">Total</p>
                <p disabled></p>
                <p class="text-fade fw-bold fs-1-4r">R$xx,xx</p>
            </div>
        </div>
        <div class="row">        
            <div class="offset-md-3 col-md-6 offset-1 col-10 d-flex justify-content-between">
                <p class="text-fade fw-semibold fs-1-4r">Escolha uma opção</p>
                <p class="text-fade fw-semibold fs-1-4r" disabled></p>
                <p class="fw-semibold bg-fade py-1 px-3 fs-1r color-bl br-25">Obrigatório</p>
            </div>
        </div>
        <div class="row">        
            <div class="offset-md-3 col-md-6 offset-1 col-10 mt-3">
            <div class="row">
                <div class="col-md-11 mb-3">
                    <p class="text-fade fw-semibold m-0 fs-1-2r">Entrega</p>        
                    <p class="text-fade fw-semibold mt-1 fs-1r">(Sujeito a disponibilidade e taxas)</p>        
                </div>
                <div class="col-md-1">
                    <div class="form-check">
                        <input class="form-check-input p-3 mt-3" type="radio" name="delivery" id="delivery1">
                    </div>
                </div>
            </div>
            <div class="row bg-fade h-1"></div>
            <div class="row mt-4">
                <div class="col-md-11 mb-3">
                    <p class="text-fade fw-semibold m-0 fs-1-2r">Retirada (30min)</p>        
                    <p class="text-fade fw-semibold mt-1 fs-1r">(Sujeito a disponibilidade)</p>        
                </div>
                <div class="col-md-1">
                    <div class="form-check">
                        <input class="form-check-input p-3 mt-3" type="radio" name="delivery" id="delivery2">
                    </div>
                </div>
            </div>
                <div class="row bg-fade h-1"></div>
            </div>
        </div>
        <div class="row">        
            <div class="offset-md-3 col-md-6 offset-1 col-10 mt-4">
                <div class="d-flex justify-content-between m-0">
                    <p class="text-fade fw-semibold m-0 fs-1-2r">Nome e Sobrenome</p>
                    <p class="text-fade fw-semibold m-0 fs-1-2r" disabled></p>
                    <p class="fw-semibold bg-fade py-1 px-3 m-0 fs-1r color-bl br-25">Obrigatório</p>
                </div>
                <div class="bg-fade mt-2 div-input-style"><input class="py-3 fw-semibold w-100 text-white input-style" type="text" required spellcheck="false" id="nom_cli_delivery" name="nom_cli_delivery" autocomplete="off"></div>
            </div>
            <div class="offset-md-3 col-md-6 offset-1 col-10 mt-4" id="addressDiv" style="display: none;">
                <div class="d-flex justify-content-between m-0">
                    <p class="text-fade fw-semibold m-0 fs-1-2r">Qual seu endereço?</p>
                    <p class="text-fade fw-semibold m-0 fs-1-2r" disabled></p>
                    <p class="fw-semibold bg-fade py-1 px-3 m-0 fs-1r color-bl br-25">Obrigatório</p>
                </div>
                <div class="bg-fade mt-3 div-input-style"><button class="py-3 fw-semibold w-100 input-style" data-bs-target="#enderecoentrega" data-bs-toggle="modal"><i class="bx bx-current-location text-fade"></i><span class="text-fade ml-5p">Selecione um endereço</span></button></div>
            </div>
            <div class="offset-md-3 col-md-6 offset-1 col-10 mt-4">
                <div class="d-flex justify-content-between m-0">
                    <p class="text-fade fw-semibold m-0 fs-1-2r">Número de celular</p>
                    <p class="text-fade fw-semibold m-0 fs-1-2r" disabled></p>
                    <p class="fw-semibold bg-fade py-1 px-3 m-0 fs-1r color-bl br-25">Obrigatório</p>            
                </div>
                <div class="bg-fade mt-2 div-input-style"><input class="py-3 fw-semibold w-100 text-white input-style" type="text" required spellcheck="false" id="num_cli_delivery" name="num_cli_delivery" autocomplete="off" maxlength="15"></div>
                <p class="text-white fw-semibold mt-2 mb-0 fs-0-8r">O número do celular será utilizado para te atualizar sobre o status do seu pedido, além de te identificar para agilizar os próximos pedidos.</p>
            </div>
            <div class="offset-md-3 col-md-6 offset-1 col-10 mt-4">
                <div class="d-flex justify-content-between m-0">
                    <p class="text-fade fw-semibold m-0 fs-1-2r">Como deseja pagar?</p>
                    <p class="text-fade fw-semibold m-0 fs-1-2r" disabled></p>
                    <p class="fw-semibold bg-fade py-1 px-3 m-0 fs-1r color-bl br-25">Obrigatório</p>
                </div>
                <div class="bg-fade mt-3 div-input-style"><button class="py-3 fw-semibold w-100 input-style" data-bs-target="#formspag" data-bs-toggle="modal"><i class="bx bx-wallet text-fade"></i><span class="text-fade ml-5p">Formas de pagamento</span></button></div>
                <p class="text-white text-center fw-semibold mt-3 fs-0-8r">Ao enviar seu pedido, você concorda com os Termos de Serviço e Política de Uso de Dados do restaurante.</p>
                <button class="bg-fade py-3 fw-bold w-100 mt-1 mb-5 border-none br-20 color-bl fs-1r">Finalizar Pedido</button>
            </div>
        </div>
    </div>
    <script>
        // Seleciona os radio buttons e a div do endereço
        const delivery1 = document.getElementById('delivery1');
        const addressDiv = document.getElementById('addressDiv');

        // Função para verificar o estado do radio button e exibir/esconder a div
        function toggleAddressDiv() {
            if (delivery1.checked) {
                addressDiv.style.display = 'block'; // Exibe a div se delivery1 estiver selecionado
            } else {
                addressDiv.style.display = 'none'; // Esconde a div se não estiver
            }
        }

        // Adiciona um evento de alteração nos radio buttons
        delivery1.addEventListener('change', toggleAddressDiv);
        const delivery2 = document.getElementById('delivery2');
        delivery2.addEventListener('change', toggleAddressDiv);

        // Chama a função inicialmente para definir o estado correto ao carregar a página
        toggleAddressDiv();
    </script>

    <!-- Visual Modal Endereço -->
    <div class="modal fade" id="enderecoentrega" aria-hidden="true" aria-labelledby="enderecoentregaLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content ">
                <div class="modal-header d-flex bg-bl" style="border-radius: 4px 4px 0px 0px;">                            
                    <p class="text-fade font-padrao-semibold modal-title" style="font-size: 1.4rem">Endereço de Entrega</p>
                    <button data-bs-dismiss="modal" aria-label="Close" class="btn-close"></button>
                </div>
                <div class="modal-body text-center align-perso py-4 bg-bl">
                    <div class="row">
                        <div class="col-md-10 col-12 mb-4">
                            <div class="d-flex justify-content-between m-0">
                                <p class="text-fade fw-semibold m-0 fs-1-2r">Endereço de Entrega</p>
                                <p class="text-fade fw-semibold m-0 fs-1-2r" disabled></p>
                                <p class="fw-semibold bg-fade py-1 px-3 m-0 fs-1r color-bl br-25">Obrigatório</p>
                            </div>
                            <div class="bg-fade mt-2 div-input-style"><input class="py-3 fw-semibold w-100 text-white input-style" type="text" required spellcheck="false" id="end_delivery" name="end_entrega" autocomplete="off" placeholder="Ex: R. Carlos de Campos - Centro"></div>
                        </div>
                        <div class="col-md-2 col-12 mb-4">
                            <div class="d-flex justify-content-between m-0">
                                <p class="text-fade fw-semibold m-0 fs-1-2r">Número</p>
                            </div>
                            <div class="bg-fade mt-2 div-input-style"><input class="py-3 fw-semibold w-100 text-white input-style" type="text" required spellcheck="false" id="num_end_delivery" name="num_end_delivery" autocomplete="off" placeholder="Ex: 226"></div>
                        </div>
                        <div class="col-md-8 col-12">
                            <div class="d-flex justify-content-between m-0">
                                <p class="text-fade fw-semibold m-0 fs-1-2r">CEP</p>
                                <p class="text-fade fw-semibold m-0 fs-1-2r" disabled></p>
                                <p class="fw-semibold bg-fade py-1 px-3 m-0 fs-1r color-bl br-25">Obrigatório</p>
                            </div>
                            <div class="bg-fade mt-2 div-input-style">
                                <input class="py-3 fw-semibold w-100 text-white input-style" type="text" required spellcheck="false" id="cep_delivery" name="cep_delivery" autocomplete="off" placeholder="Ex: 18800-000" maxlength="9">
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="d-flex justify-content-between m-0">
                                <p class="text-fade fw-semibold m-0 fs-1-2r">Complemento</p>
                                <p class="text-fade fw-semibold m-0 fs-1-2r" disabled></p>
                                <p class="fw-semibold bg-cinza py-1 px-3 m-0 fs-1r color-bl br-25">Opcional</p>
                            </div>
                            <div class="bg-fade mt-2 div-input-style"><input class="py-3 fw-semibold w-100 text-white input-style" type="text" required spellcheck="false" id="comple_delivery" name="comple_delivery" autocomplete="off" placeholder="Ex: Bombeiro"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer align-perso bg-bl" style="border-radius: 0px 0px 4px 4px;">
                    <button type="submit" class="bg-fade py-2 font-padrao-semibold w-75" style="border: 0; border-radius: 50px; color: #212121;">Salvar informações</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Visual Modal Endereço -->

    <!-- Visual Modal Formas de Pagamento -->
    <div class="modal fade" id="formspag" aria-hidden="true" aria-labelledby="formspagLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex bg-bl" style="border-radius: 4px 4px 0px 0px;">                            
                    <p class="text-fade font-padrao-semibold modal-title" style="font-size: 1.4rem">Formas de Pagamentos</p>
                    <button data-bs-dismiss="modal" aria-label="Close" class="btn-close"></button>
                </div>
                <div class="modal-body text-center align-perso py-5 bg-bl" style="border-radius: 0px 0px 4px 4px;">
                    <p class="text-white font-padrao-semibold modal-title" style="font-size: 1.2rem">Opss... Parece que você não tem uma conta. <a href="../login/login.php" class="text-fade font-padrao-bold" style="text-decoration: none;"><span>cadastre-se</span></a> agora em nosso site!</p>                         
                </div>
            </div>
        </div>
    </div>
    <!-- Visual Modal Formas de Pagamento -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Máscara para o CEP
            Inputmask({ mask: "99999-999", placeholder: " " }).mask(document.getElementById("cep_delivery"));
            // Máscara para o número de celular
            Inputmask({ mask: "(99) 99999-9999", placeholder: " " }).mask(document.getElementById("num_cli_delivery"));
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.8/dist/inputmask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>