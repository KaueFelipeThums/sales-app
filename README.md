# 🚀 Rodando o projeto em modo de desenvolvimento

#### Clone o repositório

```bash
git clone https://github.com/KaueFelipeThums/sales-app.git
cd sales-app
```

---

## 🐧 Rodando com Docker no Linux (Modo de Desenvolvimento)

#### 📌 Pré-requisitos

* Sistema operacional **Linux**
* [Docker](https://docs.docker.com/get-docker/) e [Docker Compose](https://docs.docker.com/compose/install/) instalados

---

#### 1. Criar a rede Docker (caso ainda não exista)

```bash
docker network create sales-app-api-network
```

---

#### 2. Subir o banco de dados

Acesse a pasta `devops` do projeto:

```bash
cd devops
```

Suba os containers com:

```bash
docker compose up -d
```

Isso iniciará o banco de dados MySQL com as seguintes configurações:

| Variável              | Valor     |
| --------------------- | --------- |
| MYSQL\_ROOT\_PASSWORD | `root`    |
| MYSQL\_DATABASE       | `salesdb` |
| MYSQL\_USER           | `salesdb` |
| MYSQL\_PASSWORD       | `salesdb` |


---

## ⚙️ Rodando o Backend com Docker (Linux)

#### 1. Acesse a pasta do backend

```bash
cd backend
```

---

#### 2. Configure o arquivo `.env`

Crie o arquivo `.env` com base no exemplo:

```bash
cp .env.example .env
```

Ou crie manualmente com o seguinte conteúdo:

```env
# Ambiente da aplicação (local, production)
APP_ENV=local

# Chave secreta usada para assinar os tokens JWT
JWT_SECRET=coloque_um_hash_aqui

# Tempo de vida do token JWT em segundos (3600s = 1 hora)
JWT_TTL=3600

# URL base da API ViaCEP, usada para buscar endereços a partir de CEPs
VIACEP_BASE_URL=http://viacep.com.br/ws

# Configurações do banco de dados MySQL
DB_CONNECTION=mysql
DB_HOST=SEU_IP_LOCAL
DB_PORT=3306
DB_DATABASE=salesdb
DB_USERNAME=salesdb
DB_PASSWORD=salesdb
```

> 📝 **Importante**:
>
> * Substitua `SEU_IP_LOCAL` pelo IP da sua máquina (ex: `192.168.0.100`) que esteja acessível pelos containers.
> * Gere um valor aleatório para `JWT_SECRET`, com letras e números.

---

#### 3. Rode o backend

Ainda dentro da pasta `backend`, execute:

```bash
docker compose up -d --build
```
---

#### 4. Execute o script de setup

Após os containers estarem rodando, execute o script `setup.sh` para instalar as dependências PHP dentro do container:

```bash
./setup.sh
```

> Este script acessa o container do backend e executa o comando `composer install`.

---

#### 5. Execute o script de migrations

Depois, rode o script `migrate.sh` para aplicar as migrations no banco de dados:

```bash
./migrate.sh up
```

> Esse script executa as migrations para preparar o banco da aplicação.

---

### ⚠️ Dicas importantes

* Verifique se os scripts possuem permissão de execução. Caso contrário, rode:

  ```bash
  chmod +x setup.sh migrate.sh
  ```

* Os scripts assumem que o container do backend está rodando e que o nome do container está correto dentro deles.

---

## 📱 Rodando o Frontend React Native no Linux (emulador Android)

### 🧩 Pré-requisitos

#### 1. Configurar ambiente para React Native

Siga o guia oficial de configuração Android da Rocketseat:

> **⚠️ IMPORTANTE:**
> Certifique-se de utilizar o pacote **`openjdk-17-jdk`** ao configurar o Java no seu sistema.

> **🔗 Guia completo:**
> [https://react-native.rocketseat.dev/android/linux](https://react-native.rocketseat.dev/android/linux)

---

#### 2. Instalar Node.js e npm

Primeiro, instale o `curl` (se ainda não tiver):

```bash
sudo apt-get install curl
```

Verifique a instalação:

```bash
curl --version
```

Instale o Node.js (versão LTS) e npm via NodeSource:

```bash
curl -fsSL https://deb.nodesource.com/setup_16.x | sudo -E bash -
sudo apt-get install -y nodejs
```

Verifique se tudo está instalado corretamente:

```bash
node -v
npm -v
```

> ⚠️ Recomendado usar o **Node.js versão 14 ou superior**.

---

### ▶️ Rodando o projeto

#### 1. Acesse a pasta do app

```bash
cd app
```

#### 2. Instale as dependências

```bash
npm install
```

#### 3. Configure a URL da API

Abra o arquivo de configuração:

```bash
src/config/env/env.json
```

Edite o valor de `API_BACKEND`, substituindo pelo **IP local da sua máquina**, por exemplo:

```json
{
  "API_BACKEND": "http://192.168.100.223:8989/api"
}
```

> Isso garante que o app React Native consiga se comunicar com o backend.

---

#### 4. Execute o projeto no emulador Android

No terminal, execute:

```bash
npm run start
```

Em outro terminal:

```bash
npm run android
```

Claro! Aqui está o trecho ajustado do seu `README.md` com a instrução de testes no frontend antes da seção da documentação:

---


#### ✅ Testes no Frontend

Para executar os testes no frontend, utilize o comando abaixo:

```bash
npm test
````

---

### 📚 Documentação da API

A documentação completa da API pode ser acessada no link abaixo:

➡️ [Acessar documentação da API](/docs/api-documentation.md)

---

### 👤 Usuário inicial para testes

Um usuário padrão é automaticamente inserido no banco de dados para facilitar o acesso inicial ao sistema:

* **Email:** `test@example.com`
* **Senha:** `123456`
