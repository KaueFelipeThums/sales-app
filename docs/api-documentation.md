
# Documentação API

* **Resposta de erro padrão:**

  ```json
  {
    "message": "erro!",
    "code": 500
  }
  ```

# Grupo Auth - Rotas de Autenticação

### POST /auth/login

* **Descrição:** Autentica o usuário com email e senha, retornando o access token, refresh token e dados do usuário.
* **Body (exemplo):**

  ```json
  {
    "email": "test@example.com",
    "password": "123456"
  }
  ```
* **Resposta de sucesso:**

  ```json
  {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MSwibmFtZSI6IkFkbWluIiwiZW1haWwiOiJ0ZXN0QGV4YW1wbGUuY29tIiwiaWF0IjoxNzU0MjU5MDk1LCJleHAiOjE3NTQyNjI2OTV9.IvCHQgL6vTLz6sxrVV17rfcdQ0KGh_Z4DHkdxC8U0Ck",
    "refresh_token": "012a64bce446ac25d15d98f2fcbf5647eb0391f9eb14211eb0c7eb27be25c86fdee0c8ff94dadfd0ad9c04d866e18e21fe11925afe9622994df09c342796fb57",
    "user": {
      "id": 1,
      "name": "Admin",
      "email": "test@example.com"
    }
  }
  ```
---

### POST /auth/refresh

* **Descrição:** Gera um novo access token e refresh token a partir de um refresh token válido.

* **Body (exemplo):**

  ```json
  {
    "refresh_token": "087870c569154bc480130042a4952bfa776a560045c6fa258352c6731b6ce43328d2d36385d93898d89dd124caf0f0dcd9303013d620a4639c083a4aee540786"
  }
  ```

* **Resposta de sucesso:**

  ```json
  {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MSwibmFtZSI6IkFkbWluIiwiZW1haWwiOiJ0ZXN0QGV4YW1wbGUuY29tIiwiaWF0IjoxNzU0MjU5NTM3LCJleHAiOjE3NTQyNjMxMzd9.ZIJ0qz4zcipE12-5QKAVVGEykKdeIapGLFP1WWt9d08",
    "refresh_token": "bfb4a4dc87912082eab6a30fb600a750443c69f5df39e28213fe557386acfe68842e849921f5e0811bdc861d45c138038df006b9c639359fa278adc1c3650b18"
  }
  ```

---

### POST /auth/update-password

* **Descrição:** Atualiza a senha do usuário autenticado.

* **Body (exemplo):**

  ```json
  {
    "password": "123456",
    "new_password": "123456"
  }
  ```

* **Resposta de sucesso:**

  ```json
  null
  ```

---

### GET /v1/auth/user

* **Descrição:** Retorna os dados do usuário autenticado.

* **Resposta de sucesso:**

  ```json
  {
    "id": 1,
    "name": "Admin",
    "email": "test@example.com",
    "password": null,
    "is_active": 1,
    "created_at": "2025-08-04 20:13:37",
    "updated_at": null
  }
  ```

---

### POST /v1/user/create

* **Descrição:** Cria um novo usuário.

* **Body (exemplo):**

  ```json
  {
    "name": "Kaue",
    "email": "kaue@gmail.com",
    "password": "123",
    "is_active": 1
  }
  ```

* **Resposta de sucesso:**

  ```json
  {
    "id": 2,
    "name": "Kaue",
    "email": "kaue@gmail.com",
    "password": null,
    "is_active": 1,
    "created_at": "2025-08-05 12:19:15",
    "updated_at": null
  }
  ```
---

### GET /v1/user/get/1

* **Descrição:** Retorna os dados do usuário pelo ID.

* **Resposta de sucesso:**

  ```json
  {
    "id": 1,
    "name": "Admin",
    "email": "test@example.com",
    "password": null,
    "is_active": 1,
    "created_at": "2025-08-04 20:13:37",
    "updated_at": null
  }
  ```

---

### GET /v1/user/get-all

* **Descrição:** Retorna a lista de todos os usuários.

* **Resposta de sucesso:**

  ```json
  [
    {
      "id": 1,
      "name": "Admin",
      "email": "test@example.com",
      "password": null,
      "is_active": 1,
      "created_at": "2025-08-04 20:13:37",
      "updated_at": null
    }
  ]
  ```

---

### POST /v1/user/update

* **Descrição:** Atualiza os dados de um usuário existente.

* **Body (exemplo):**

  ```json
  {
    "id": 2,
    "name": "Kaue 2",
    "email": "kauethums992aa@gmail.com",
    "password": "123",
    "is_active": 1
  }
  ```

* **Resposta de sucesso:**

  ```json
  {
    "id": 2,
    "name": "Kaue 2",
    "email": "kauethums992aa@gmail.com",
    "password": null,
    "is_active": 1,
    "created_at": "2025-08-05 12:19:15",
    "updated_at": "2025-08-05 12:28:42"
  }
  ```

---

### POST /v1/user/delete

* **Descrição:** Remove um usuário pelo ID.

* **Body (exemplo):**

  ```json
  {
    "id": 2
  }
  ```

* **Resposta de sucesso:**

  ```json
  null
  ```

---

### POST /v1/product/create

* **Descrição:** Cria um novo produto.

* **Body (exemplo):**

  ```json
  {
    "name": "Produto 2",
    "quantity": 5,
    "price": 1.25,
    "is_active": 1
  }
  ```

