<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Passo a passo para testar o programa:

Este guia detalha os passos necessários para configurar o ambiente de desenvolvimento para um projeto web utilizando PHP 8 e Bootstrap.

**Pré-requisitos:**

* Computador com sistema operacional Windows
* Navegador web (Chrome ou Firefox recomendado)

**Dependências:**

* **Bootstrap:** Uma biblioteca CSS para criar interfaces responsivas rapidamente.
* **PHP 8.0+:** A versão mais recente do PHP para executar o código do back-end.
* **Live Server:** Utilizar extensão Live Server para ambiente de teste do front-end.
* **phpMyAdmin:** Ter configurado o phpMyAdmin para acessar o banco de dados.
* **nginx:** Ter configurado o nginx para conseguir acessar o phpMyAdmin.

**Configuração do Front-end:**

1. **Instalação do Bootstrap:**

    * Execute os seguintes comandos:

      ```bash
      npm install bootstrap
      npm install 
      npm run dev
      ```

      * Isso instalará as dependências do Bootstrap e criará os arquivos de configuração necessários.
      *após isso abra em seu navegador de preferência ou use a extensão Live Server

**Configuração do Back-end:**
1. **Criar a base de dados:**
    * No seu phpMyAdmin, crie uma base de dados chamada ecommerce com a collation utg8mb4_unicode_ci e o nome "ecommerce".
    * Após criar a base de dados, importe o arquivo ecommerce.sql, que está na pasta file, para a base criada em seu phpMyAdmin.
    
2. **Executando o Servidor PHP:**

    * Para executar o servidor PHP, rode:
      ```bash
         php artisan serve
      ```
**Observações:**
    * Caso queira resetar o seu banco de dados, rode o comando: php artisan migrate:fresh
    * A base será populada com dados básicos de usuários, como por exemplo:
        * 1 Usuário Admin (usuário e senha constam nos arquivos seeders)
        * 1 Usuário Cliente (usuário e senha constam nos arquivos seeders)
        * 1 Usuário Fornecedor (usuário e senha constam nos arquivos seeders)
        * 1 Pessoa do tipo cliente
        * 1 Pessoa do tipo fornecedor

**Futuras atualizações:**
    * Melhorar responsividade.
    * Corrigir redirecionamento de rotas do usuário admin quando acessa tela de produtos.
    * Corrigir cadastro de usuários.