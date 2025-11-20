<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Detalhes - Fortnite</title>
  
  <link rel="stylesheet" href="../css/geral.css" />
  <link rel="stylesheet" href="../css/produto.css" />

  <script src="../backend/js/jquery-3.7.1.min.js"></script>
  <script src="../backend/js/produto.js"></script>
</head>
<body>

  <?php include '../paginas/nav.php'; ?>
  
  <main class="fundo-moderno">
    
    <div class="card-produto-especial">
      
      <div class="box-imagem">
         <img id="imagem" src="../img/loading.gif" alt="Skin" />
      </div>

      <div class="box-info">
        
        <div class="cabecalho">
          <h1 id="nome">Carregando...</h1>
          <div class="meta-dados">
             <span class="badge-tipo" id="tipo">--</span>
             <span class="badge-raridade" id="raridade">--</span>
          </div>
        </div>

        <div class="descricao-container-cinza">
          <div class="desc-header">
            <h3>Sobre o item</h3>
            <span class="icone-info">i</span>
          </div>
          <p id="descricao">Buscando informações...</p>
        </div>

        <div class="rodape-acao">
          <div class="preco-box">
            <span class="label">Valor atual:</span>
            <p class="valor-final" id="preco">...</p>
          </div>

          <div class="botoes-wrapper">
            <button class="btn-acao btn-azul btn-comprar" style="display:none;">
              Adicionar
            </button>
            <button class="btn-acao btn-vermelho btn-voltar">
              Voltar
            </button>
          </div>
        </div>

      </div>

    </div>
  </main>

</body>
</html>