* **Resposta de sucesso:**

  ```json
  {
    "id": 3,
    "users_id": 1,
    "name": "Produto 2",
    "quantity": 5,
    "price": 1.25,
    "is_active": 1,
    "created_at": "2025-08-05 12:30:24",
    "updated_at": null,
    "user": {
      "id": 1,
      "name": "Admin",
      "email": "test@example.com",
      "password": null,
      "is_active": 1,
      "created_at": "2025-08-04 20:13:37",
      "updated_at": null
    }
  }
  ```

---

### GET /v1/product/get/1

* **Descrição:** Retorna os dados do produto pelo ID.

* **Resposta de sucesso:**

  ```json
  {
    "id": 1,
    "users_id": 1,
    "name": "Produto 1",
    "quantity": 0,
    "price": 100,
    "is_active": 1,
    "created_at": "2025-08-04 20:15:17",
    "updated_at": null,
    "user": {
      "id": 1,
      "name": "Admin",
      "email": "test@example.com",
      "password": null,
      "is_active": 1,
      "created_at": "2025-08-04 20:13:37",
      "updated_at": null
    }
  }
  ```

---

### GET /v1/product/get-all

* **Descrição:** Retorna a lista de todos os produtos.

* **Resposta de sucesso:**

  ```json
  [
    {
      "id": 1,
      "users_id": 1,
      "name": "Produto 1",
      "quantity": 0,
      "price": 100,
      "is_active": 1,
      "created_at": "2025-08-04 20:15:17",
      "updated_at": null,
      "user": {
        "id": 1,
        "name": "Admin",
        "email": "test@example.com",
        "password": null,
        "is_active": 1,
        "created_at": "2025-08-04 20:13:37",
        "updated_at": null
      }
    }
  ]
  ```

---

### POST /v1/product/update

* **Descrição:** Atualiza os dados de um produto existente.

* **Body (exemplo):**

  ```json
  {
    "id": 1,
    "name": "Produto 2",
    "quantity": 5,
    "price": 1.25,
    "is_active": 1
  }
  ```

* **Resposta de sucesso:**

  ```json
  {
    "id": 1,
    "users_id": 1,
    "name": "Produto 2",
    "quantity": 5,
    "price": 1.25,
    "is_active": 1,
    "created_at": "2025-08-04 20:15:17",
    "updated_at": "2025-08-05 12:31:48",
    "user": {
      "id": 1,
      "name": "Admin",
      "email": "test@example.com",
      "password": null,
      "is_active": 1,
      "created_at": "2025-08-04 20:13:37",
      "updated_at": null
    }
  }
  ```

---

### POST /v1/product/delete

* **Descrição:** Remove um produto pelo ID.

* **Body (exemplo):**

  ```json
  {
    "id": 1
  }
  ```

* **Resposta de sucesso:**

  ```json
  null
  ```

---

### POST /v1/payment-method/create

* **Descrição:** Cria um novo método de pagamento.

* **Body (exemplo):**

  ```json
  {
    "name": "Plano Mensal",
    "installments": 3,
    "is_active": 1
  }
  ```

* **Resposta de sucesso:**

  ```json
  {
    "id": 3,
    "name": "Plano Mensal",
    "installments": 3,
    "is_active": 1,
    "created_at": "2025-08-05 12:33:35",
    "updated_at": null,
    "user": {
      "id": 1,
      "name": "Admin",
      "email": "test@example.com",
      "password": null,
      "is_active": 1,
      "created_at": "2025-08-04 20:13:37",
      "updated_at": null
    }
  }
  ```

---

### GET /v1/payment-method/get/1

* **Descrição:** Retorna os dados do método de pagamento pelo ID.

* **Resposta de sucesso:**

  ```json
  {
    "id": 1,
    "name": "Plano Mensal",
    "installments": 3,
    "is_active": 1,
    "created_at": "2025-08-04 20:15:20",
    "updated_at": null,
    "user": {
      "id": 1,
      "name": "Admin",
      "email": "test@example.com",
      "password": null,
      "is_active": 1,
      "created_at": "2025-08-04 20:13:37",
      "updated_at": null
    }
  }
  ```

---

### GET /v1/payment-method/get-all

* **Descrição:** Retorna a lista de todos os métodos de pagamento.

* **Resposta de sucesso:**

  ```json
  [
    {
      "id": 1,
      "name": "Plano Mensal",
      "installments": 3,
      "is_active": 1,
      "created_at": "2025-08-04 20:15:20",
      "updated_at": null,
      "user": {
        "id": 1,
        "name": "Admin",
        "email": "test@example.com",
        "password": null,
        "is_active": 1,
        "created_at": "2025-08-04 20:13:37",
        "updated_at": null
      }
    }
  ]
  ```

---

### POST /v1/payment-method/update

* **Descrição:** Atualiza os dados de um método de pagamento existente.

* **Body (exemplo):**

  ```json
  {
    "id": 1,
    "name": "Plano Mensal 2",
    "installments": 4,
    "is_active": 1
  }
  ```

* **Resposta de sucesso:**

  ```json
  {
    "id": 1,
    "name": "Plano Mensal 2",
    "installments": 4,
    "is_active": 1,
    "created_at": "2025-08-04 20:15:20",
    "updated_at": "2025-08-05 12:35:13",
    "user": {
      "id": 1,
      "name": "Admin",
      "email": "test@example.com",
      "password": null,
      "is_active": 1,
      "created_at": "2025-08-04 20:13:37",
      "updated_at": null
    }
  }
  ```

---

### POST /v1/payment-method/delete

* **Descrição:** Remove um método de pagamento pelo ID.

* **Body (exemplo):**

  ```json
  {
    "id": 1
  }
  ```

* **Resposta de sucesso:**

  ```json
  null
  ```

---
