# PHP Challenge

## A aplicação

A aplicação consiste em um sistema que consume a API do Open Food Facts e salva no máximo 100 dados de cada
arquivo do Open Food Facts(um banco de dados aberto com informação nutricional de diversos produtos alimentícios).


## Fluxo de pensamento para a construção da aplicação

1) Jobs 
<br>
a) criação de um job para baixar os 9 arquivos do Open Food Facts e salvar no storage da aplicação; <br>
b) um job para pegar cada arquivo, abra ele, e consuma no máximo 100 objetos desse arquivo para evitar
o uso de memória exagerado; <br>
c) um job para salvar cada objeto que foi consumido dos arquivos no banco de dados, até o máximo de 100 objetos de cada arquivo; <br>
d) um job que vai ser garantir que o job intermediário (o que pega 100 objetos de cada arquivo) vai ser chamado uma vez para cada arquivo. <br>

2) Controllers <br>
a) para abstrair a regra de manipulação do banco de dados do controller, foram criados repositórios; <br>
b) nos repositórios foram criadas as funções que vão ser usadas para manipular o banco de dados(index, update, delete); <br>

3) Migrations <br>
a) a maioria dos dados foram criados como nullable, pois nem todos os dados do Open Food Facts vem preenchidos; <br>
b) foi criado um campo para armazenar o código do produto, pois o código do produto é único; <br>
c) alguns campos foram criados como text, pois campos como o ```url```, ```categories``` e ```ingredients_text``` tem um tamanho
que excede o limite de strings do tipo string do banco de dados; <br>

4) Observer <br>
a) para abstrair do job que salva os dados no banco de dados a responsabilidade de preencher o campo ```imported_t```
que é a data que o dado foi importado, foi criado um observer que vai ser chamado toda vez que um dado for salvo no banco de dados,
e vai preencher o campo ```imported_t``` com a data atual da importação. <br>

## Rotas

### Database

| Método HTTP | Endpoint | Descrição                                                                                                                                            |
|-------------|----------|------------------------------------------------------------------------------------------------------------------------------------------------------|
| GET         | `/api/`  | Retorna o status da conexão, leitura e escritura com a base de dados, horário da última vez que o CRON foi executado, tempo online e uso de memória. |


### Products

| Método HTTP | Endpoint               | Descrição                                                    |
|-------------|------------------------|--------------------------------------------------------------|
| GET         | `/api/products`        | Retorna todos os produtos cadastrados no banco de dados      |
| GET         | `/api/products/{code}` | Retorna os dados de determinado produto                      |
| PUT         | `/api/products/{code}` | Atualiza os dados de determinado produto                     |
| DELETE      | `/api/products/{code}` | Deleta determinado produto e atualiza seu status para 'trash' |

O ```{code}``` é referente ao código unico de cada produto cadastrado.

## Como rodar o projeto

siga os passos abaixo

```
# clone o projeto
$ git clone git@github.com:thalesmengue/open-food-challenge.git

# entre na pasta do projeto e instale as dependências
$ composer install

# copie o arquivo .env.example para .env
$ cp .env.example .env

# sete as variáveis de ambiente do banco de dados, e também troque o método de processamento
da fila para algum de sua preferência, eu gosto de utilizar o redis, mas caso prefira você pode utilizar o seu banco de dados

$ DB_DATABASE={nome_do_banco}
$ DB_USERNAME={usuario_do_banco}
$ DB_PASSWORD={senha_do_banco}
$ QUEUE_CONNECTION={driver_da_fila}

# gere a chave da aplicação
$ php artisan key:generate

# rode as migrations
$ php artisan migrate

# os cron jobs foram específicados para rodar todo dia 00:00, assim que chegar esse horário, só
rodar o comando abaixo
$ php artisan schedule:run

# ao rodar o comando acima, a aplicação irá jogar para a fila os jobs para baixar os arquivos, processar e importar os dados
assim, é necessário rodar a fila
$ php artisan queue:work

# rode a aplicação
$ php artisan serve
```

## Como rodar os testes
Os testes da aplicação foram feitos com o PHPUnit, para rodar os testes, basta rodar o comando abaixo, mas é recomendado
rodar os testes, apenas após os Cron Jobs serem rodados, pois, alguns dos testes usam o assert com base nos dados persistidos
no banco de dados.

```
php artisan test
```

## Referências

- [Open Food Facts](https://world.openfoodfacts.org/)
- [Laravel](https://laravel.com/)
- [PHP](https://www.php.net/)
