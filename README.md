# Loja Simples — Processo Seletivo ESO

Projeto feito para o processo seletivo da ESO.  
A ideia aqui é mostrar o básico de um e-commerce: cadastro, login, catálogo, compra, devolução e visualização de usuários.

Nada de firula — foco no funcionamento.

---

## 🔧 O que funciona

### 🛒 Loja
- Catálogo com paginação.
- Página de produto.
- Busca por nome.
- Perfil público e privado.

### 👤 Usuário
- Cadastro e login.
- Compra de item.
- Devolução de item.
- Lista de itens adquiridos.

### 🗄️ Banco
- MySQL funcionando com todas as tabelas necessárias.
- Dump incluso no projeto.

### 🌐 Front e Back
- HTML, CSS e JavaScript simples no front.
- PHP no back-end com as regras de compra, devolução, login, etc.

---

## 🚫 O que ainda não tem
- Docker (não rolou no dispositivo onde desenvolvi).
- Testes automatizados.
- Hash de senha / prepared statements (a parte de segurança ainda precisa ser reforçada).
- Fluxo mais robusto de validação.

---

## 📁 Estrutura do Projeto

processo-seletivo-eso/
│
├── index.php
├── mydb (1).sql
│
├── backend/
│ ├── conecta.php
│ ├── validaCadastro.php
│ ├── validaLogin.php
│ ├── comprar.php
│ ├── devolver.php
│ ├── obtidos.php
│ ├── verifica_Posse.php
│ └── js/
│ ├── catalogo.js
│ ├── home.js
│ └── produto.js
│
├── paginas/
│ ├── cadastro.php
│ ├── catalogo.php
│ ├── login.php
│ ├── perfil.php
│ ├── perfilPub.php
│ ├── produto.php
│ └── usuarios.php
│
├── css/
└── img/

yaml
Copiar código

---

## Como rodar localmente

### Requisitos
- XAMPP, WAMP ou equivalente
- PHP 8+
- MySQL 5.7+ ou MariaDB

### Passo a passo

1. Clone o repositório:
   ```bash
   git clone https://github.com/NathanaelFenichi/processo-seletivo-eso.git
Coloque a pasta dentro do htdocs do XAMPP:

makefile
Copiar código
C:\xampp\htdocs\processo-seletivo-eso
Importe o banco:

Abra o phpMyAdmin

Crie um banco

Importe mydb (1).sql

Ajuste o arquivo:

bash
Copiar código
backend/conecta.php
com as credenciais do seu MySQL.

Abra no navegador:

arduino
Copiar código
http://localhost/processo-seletivo-eso/index.php
🧩 Observações diretas sobre o código
O fluxo de compra e devolução funciona, mas ainda não usa transações nem prepared statements.

Senhas precisam ser hashadas (password_hash / password_verify).

O JS do catálogo funciona, mas ainda pode ser desacoplado e melhor estruturado.

As páginas estão funcionais e simples, o suficiente pra entender o fluxo.

A organização geral tá limpa o bastante pro avaliador navegar sem sofrer.

📌 Melhorias planejadas (curto prazo)
Hash de senha + refatoração de SQL com PDO.

Documentação dos endpoints e parâmetros.

Dockerfile + docker-compose.

Testes básicos de login, compra e devolução.

📞 Contato
Email: nathanael.essantos@gmail.com
WhatsApp: (19) 99308-4117
