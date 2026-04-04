# 🏋️ Exercicio-Treino API

> API REST de gerenciamento de produtos desenvolvida em PHP puro, com autenticação JWT e arquitetura de microserviços. Projeto criado para treinamento da competição Senac.

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat&logo=php&logoColor=white)
![MySql](https://img.shields.io/badge/MySQL-8.x-4479A1?style=flat&logo=mysql&logoColor=white)
![JWT](https://img.shields.io/badge/Auth-JWT-000000?style=flat&logo=jsonwebtokens)
![Status](https://img.shields.io/badge/status-em%20desenvolvimento-yellow)

---

## 📋 Índice

- [Sobre o Projeto](#-sobre-o-projeto)
- [Tecnologias](#-tecnologias)
- [Banco de Dados](#-banco-de-dados)
- [Rotas da API](#-rotas-da-api)
- [Autenticação](#-autenticação)
- [Como Rodar](#-como-rodar)
- [Variáveis de Ambiente](#-variáveis-de-ambiente)

---

## 📌 Sobre o Projeto

A arquitetura é baseada em **microserviços**, separando bancos de dados e portas de rotas para cada entidade:

| Serviço    | Porta   | Banco de dados    |
|------------|---------|-------------------|
| Usuários   | `3000`  | `db_usuarios`     |
| Produtos   | `3001`  | `db_produtos`     |
| Categorias | `3002`  | `db_categorias`   |

---

## 🛠 Tecnologias

**Ferramentas:**
- PHP 8.x (puro, sem framework)
- MySQL Server + MySQL Workbench
- Insomnia (testes de rota)
- Composer

**Bibliotecas:**
- [`vlucas/phpdotenv`](https://github.com/vlucas/phpdotenv) — variáveis de ambiente
- [`firebase/php-jwt`](https://github.com/firebase/php-jwt) — autenticação JWT

---

## 🗄 Banco de Dados

O projeto usa 3 schemas/bancos separados com 3 tabelas:

- `usuarios` (db_usuarios)
- `categorias` (db_categorias)
- `produtos` (db_produtos) — possui FK para `categorias`

**EER do Banco:**

<img width="306" height="504" alt="EER do banco" src="https://github.com/user-attachments/assets/0a82e9ef-4ecd-4ce6-b6eb-ac3bba3f5a53" />

### Script de criação

<details>
<summary>Clique para expandir o SQL completo</summary>

```sql
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE SCHEMA IF NOT EXISTS `db_usuarios` DEFAULT CHARACTER SET utf8;
CREATE SCHEMA IF NOT EXISTS `db_produtos`;
CREATE SCHEMA IF NOT EXISTS `db_categorias`;

USE `db_usuarios`;

CREATE TABLE IF NOT EXISTS `db_usuarios`.`usuarios` (
  `id`    INT NOT NULL AUTO_INCREMENT,
  `nome`  VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `senha` VARCHAR(255) NOT NULL,
  `role`  VARCHAR(45) NULL DEFAULT 'usuario',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC)
) ENGINE = InnoDB;

USE `db_categorias`;

CREATE TABLE IF NOT EXISTS `db_categorias`.`categorias` (
  `id`     INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(45) NOT NULL,
  `status` VARCHAR(45) NULL DEFAULT 'ativo',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

USE `db_produtos`;

CREATE TABLE IF NOT EXISTS `produtos` (
  `id`           INT NOT NULL AUTO_INCREMENT,
  `titulo`       VARCHAR(45) NOT NULL,
  `categoria_id` INT NOT NULL,
  `status`       VARCHAR(45) DEFAULT 'ativo',
  `usuario_id`   INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_categoria_id` FOREIGN KEY (`categoria_id`) REFERENCES `db_categorias`.`categorias` (`id`),
  CONSTRAINT `fk_usuario_id`   FOREIGN KEY (`usuario_id`)   REFERENCES `db_usuarios`.`usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
```

</details>

---

## 🔀 Rotas da API

### 👤 Usuários — `localhost:3000`

| Método | Rota               | Descrição           | Auth |
|--------|--------------------|---------------------|------|
| POST   | `/usuarios`        | Cadastrar usuário   | ❌   |
| GET    | `/usuarios`        | Listar usuários     | ❌   |
| POST   | `/usuarios/login`  | Login (retorna JWT) | ❌   |

<details>
<summary>📄 Exemplos de corpo (body)</summary>

**POST /usuarios** — Cadastrar
```json
{
  "nome": "Ricardo",
  "email": "Ricardo@gmail.com",
  "senha": "12345678"
}
```
> O campo `role` é preenchido automaticamente como `"usuario"`.

**POST /usuarios/login** — Login
```json
{
  "nome": "Ricardo",
  "email": "Ricardo@gmail.com",
  "senha": "12345678"
}
```
> Retorna um token JWT. Use-o nas rotas protegidas.

</details>

---

### 📦 Produtos — `localhost:3001`

> Requer autenticação: qualquer usuário logado.

| Método | Rota                              | Descrição          | Auth |
|--------|-----------------------------------|--------------------|------|
| POST   | `/produtos`                       | Cadastrar produto  | ✅   |
| GET    | `/produtos`                       | Listar produtos    | ✅   |
| PUT    | `/produtos?produto_id={id}`       | Atualizar produto  | ✅   |
| DELETE | `/produtos?produto_id={id}`       | Deletar produto    | ✅   |

<details>
<summary>📄 Exemplos de corpo (body)</summary>

**POST /produtos** — Cadastrar
```json
{
  "titulo": "Notebook",
  "categoria_id": 1
}
```

**PUT /produtos?produto_id=1** — Atualizar
```json
{
  "titulo": "Notebook Gamer",
  "categoria_id": 2,
  "status": "ativo"
}
```
> `status` aceita `"ativo"` ou `"inativo"`. Qualquer outro valor será tratado como `"ativo"`.

</details>

---

### 🗂 Categorias — `localhost:3002`

> Requer autenticação: **somente admins**.

| Método | Rota                                  | Descrição            | Auth  |
|--------|---------------------------------------|----------------------|-------|
| POST   | `/categorias`                         | Cadastrar categoria  | ✅ Admin |
| GET    | `/categorias`                         | Listar categorias    | ✅ Admin |
| PUT    | `/categorias?categoria_id={id}`       | Atualizar categoria  | ✅ Admin |
| DELETE | `/categorias?categoria_id={id}`       | Deletar categoria    | ✅ Admin |

<details>
<summary>📄 Exemplos de corpo (body)</summary>

**POST /categorias** — Cadastrar
```json
{
  "titulo": "Eletrônicos"
}
```

**PUT /categorias?categoria_id=1** — Atualizar
```json
{
  "titulo": "Informática",
  "status": "ativo"
}
```

</details>

---

## 🔐 Autenticação

O sistema usa **JWT (JSON Web Token)**:

1. O usuário faz login via `POST /usuarios/login`
2. A API retorna um token JWT contendo: `id`, `role` e `exp` (expiração)
3. O token deve ser enviado no header de todas as rotas protegidas:

```
Authorization: Bearer {seu_token_aqui}
```

**Níveis de acesso:**

| Role      | Produtos | Categorias |
|-----------|----------|------------|
| `usuario` | ✅       | ❌         |
| `admin`   | ✅       | ✅         |

---

## 🚀 Como Rodar

### Pré-requisitos

- PHP 8.x
- MySQL Server + MySQL Workbench
- Composer
- Git

### 1. Clonar o repositório

```bash
git clone https://github.com/RicardoFM1/Exercicio-Treino.git
cd Exercicio-Treino
```

### 2. Instalar dependências

```bash
cd backend
composer require vlucas/phpdotenv
composer require firebase/php-jwt
```

### 3. Configurar variáveis de ambiente

Crie um arquivo `.env` na raiz do projeto (veja a seção [Variáveis de Ambiente](#-variáveis-de-ambiente)).

### 4. Criar o banco de dados

Abra o MySQL Workbench, conecte ao seu servidor local e execute o [script SQL](#script-de-criação) acima.

### 5. Popular o banco (opcional)

```bash
# Usuários
cd backend/Seeders/Usuarios
php usuariosSeeder.php

# Categorias
cd ../Categorias
php categoriasSeeder.php

# Produtos
cd ../Produtos
php produtosSeeder.php
```

### 6. Iniciar os servidores

Abra **3 terminais** e execute um comando em cada:

```bash
# Terminal 1 — Usuários
cd backend/Routes/RouteUsuario
php -S localhost:3000
```

```bash
# Terminal 2 — Produtos
cd backend/Routes/RouteProduto
php -S localhost:3001
```

```bash
# Terminal 3 — Categorias
cd backend/Routes/RouteCategoria
php -S localhost:3002
```

### 7. Testar no Insomnia

1. Crie um novo HTTP Request
2. Selecione o método (GET, POST, PUT, DELETE)
3. Informe a URL da rota
4. Para rotas protegidas, vá em **Auth → Bearer Token** e cole o token retornado pelo login
5. Para POST/PUT, vá em **Body → JSON** e cole o corpo da requisição
6. Clique em **Send**

---

## ⚙️ Variáveis de Ambiente

Crie um arquivo `.env` na raiz do projeto com o seguinte conteúdo:

```env
# Banco de Usuários
DBUSUARIO_NAME=db_usuarios
DBUSUARIO_HOST=localhost
DBUSUARIO_USER=root
DBUSUARIO_PASS=sua_senha

# Banco de Produtos
DBPRODUTO_NAME=db_produtos
DBPRODUTO_HOST=localhost
DBPRODUTO_USER=root
DBPRODUTO_PASS=sua_senha

# Banco de Categorias
DBCATEGORIA_NAME=db_categorias
DBCATEGORIA_HOST=localhost
DBCATEGORIA_USER=root
DBCATEGORIA_PASS=sua_senha

# JWT
JWT_SECRET_KEY=uma_chave_secreta_bem_longa_e_segura
```

> [!WARNING]
> ⚠️ Nunca suba o arquivo `.env` para o GitHub. Adicione-o ao `.gitignore`.

---

## 🚧 Funcionalidades não implementadas

- [ ] Atualização de usuário (PUT /usuarios)
- [ ] Exclusão de usuário (DELETE /usuarios)
- [ ] Paginação nas listagens
- [ ] Testes automatizados

---

