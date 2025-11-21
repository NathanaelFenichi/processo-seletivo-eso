
const modal = document.getElementById("modalPerfil");
const botoes = document.querySelectorAll(".btn-ver-perfil");
const closeBtn = document.querySelector(".close-btn");
const btnFecharFooter = document.querySelector(".btn-fechar");


const modalNome = document.getElementById("modal-titulo");
const modalEmail = document.getElementById("modal-email");
const modalItem = document.getElementById("modal-item");
const modalData = document.getElementById("modal-data");


botoes.forEach(botao => {
    botao.addEventListener("click", function() {
 
        const nome = this.dataset.nome;
        const email = this.dataset.email;
        const item = this.dataset.ultimaCompra; 
        const data = this.dataset.dataCompra;


        modalNome.innerText = "Perfil de " + nome;
        modalEmail.innerText = email;
        modalItem.innerText = item;
        modalData.innerText = data;


        modal.classList.add("show");
    });
});

function fecharModal() {
    modal.classList.remove("show");
}

closeBtn.addEventListener("click", fecharModal);
btnFecharFooter.addEventListener("click", fecharModal);


window.addEventListener("click", (e) => {
    if (e.target === modal) {
        fecharModal();
    }
});