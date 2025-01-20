# GS3 Teste - Laravel API (BACKEND) - Documentação de Uso

Esta é uma aplicação Laravel configurada para atuar como API back-end para a aplicação GS3 - Flutter.

## Requisitos
Para utilizar a aplicação serão necessários:

- PHP 8.0 ou superior
- Composer
- MySQL
- Servidor HTTP (Apache/Nginx)

## Configuração do Projeto

1. **Clone o repositório:**
   ```bash
   git clone https://github.com/GaLfoTorTo/gs3-laravel.git
   cd gs3-laravel
   ```

2. **Instale as dependências:**
   ```bash
   composer install
   ```

3. **Configure as variáveis de ambiente:**
   - Copie o arquivo `.env.example` para `.env`:
     ```bash
     cp .env.example .env
     ```
   - Configure as seguintes variáveis no arquivo `.env`:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=gs3-teste
     DB_USERNAME=root
     DB_PASSWORD=
     ```

4. **Configurar JWT:**
    - Execute o comando de geração da chave de autenticação do JWT na aplicação:
   ```bash
   php artisan jwt:secret
   ```
   isso gerará uma chave na variavel de ambiente JWT_SECRET

   ```bash
   JWT_SECRET={CHAVE-GERADA}
   ```

5. **Execute as migrações e seeds:**
   - Crie o banco de dados com nome "**gs3-teste**" na sua maquina local e execute o comando de geração de tabelas
   ```bash
   php artisan migrate --seed
   ```
   - Isso criá as tabelas e disponibilizará um usuario com perfil de administrador ja configurado
   **Usuario:** admin@teste.com
   **senha:** admin@1234

6. **Inicie o servidor:**
   - Com tudo configurado basta rodar o projeto utilizando da tag --host para disponibilizar a api em toda sua rede local 
   ```bash
   php artisan serve --host 0.0.0.0
   ```
   O servidor será iniciado em `http://localhost:8000` por padrão.

4. **Configurar um servidor HTTP:**
   Configure um servidor HTTP como Apache ou Nginx para apontar para a pasta `public` do Laravel.

