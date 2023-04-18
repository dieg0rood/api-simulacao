
# API Simulação de Crédito

Projeto tem objetivo de repassar ao cliente ofertas de crédito de forma ordenada da mais vantajosa para a menos, consumindo a API da gosat e realizando o tratamento dos dados. 

A cada nova consulta de uma combinação única de CPF + Valor Solicitado + Quantidade de Parcela, os dados de simulação do cliente e de ofertas fornecidas pela API são salvas no banco de dados, para que caso seja realizada a mesma consulta, o resultado será retornado direto do banco de dados.

É executado um fíltro para que somente sejam apresentados resultados de propostas compativeis com o Valor solicitado e Quantidade de parcelas, dito isso, a quantidade de resultados pode variar a depender da consulta feita.

## Instalação

Faça o clone do projeto em uma pasta de sua preferência

```bash
  git clone git@github.com:dieg0rood/api-simulacao.git
```

Acessando o diretório raiz do projeto faça a instalação das dependências do projeto

```bash
  composer install
```    

Copie o arquivo env

```bash
  cp .env.example .env
```

Crie o arquivo de banco de dados do sqlite
(Caso o terminal fique piscando, dê CtrlC)

```bash
  cat > database/database.sqlite
``` 

Ainda na raiz do projeto, gere o banco de dados com suas migrations, segue exemplo utilizando o sail

```bash
  alias sail="vendor/bin/sail"
  sail up -d 
  sail artisan migrate
```

Para utilizar o servidor do laravel

```bash
  php artisan serve
```  

Após isso a API já estará disponível para consulta


## Documentação da API

#### A documentação está disponível em

```http
  http://localhost:8000/api/docs
```

## Rodando os testes

Para rodar os testes, rode os seguintes comandos na raiz do projeto
*Comandos para usar somente se o sail ainda não estiver Up

```bash
  mkdir tests/Unit   
  *alias sail="vendor/bin/sail"
  *sail up -d 
  sail test
```

#### Rodando a Aplicação

CPFs disponíveis:
- 111.111.111-11
- 123.123.123-12
- 222.222.222-22

Exemplo de dados:
- cpf: 111.111.111-11
- valor: R$ 10.000,00
- parcelas: 24x

Requisição:
```http
http://localhost:8000/api/simulation?cpf=11111111111&valor=10000&parcelas=24
```  

#### Troubleshooting

Caso tenha erros semelhante ao abaixo:

```bash
Error response from daemon: driver failed programming external connectivity on endpoint api-simulacao-laravel.test-1 (e1eaee2fe9d0ba510025c0acfc4d5d38a7de06175a339d3d5642e58da913387b): Error starting userland proxy: listen tcp4 0.0.0.0:80: bind: address already in use
```

Rode o seguinte comando para visualizar qual serviço está usando a porta 80

```bash
  sudo lsof -i :80
```

E para liberar a porta, localize o PID (ID do processo), e rode o seguinte comando para finaliza-lo:

```bash
  sudo kill PID
```
