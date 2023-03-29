
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

Copie o arquivo env

```bash
  cp .env.example .env
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
  mkdir tests/Unit   
  alias sail="vendor/bin/sail"
  sail up -d 
  sail test
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