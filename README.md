# Exercicio-Treino

Uma API de gerenciamento de produtos feito para um treino. O principal objetivo é treinar para a competição Senac e aprender a como fazer código bom, escalável e rápido.

# A arquitetura de pastas se baseia em uma arquitetura de microserviços, separando bancos de dados e portas de rotas para cada "tabela". 

# Banco de dados:

- São apenas 3 tabelas: usuarios, produtos e categorias
- Produtos tem uma foreign key associada à tabela categorias

* EER do Banco:
<img width="654" height="274" alt="EER banco" src="https://github.com/user-attachments/assets/855e454c-1e55-4522-ae17-ba175f0cdd78" />


# As principais funções se constituem de:

# Gerenciamento de USUARIOS:

1. Cadastro de usuarios;
* Método HTTP: POST
* Rota: http://localhost:{A porta definida a seguir na etapa 5}/usuarios
* Corpo: 
    {
	"nome": "Ricardo",
	"email": "Ricardo2@gmail.com", -- Se já existir, não cria
	"senha": "12345678"
    }
* Valores padrões: A coluna "role" é sempre padrão "usuario", aqui não precisa informar

2. Listagem de usuarios;
* Método HTTP: GET
* Rota: http://localhost:{A porta definida a seguir na etapa 5}/usuarios
* Corpo: Nulo

3. Atualização de usuarios;
Não implementado

4. Exclusão de usuarios;
Não implementado

5. Login de usuarios;
* Método HTTP: Post
* Rota: http://localhost:{A porta definida a seguir na etapa 5}/usuarios/login
* Corpo: 
    {
	"nome": "Ricardo",
	"email": "Ricardo2@gmail.com", -- Precisa existir no banco e a senha ser certa
	"senha": "12345678"
    }

6. Logout de usuarios;
* Método HTTP: Post
* Rota: http://localhost:{A porta definida a seguir na etapa 5}/usuarios/logout
* Corpo: Nulo

# CRUD de CATEGORIAS:

1. Cadastro de categorias;
* Método HTTP: POST
* Rota: http://localhost:{A porta definida a seguir na etapa 5}/categorias
* Corpo: 
    {
	"titulo": "teste"
    }
* Valores padrões: Coluna "status" é sempre padrão "ativo", aqui não precisa enviar 

2. Listagem de categorias;
* Método HTTP: GET
* Rota: http://localhost:{A porta definida a seguir na etapa 5}/categorias
* Corpo: Nulo

3. Atualização de categorias;
* Método HTTP: PUT
* Rota: http://localhost:{A porta definida a seguir na etapa 5}/categorias?categoria_id={id da categoria}
* Corpo: 
{
	"titulo": "teste",
    "status": "ativo" ou "inativo" -- Qualquer coisa diferente envia como "ativo"
}

4. Exclusão de categorias;
* Método HTTP: DELETE
* Rota: http://localhost:{A porta definida a seguir na etapa 5}/categorias?categoria_id={id da categoria}
* Corpo: Nulo

# CRUD de PRODUTOS:

1. Cadastro de produtos;
* Método HTTP: POST
* Rota: http://localhost:{A porta definida a seguir na etapa 5}/produtos
* Corpo: 
{
	"titulo": "teste",
	"categoria_id": 1 -- Id da categoria precisa existir no banco de dados
}
* Valores padrões: Coluna "status" é sempre padrão "ativo", aqui não precisa enviar

2. Listagem de produtos;
* Método HTTP: GET
* Rota: http://localhost:{A porta definida a seguir na etapa 5}/produtos
* Corpo: Nulo

3. Atualização de produtos;
* Método HTTP: PUT
* Rota: http://localhost:{A porta definida a seguir na etapa 5}/produtos?produto_id={id do produto}
* Corpo: 
{
	"titulo": "teste",
	"categoria_id": 1 -- Id da categoria precisa existir no banco de dados
    "status": "ativo" ou "inativo" -- Qualquer coisa diferente envia como "ativo"
}

4. Exclusão de produtos;
* Método HTTP: DELETE
* Rota: http://localhost:{A porta definida a seguir na etapa 5}/produtos?produto_id={id do produto}
* Corpo: Nulo



---------------------------------

# Quais ferramentas usei?

- MySql Server
- MySql Workbench
- Insomnia
- Vs Code
- Git Bash
- Github

# E linguagens? 

- Apenas PHP puro

# Como bibliotecas:

- Apenas o phpdotenv (usado para conseguir usar variáveis de ambiente do .env pelo código inteiro)

--------------- 

# Como funciona o sistema de Login/Autenticação/Autorização do usuarios?

* Sistema de login:
Quando o usuário faz login, o sistema dispara uma requisição, nessa requisição é chamado a função na API loginUsuario em backend/Services/UsuarioServices/UsuarioService.php.
- Na função é iniciado a sessão (Onde fica guardado o usuário com cookie):
![alt text](/imagensReferencia/image-8.png)

