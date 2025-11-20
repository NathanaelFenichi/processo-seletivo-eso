<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Detalhes do Item</title>
  
  <!-- CSS -->
  <link rel="stylesheet" href="../css/geral.css" />
  <link rel="stylesheet" href="../css/produto.css" />

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="../backend/js/produto.js"></script>
</head>
<body>

  <!-- Inclui a Navbar -->
  <?php include 'nav.php'; ?>
  
  <main class="fundo-moderno">
    
    <div class="card-produto-especial">
      
      <!-- Lado Esquerdo: Imagem -->
      <div class="box-imagem">
         <!-- Imagem padrão de carregamento -->
         <img id="imagem" src="../img/loading.gif" alt="Carregando..." onerror="this.src='../img/sem-imagem.png'">
      </div>

      <!-- Lado Direito: Informações -->
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
          <p id="descricao">Buscando informações na base de dados...</p>
        </div>

        <div class="rodape-acao">
          <div class="preco-box">
            <span class="label">Preço Atual:</span>
            <p class="valor-final" id="preco">Verificando...</p>
          </div>

          <div class="botoes-wrapper">
            <!-- Botão Comprar (Só aparece se estiver na loja) -->
            <button class="btn-acao btn-azul btn-comprar" style="display:none;">
              Adicionar ao Carrinho
            </button>
            
            <!-- Botão Voltar -->
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