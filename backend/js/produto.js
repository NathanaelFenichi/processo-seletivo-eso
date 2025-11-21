$(document).ready(function () {

    const urlParams = new URLSearchParams(window.location.search);
    const itemId = urlParams.get('id');
    const $container = $("#produto-detalhes");

    if (!itemId) {
        $container.html('<p style="color: red;">Erro: ID do item não fornecido.</p>');
        return;
    }

    async function carregarDetalhes() {
        try {
            const response = await fetch(`https://fortnite-api.com/v2/cosmetics/br/${itemId}?language=pt-BR`);
            const data = await response.json();

            if (data && data.data) {
                const item = data.data;
                const detalhesHtml = criarDetalhesHtml(item);
                $container.html(detalhesHtml);

                configurarBotoes(item);

            } else {
                $container.html('<p style="color: red;">Erro: Item não encontrado.</p>');
            }

        } catch (error) {
            console.error("Erro ao carregar detalhes:", error);
            $container.html('<p style="color: red;">Erro ao conectar com a API.</p>');
        }
    }

    function criarDetalhesHtml(item) {
        const nome = item.name || "Nome não disponível";
        const tipo = item.type ? item.type.displayValue : "Tipo não disponível";
        const raridade = item.rarity ? item.rarity.displayValue : "Comum";
        const descricao = item.description || "Descrição não disponível.";
        const imagem = item.images ? item.images.icon : "https://via.placeholder.com/300?text=Sem+Imagem";

        return `
            <div class="produto-header">
                <img src="${imagem}" alt="${nome}" class="produto-img" onerror="this.src='https://via.placeholder.com/300?text=Erro'">
                <div class="produto-info">
                    <h1>${nome}</h1>
                    <p class="tipo">${tipo}</p>
                    <p class="raridade raridade-${raridade.toLowerCase()}">${raridade}</p>
                    <p class="descricao">${descricao}</p>
                </div>
            </div>

            <div class="produto-acoes">
                <button id="btn-comprar" class="btn btn-comprar">Comprar</button>
                <button id="btn-devolver" class="btn btn-devolver" style="display: none;">Devolver</button>
                <button id="btn-voltar" class="btn btn-voltar">Voltar à Loja</button>
            </div>
        `;
    }

    function configurarBotoes(item) {
        const $btnComprar = $("#btn-comprar");
        const $btnDevolver = $("#btn-devolver");
        const $btnVoltar = $("#btn-voltar");

        $btnVoltar.click(function () {
            window.location.href = "../catalogo.php";
        });

        verificarPosse(item.id).then(temItem => {
            if (temItem) {
                $btnComprar.hide();
                $btnDevolver.show();

                $btnDevolver.click(function () {
                    devolverItem(item.id);
                });

            } else {
                $btnComprar.show();
                $btnDevolver.hide();

                $btnComprar.click(function () {
                    comprarItem(item.id, item.price || 1000);
                });
            }
        });
    }

    async function verificarPosse(id) {
        try {
            const response = await fetch('../backend/verifica_Posse.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            });
            const data = await response.json();
            return data.tem_item || false;
        } catch (error) {
            console.error("Erro ao verificar posse:", error);
            return false;
        }
    }

    async function comprarItem(id, preco) {
        try {
            const response = await fetch('../backend/comprar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id, preco: preco })
            });
            const data = await response.json();

            if (data.sucesso) {
                alert("Compra realizada com sucesso!");
                location.reload();
            } else {
                alert("Erro: " + data.msg);
            }
        } catch (error) {
            console.error("Erro ao comprar:", error);
            alert("Erro ao processar a compra.");
        }
    }

    async function devolverItem(id) {
        if (!confirm("Tem certeza que deseja devolver este item?")) return;

        try {
            const response = await fetch('../backend/devolver.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_compra: id })
            });
            const data = await response.json();

            if (data.sucesso) {
                alert("Item devolvido com sucesso!");
                location.reload();
            } else {
                alert("Erro: " + data.msg);
            }
        } catch (error) {
            console.error("Erro ao devolver:", error);
            alert("Erro ao processar a devolução.");
        }
    }

    carregarDetalhes();
});