- depois é verificado se o usuário existe no banco de dados, buscando pelo email:
![alt text](/imagensReferencia/image-9.png)

- Em seguida tratado erros

- Verifica a senha com password_hash, que foi criado o hash no cadastro de usuário
![alt text](/imagensReferencia/image-10.png)

- Retorna esse se não tiver correta a senha

- Por fim seta na sessão do PHP o id do usuário logado e o cargo dele (Admin ou Usuario)
![alt text](/imagensReferencia/image-11.png)


* Sistema de autenticação: 
Na parte das rotas, mais especificamente nas rotas de Produtos e Categorias, há uma proteção que verifica se o usuário está logado, por meio da sessão e se ele é admin ou usuario normal.

Na parte de produtos, qualquer um Autenticado consegue usufruir.
- A lógica é:
Verificar se o valor de "usuario_id" existe na sessão, se não existir apresenta erro de não autenticado e retorna código HTTP 401 (Não autenticado):
![alt text](/imagensReferencia/image-12.png)

- E então para por aí e não deixa seguir se não estiver autenticado.

Na parte de categorias, apenas admins conseguem usufruir, porém ainda verifica se está logado ou não:
A lógica de autenticação é a mesma
- A de autorização é o seguinte

Como foi colocado na sessão também o valor "usuario_role", cargo do usuário, validamos na rota de categoria se o usuário autenticado tem como cargo usuário ou admin, se for usuário normal para ali e não continua, retorna uma mensagem de erro com o código de resposta 403, mas caso for um admin, continua.

![alt text](/imagensReferencia/image-13.png)

---------------

# Como Rodar a API?

# INSTALAÇÃO E CONFIGURAÇÃO:

1. Instale os seguintes aplicativos/ferramentas:
    - MySql Server (Configure colocando senha e guardado ela)
    - MySql Workbench (Inicie o banco de dados local com a senha do MySql Server)
    - Instale o PHP (Versão mais atualizada)
    - Instale o INSOMNIA (Para testar rotas da API)
    - Instale o VS Code (Versão mais atual, para executar código internos)
    - Instale o Git Bash (Para conseguir clonar esse repositório)

2. Configure o MySql Workbench do seguinte modo:
   - No canto superior esquerdo um icone de folha escrito SQL deve aparecer, você deve clicar:
   ![alt text](/imagensReferencia/image.png) 
   - Ao clicar, aparecerá um campo para digitar, então copie o seguinte:

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema db_usuarios
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema db_usuarios
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `db_usuarios` DEFAULT CHARACTER SET utf8 ;
-- -----------------------------------------------------
-- Schema db_produtos
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema db_produtos
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `db_produtos` ;
-- -----------------------------------------------------
-- Schema db_categorias
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema db_categorias
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `db_categorias` ;
USE `db_usuarios` ;

