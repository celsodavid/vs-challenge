# Sistema Veus - Saude

## Requisitos

* PHP 7.2
* Lumen 5.6
* Mysql 5.7
* Docker

## Instalacao

* Executar os seguintes passos:
    * Efetua o git clone do repositorio
    * cd veus-saude
    * cp .env.example .env
    * efetuar as configuracoes de banco de dados
    * docker-compose up -d
    * docker-compose exec php-fpm bash
    * Dentro do container entrar na pasta api e executar os comandos:
        * composer install
        * efetuar um [GET](http://localhost:8000/key), copiar a hash gerada e adicionar no arquivo .env em 'APP_KEY'
        * efetuar um [GET](http://localhost:8000/key), copiar a hash gerada e adicionar no arquivo .env em 'JWT_SECRET'
        * php artisan migrate
        * php artisan db:seed
        * composer dump-autoload

## Como usar

* [Login](http://localhost:8000/auth/login), acessar a url passando email e senha para efetuar a autenticação
    * Após a autenticacao com sucesso, a mesma irá retornar um token valido, que será usado nas demais chamadas da API.
* [GetALL](http://localhost:8000/product?token=token_gerado), isso retornar todos os produtos cadastrados  
* [Get](http://localhost:8000/product/product_id?token=token_gerado), isso retornar o produto especifico de acordo com o id passado.
* [Post](http://localhost:8000/product?token=token_gerado), cria um produto novo, passando no corpo do post os seguinte elementos:
    * 'name' = Nome do produto | requerido, unico
    * 'brand' = Nome do fornecedor | requerido
    * 'description' = Descricao do produto | opcional
    * 'value' = Valor/Preço do produto | requerido
    * 'qty_stcok' = Quantidade de estoque do produto | requerido
* [Put](http://localhost:8000/product/product_id?token=token_gerado), edita um produto, passando o id a ser alterado e no corpo do put os seguinte elementos a serem alterados:
    * 'name' = Nome do produto | requerido
    * 'brand' = Nome do fornecedor | requerido
    * 'description' = Descricao do produto | opcional
    * 'value' = Valor/Preço do produto | requerido
    * 'qty_stcok' = Quantidade de estoque do produto | requerido
* [Delete](http://localhost:8000/product/product_id?token=token_gerado), excluiu um produto existente, passando o id a ser deletado.
* [Get](http://localhost:8000/products?token=token_gerado&q=search&filter=campo:valor), busca um ou mais produtos por querystring, enviando alguns dados:
    * 'q' recebe o produto que queremos buscar Ex.: q=seringa
    * 'filter' recebe uma combinacao de campo e valor Ex.: filter=brand:BUNZL

## Extras

**User**: admin@admin.com.br
**Pass**: praquesenha

## Acesso ao banco de dados
* 1 - docker-compose exec mysql mysql -uroot -p
* 2 - **Senha**: root


## Testes Unitários

Para rodar os testes unitários, seguir os passos abaixo:
* 1 - docker-compose exec php-fpm bash
* 2 - cd api
* 3 - vendor/bin/phpunit