# Processo Seletivo ESO - Loja Simples

Este é um projeto desenvolvido para o processo seletivo da vaga de estágio na ESO. Trata-se de uma loja virtual simples implementada com funcionalidades básicas de e-commerce.

## Funcionalidades Implementadas

- **Estrutura Base**: Organização do projeto com separação de front-end e back-end.
- **Página Inicial**: Interface principal da loja.
- **Catálogo de Produtos**: Listagem de produtos com paginação.
- **Barra de Pesquisa**: Funcionalidade para buscar produtos.
- **Sistema de Cadastro**: Registro de novos usuários.
- **Sistema de Login**: Autenticação de usuários (básico).
- **Banco de Dados**: Configuração e integração com MySQL para armazenar usuários e produtos.

## Funcionalidades Não Implementadas

- Containerização com Docker (incompatibilidade com o dispositivo).

## Tecnologias Utilizadas

- **Front-end**: HTML, CSS, JavaScript
- **Back-end**: PHP
- **Banco de Dados**: MySQL

## Instalação e Execução

### Pré-requisitos
- Servidor local como XAMPP, WAMP ou similar com suporte a PHP e MySQL.

### Passos

1. **Clone o repositório**:
   ```bash
   git clone https://github.com/NathanaelFenichi/processo-seletivo-eso.git
   ```

2. **Navegue até a pasta do projeto**:
   ```bash
   cd processo-seletivo-eso
   ```

3. **Configure o ambiente local**:
   - Adicione a pasta do projeto em `C:\xampp\htdocs` (ou equivalente no seu servidor).
   - Importe o arquivo `mydb (1).sql` para o seu banco de dados MySQL (via phpMyAdmin ou similar).

4. **Execute o projeto**:
   - Inicie o servidor Apache e MySQL no XAMPP.
   - Acesse via navegador: [http://localhost/processo-seletivo-eso/index.php](http://localhost/processo-seletivo-eso/index.php)

## Estrutura do Projeto

```
processo-seletivo-eso/
│
├── index.php                 # Página inicial
├── mydb (1).sql              # Dump do banco de dados
│
├── backend/                  # Scripts PHP 
│   ├── comprar.php
│   ├── conecta.php
│   ├── devolver.php
│   ├── logout.php
│   ├── obtidos.php
│   ├── validaCadastro.php
│   ├── validaLogin.php
│   ├── verifica_Posse.php
│   └── js/                   # Scripts JavaScript
│       ├── catalogo.js
│       ├── home.js
│       ├── produto.js
│     
│
├── css/                     
│   ├── cadastro-login.css
│   ├── geral.css
│   ├── index.css
│   ├── perfil.css
│   ├── produto.css
│   └── shop.css
│
├── img/                      # Imagens e ícones
│   ├── fortinite-banner.jpeg
│   └── icons/
│
└── paginas/                  # Páginas PHP
    ├── cadastro.php
    ├── catalogo.php
    ├── login.php
    ├── nav.php
    ├── perfil.php
    ├── perfilPub.php
    ├── produto.php
    └── usuarios.php
```

## Nota Pessoal

Esse projeto foi feito em um período de bastante demanda por causa das provas da faculdade, mas acabou sendo uma boa oportunidade para revisitar conteúdos do curso técnico em TI e colocar em prática o que eu já sabia. Ainda tenho pontos do código que preciso rever, porque fiquei um tempo sem contato com esses temas e estou retomando agora, mas vejo isso como parte do processo de aprendizado.Desde ja agradeço pela chance de participar do processo seletivo.

## Contato

- **Email**: [nathanael.essantos@gmail.com](mailto:nathanael.essantos@gmail.com)
- **WhatsApp**: (19) 99308-4117
