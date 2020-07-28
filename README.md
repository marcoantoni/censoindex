# Censoindex
Aplicação para pesquisar dados educacionais do Censo Escolar brasileiro disponibilizados pelo INEP através de linguagem natural
[Demonstração: 99.79.143.96](http://99.79.143.96/)

## Como usar o sistema
Por usar Linguagem Natural, basta fazer perguntas como se fossem dirigidas a outra pessoa. No momento, o sistema está respondendo as seguintes perguntas. 
	* Quais escolas federais tem em Santa Maria/RS - restrições de escolas particulares e publicas (municipais, estaduais) também funcionam
	* quais escolas particulares existem em Frederico Westphalen/RS
	* quantos alunos tem na cidade de Rolante/RS
	* quantos alunos tem na escola visconde de cairu em Santa Rosa/RS
	* quantos alunos tem em Rolante/RS que usam transporte publico
	* quantos alunos tem no ifrs Rolante/RS no curso tecnico em informatica
	* Quais cursos tem na cidade de Taquara/RS

## Para configurar o projeto
É necessário seguir os seguintes passos

### Instale as dependências do Laravel
```sh
sudo apt install openssl php-common php-curl php-json php-mbstring php-mysql php-xml php-zip php-bcmath
```
### Clone o repositório e entre na pasta
```shell script
git clone https://github.com/marcoantoni/censoindex.git && cd censoindex;
```
### Instale as dependências do projeto
Se o composer estiver instalado globalmente
```shell script
composer install
```
Se o composer estiver instalado na pasta do projeto
```shell script
php composer.phar install
```

### Framework Laravel
Para configurar o Laravel é necessário executar os comandos:
```shell script
php -r "file_exists('.env') || copy('.env.example', '.env');"
php artisan key:generate --ansi
```
Configure os parametros de conexão com o banco de dados no arquivo ***.env***, e em seguida rode o comando ```php artisan migrate``` para criar as tabelas no banco de dados.

Para importar os dados para o banco de dados são usados scripts na linguagem Python e é necessário ter o conector com o banco de dados. Caso não esteja instaladp, instale-o com o seguinte comenado:
```shell script
sudo apt-get install python3-mysql.connector
```
Ajuste os parametos de conexão com o banco de dados em todos os scripts da pasta *database/scriptsOfDataImportation* e os execute com o comando `python nomeScript.py`. Se usar outro banco de dados, é necessário ajustar o script para popular as tabelas.

### Testando o projeto
Execute o comando ```php artisan serve```
