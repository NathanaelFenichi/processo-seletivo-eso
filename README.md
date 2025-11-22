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

---

```text
processo-seletivo-eso/
│
├── index.php              # Ponto de entrada
├── mydb.sql               # banco de dados
│
├── backend/               # Lógica de negócio e APIs
│   ├── conecta.php        # Configuração da Base de Dados
│   ├── validaCadastro.php
│   ├── validaLogin.php
│   ├── comprar.php        # Lógica de transação
│   ├── devolver.php       # Lógica de estorno
│   ├── obtidos.php
│   ├── verifica_Posse.php
│   └── js/                # Scripts de interação
│
├── paginas/               # Interfaces de utilizador (Views)
│   ├── cadastro.php
│   ├── catalogo.php
│   ├── login.php
│   ├── perfil.php
│   └── ...
│
└── css/ & img/            # Estilos e Assets

````

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

2. instale e ative o xampp
3. abra o phpmyadmin no navegador
4. Importe o banco "mydb.sql"
5. Ajuste o arquivo: backend/conecta.php
com as credenciais do seu MySQL.

6. Abra no navegador:
Copiar código
http://localhost/processo-seletivo-eso/index.php

### Observações diretas sobre o código
O JS do catálogo funciona, mas ainda pode ser desacoplado e melhor estruturado.
As páginas estão funcionais e simples, o suficiente pra entender o fluxo.



📞 Contato
Email: nathanael.essantos@gmail.com
WhatsApp: (19) 99308-4117
