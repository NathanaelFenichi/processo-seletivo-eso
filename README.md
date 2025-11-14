# README - Processo Seletivo ESO

Projeto enviado para o processo seletivo da vaga de estágio. Sem rodeio: entreguei o que deu dentro da minha semana de provas. Aqui explico o que tem e o que não tem no projeto.

## O que foi feito

* Estrutura base do projeto.
* Começo das funcionalidades pedidas.
* Organização mínima pra não virar bagunça.
* paginação
* barra de pesquisa
* cadastro
* banco de dados

## O que não foi feito

* Partes que não consegui finalizar dentro do prazo. (Salvar compras do usuario, login funcional com sessão ativa, filtro de tipo, salvar no docker (aparentemente meu dispositivo não é compativel), pagina com produto especifico e sua descricao)
* somente o catalogo login e cadastro tem funcionalidades interativas.


## Como acessar

1. Clone o repositório:

```
git clone https://github.com/NathanaelFenichi/processo-seletivo-eso.git
```

2. Entre na pasta do projeto:

```
cd processo-seletivo-eso
```

3. Execute em ambiente local (XAMPP/WAMP/PHP) conforme seu setup.
  importe o mydb (1).sql como banco de dados
  ja vai estar como localhost a configuração então é so entrar por
  [phpmyA](http://localhost/processo-seletivo-eso/index.php)]
  e ja estará rodando na sua maquina,
  lembre se de adicionar o arquivo dentro de C:\xampp\htdocs para funcionar

## Estrutura do Projeto

```
processo-seletivo-eso/
│
├── public/              # Arquivos acessíveis diretamente (index, assets, css, js)
│   ├── css/
│   ├── js/
│   └── img/
│
├── src/                 # Código fonte principal
│   ├── config/          # Configurações (ex: conexão com banco)
│   ├── controllers/     # Lógica das rotas / regras do sistema
│   ├── models/          # Operações com banco de dados
│   └── views/           # Páginas que o usuário vê (HTML/PHP)
│
├── database/            # SQL, scripts de criação de tabelas, dumps
│
├── README.md
└── .gitignore
```

## Tecnologias usadas

* HTML / CSS / JS
* PHP básico
* MySQL básico

## Nota pessoal

Gostaria de deixar claro que tive maior dificuldade com o projeto devido a correria e estar aprendendo alguns recursos que não sabia usar, este projeto mesmo que incompleto me fez relembrar de muita coisa do meu curso de tecnico em Ti, 
agradeço novamente a oportunidade
## Contato

Email: **[nathanael.essantos@gmail.com](mailto:nathanael.essantos@gmail.com)**
WhatsApp: (19) 99308-4117
