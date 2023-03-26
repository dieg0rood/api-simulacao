
# API Simulação de Crédito

Projeto tem objetivo de repassar ao cliente ofertas de crédito de forma ordenada da mais vantajosa para a menos, consumindo a API da gosat e realizando o tratamento dos dados. 


## Instalação

Faça o clone do projeto em uma pasta de sua preferência

```bash
  git clone git@github.com:dieg0rood/api-simulacao.git
```

Acessando o diretório do projeto faça a instalação das dependências do projeto

```bash
  composer install
```    

Renomeie o arquivo env

```bash
  mv .env.example .env
```  

Para utilizar o servidor do laravel

```bash
  php artisan serve
```  



## Documentação da API

#### A documentação está disponível em

```http
  http://localhost:8000/api/docs
```

## Rodando os testes

Para rodar os testes, rode os seguintes comandos na raiz do projeto

```bash
  alias sail="vendor/bin/sail"
  sail up -d 
  sail test
```