-- -----------------------------------------------------
-- Table `db_usuarios`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_usuarios`.`usuarios` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `senha` VARCHAR(255) NOT NULL,
  `role` VARCHAR(45) NULL DEFAULT 'usuario',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE)
ENGINE = InnoDB;

USE `db_produtos` ;

-- -----------------------------------------------------
-- Table `db_categorias`.`categorias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_categorias`.`categorias` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(45) NOT NULL,
  `status` VARCHAR(45) NULL DEFAULT 'ativo',
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_produtos`.`produtos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_produtos`.`produtos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(45) NOT NULL,
  `categoria_id` INT NOT NULL,
  `status` VARCHAR(45) NOT NULL DEFAULT 'ativo',
  PRIMARY KEY (`id`),
  INDEX `fk_categoria_id_idx` (`categoria_id` ASC) VISIBLE,
  CONSTRAINT `fk_categoria_id`
    FOREIGN KEY (`categoria_id`)
    REFERENCES `db_categorias`.`categorias` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `db_categorias` ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

    - Depois disso você terá o banco de dados criado, com tabelas, colunas e etc.

# NOTA: PASSO IMPORTANTE PARA OUTRAS ETAPAS

3. Dentro do Vs Code, Aperte a tecla CTRL + J, procure um + ao lado de uma seta, clique na seta e selecione
Git bash, dentro do Git bash execute os seguintes comandos:

4. # PRIMEIRAMENTE:
No terminal do Git Bash execute o seguinte: 
- git clone https://github.com/RicardoFM1/Exercicio-Treino.git

Clique com o botão direito no navegador na parte esquerda do VS Code (onde tem as pastas) e crie um novo arquivo chamado ".env" dentro dele aplique o seguinte:

DBUSUARIO_NAME=db_usuarios
DBUSUARIO_HOST={NOME DO SEU HOST DO BANCO DE DADOS}
DBUSUARIO_USER={NOME DO USUARIO DO SEU BANCO DE DADOS}
DBUSUARIO_PASS={SENHA DO SEU BANCO DE DADOS}

DBPRODUTO_NAME=db_produtos
DBPRODUTO_HOST={NOME DO SEU HOST DO BANCO DE DADOS}
DBPRODUTO_USER={NOME DO USUARIO DO SEU BANCO DE DADOS}
DBPRODUTO_PASS={SENHA DO SEU BANCO DE DADOS}

DBCATEGORIA_NAME=db_categorias
DBCATEGORIA_HOST={NOME DO SEU HOST DO BANCO DE DADOS}
DBCATEGORIA_USER={NOME DO USUARIO DO SEU BANCO DE DADOS}
DBCATEGORIA_PASS={SENHA DO SEU BANCO DE DADOS}

5. # DEPOIS: 
Inicie outro terminal (Pela seta, mesma do Git Bash), selecione "CMD" e execute lá dentro do terminal CMD:

    - cd backend ou cd Exercicio-Treino (Para entrar na pasta da API)
    - php require vlucas/phpdotenv
    - verifique se o php está instalado e com alguma versão digitando "php --version"

6. # EM SEGUIDA: 
Inicie novamente outro terminal CMD, como acima, porém, inicie 3 terminais.

NO TERMINAL 1:
Execute: 
- cd Exercicio-Treino/backend/Routes/RouteUsuario (dependendo no seu caso irá ter ou não o "Exercicio-Treino, depende de como está a estrutura")
Na próxima linha:
- php -S localhost:3000 (Ou a rota que você quiser, não pode ser uma já em uso em outros terminais, mas lembre-se dessa rota para usar no insomnia)

Nisso, a API dos usuário estará rodando, você poderá testar no insomnia.


No Terminal 2: 
- cd Exercicio-Treino/backend/Routes/RouteProduto (dependendo no seu caso irá ter ou não o "Exercicio-Treino, depende de como está a estrutura")
Na próxima linha:
- php -S localhost:3001 (Ou a rota que você quiser, não pode ser uma já em uso em outros terminais, mas lembre-se dessa rota para usar no insomnia)

Nisso, a API dos produtos estará rodando, você poderá testar no insomnia.


No Terminal 3: 
- cd Exercicio-Treino/backend/Routes/RouteCategoria (dependendo no seu caso irá ter ou não o "Exercicio-Treino, depende de como está a estrutura")
Na próxima linha:
- php -S localhost:3002 (Ou a rota que você quiser, não pode ser uma já em uso em outros terminais, mas lembre-se dessa rota para usar no insomnia)

Nisso, a API das categorias estará rodando, você poderá testar no insomnia.


Agora abra outro terminal CMD e execute:

# DETALHE MUITO IMPORTANTE ANTES DESSE PASSO:
SE CASO AINDA NÃO INSTALOU O MYSQL SERVER E MUITO MENOS O WORKBENCH, É NECESSÁRIO INSTALAR ANTES DE PROSSEGUIR, EXECUTE O PASSO 2 NA SEÇÃO "INSTALAÇÃO E CONFIGURAÇÃO".

- cd Exercicio-Treino/backend/Seeders/Categorias (dependendo no seu caso irá ter ou não o "Exercicio-Treino, depende de como está a estrutura")

Sobre este passo, você pode optar por criar mais 3 terminais e colocar cada "cd" em um, a única coisa que mudará será o final, caso queira inserir dados na tabela Categorias do banco de dados, o final da rota vai ser "Categorias", se quiser nos Usuarios irá ser "Usuarios" e se caso quiser Produtos será "Produtos".

* Seguindo qualquer uma das lógicas você executará então o código (dependendo do final da rota): 

Caso o final da rota que foi executada no cd do terminal for Usuarios, execute:
- php usuariosSeeder.php

Se for Produtos:
- php produtosSeeder.php

E se for Categorias:
- php categoriasSeeder.php


7. # USO DAS ROTAS:
Inicie o insomnia, crie uma conta/faça login ou inicie um projeto local, selecionando a opção na tela do insomnia.


1. No Insomnia crie um HTTP REQUEST:
![alt text](/imagensReferencia/image-1.png)
- Quando criar aparecerá da seguinte maneira: 
![alt text](/imagensReferencia/image-2.png)

2. Clique em headers:
![alt text](/imagensReferencia/image-5.png)

4. Adicione, clicando em Add, em headers, a chave:
![alt text](/imagensReferencia/image-6.png)

5. Mude conforme necessário o método HTTP (GET, POST, UPDATE ou DELETE, se necessário consulte o texto de "ROTAS" na Seção de funcionalidades acima para entender melhor):
![alt text](/imagensReferencia/image-3.png)

6. Coloque a rota como citado acima as disponíves na seção ROTAS:
![alt text](/imagensReferencia/image-4.png)

7. Se caso necessário (métodos POST e PUT) adicione um corpo (body) para a requisição:
![alt text](/imagensReferencia/image-7.png) - Onde apresenta "JSON" é necessário selecionar o mesmo para funcionar e o corpo está listado os campos necessários no texto de "ROTAS" na Seção de funcionalidades

8. Clique "SEND" para enviar a requisição e retornará algo.

No mais é esse o básico de como utilizar a API, se caso tiver alguma dúvida, contate para mais informações.
