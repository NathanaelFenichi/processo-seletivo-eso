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

Devido a limitações de tempo (semana de provas), as seguintes funcionalidades não foram concluídas:
- Salvamento de compras do usuário.
- Login funcional com sessão ativa.
- Filtro por tipo de produto.
- Containerização com Docker (incompatibilidade com o dispositivo).
- Página detalhada de produto com descrição específica.
- Apenas o catálogo, login e cadastro possuem interatividade completa.

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
├── loja_simples.html         # Versão HTML da loja
├── produto.html              # Página de produto
├── mydb (1).sql              # Dump do banco de dados
│
├── backend/                  # Scripts PHP do back-end
│   ├── comprar.php
│   ├── conecta.php
│   ├── devolver.php
│   ├── logout.php
│   ├── obtidos.php
│   ├── validaCadastro.php
│   ├── validaLogin.php
│   ├── verifica_Posse.php
│   └── js/                   # Scripts JavaScript do back-end
│       ├── catalogo.js
│       ├── home.js
│       ├── produto.js
│       └── testes.js
│
├── css/                      # Folhas de estilo
│   ├── cadastro-login.css
│   ├── geral.css
│   ├── index.css
│   ├── perfil.css
│   ├── produto.css
│   └── shop.css
│
├── img/                      # Imagens e ícones
│   ├── fortinite-banner.jpeg
│   ├── photo.png
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

Este projeto foi desenvolvido em um período de alta demanda devido a provas acadêmicas, o que resultou em algumas funcionalidades incompletas. Mesmo assim, serviu como uma oportunidade valiosa para revisar conceitos aprendidos no curso técnico em TI. Agradeço pela oportunidade de participar do processo seletivo.

## Contato

- **Email**: [nathanael.essantos@gmail.com](mailto:nathanael.essantos@gmail.com)
- **WhatsApp**: (19) 99308-4117
