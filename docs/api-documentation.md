
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

* **Body (exemplo):**

  ```json
  {
    "search": "",
    "page": 1,
    "page_count": 10
  }
  ```

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

### GET /v1/user/get-all-active

* **Descrição:** Retorna a lista de todos os usuários ativos.

* **Body (exemplo):**

  ```json
  {
    "search": "",
    "page": 1,
    "page_count": 10
  }
  ```

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

* **Body (exemplo):**

  ```json
  {
    "search": "",
    "page": 1,
    "page_count": 10
  }
  ```

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

### GET /v1/product/get-all-active

* **Descrição:** Retorna a lista de todos os produtos ativos.

* **Body (exemplo):**

  ```json
  {
    "search": "",
    "page": 1,
    "page_count": 10
  }
  ```

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

* **Body (exemplo):**

  ```json
  {
    "search": "",
    "page": 1,
    "page_count": 10
  }
  ```

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

### GET /v1/payment-method/get-all-active

* **Descrição:** Retorna a lista de todos os métodos de pagamento ativos.

* **Body (exemplo):**

  ```json
  {
    "search": "",
    "page": 1,
    "page_count": 10
  }
  ```

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

### POST /v1/customer/create

* **Descrição:** Cria um novo cliente.

* **Body (exemplo):**

  ```json
  {
    "name": "João da Silva 2",
    "cpf": "123456412312",
    "email": "joao.silva@example.com",
    "zip_code": null,
    "street": "Rua das Flores",
    "number": "123",
    "complement": "Apartamento 45",
    "neighborhood": "Centro",
    "city": "São Paulo",
    "state": "",
    "is_active": 1
  }
  ```

* **Resposta de sucesso:**

  ```json
  {
    "id": 4,
    "users_id": 1,
    "name": "João da Silva 2",
    "cpf": "123456412312",
    "email": "joao.silva@example.com",
    "zip_code": null,
    "street": "Rua das Flores",
    "number": "123",
    "complement": "Apartamento 45",
    "neighborhood": "Centro",
    "city": "São Paulo",
    "state": null,
    "is_active": 1,
    "created_at": "2025-08-05 14:04:39",
    "updated_at": null,
    "user": {
      "id": 1,
      "name": "Admin",
      "email": "test@example.com",
      "password": null,
      "is_active": 1,
      "created_at": "2025-08-05 08:10:55",
      "updated_at": null
    }
  }
  ```

---

### GET /v1/customer/get/1

* **Descrição:** Retorna os dados do cliente pelo ID.

* **Resposta de sucesso:**

  ```json
  {
    "id": 1,
    "users_id": 1,
    "name": "Kaue Thums",
    "cpf": "10227078926",
    "email": "kauethums99@gmail.com",
    "zip_code": "89708-052",
    "street": "Travessa Felipe Turmena",
    "number": "62",
    "complement": "Casa",
    "neighborhood": "Sunti",
    "city": "Concórdia",
    "state": "SC",
    "is_active": 1,
    "created_at": "2025-08-05 08:43:14",
    "updated_at": "2025-08-05 08:43:19",
    "user": {
      "id": 1,
      "name": "Admin",
      "email": "test@example.com",
      "password": null,
      "is_active": 1,
      "created_at": "2025-08-05 08:10:55",
      "updated_at": null
    }
  }
  ```

---

### POST /v1/customer/get-all

* **Descrição:** Retorna uma lista paginada de clientes com opção de busca por nome, e-mail ou CPF.

* **Body (exemplo):**

  ```json
  {
    "search": "",
    "page": 1,
    "page_count": 10
  }
  ```

* **Resposta de sucesso:**

  ```json
  [
    {
      "id": 2,
      "users_id": 1,
      "name": "Felipe D",
      "cpf": "17745310032",
      "email": "kaue@kaue.com",
      "zip_code": "89708-052",
      "street": "Travessa Felipe Turmena",
      "number": "62",
      "complement": "Casa",
      "neighborhood": "Sunti",
      "city": "Concórdia",
      "state": "SC",
      "is_active": 1,
      "created_at": "2025-08-05 08:47:54",
      "updated_at": "2025-08-05 08:48:07",
      "user": {
        "id": 1,
        "name": "Admin",
        "email": "test@example.com",
        "password": null,
        "is_active": 1,
        "created_at": "2025-08-05 08:10:55",
        "updated_at": null
      }
    }
  ]
  ```

---

### POST /v1/customer/get-all-active

* **Descrição:** Retorna uma lista paginada de clientes ativos.

* **Body (exemplo):**

  ```json
  {
    "search": "",
    "page": 1,
    "page_count": 10
  }
  ```

* **Resposta de sucesso:**

  ```json
  [
    {
      "id": 2,
      "users_id": 1,
      "name": "Felipe D",
      "cpf": "17745310032",
      "email": "kaue@kaue.com",
      "zip_code": "89708-052",
      "street": "Travessa Felipe Turmena",
      "number": "62",
      "complement": "Casa",
      "neighborhood": "Sunti",
      "city": "Concórdia",
      "state": "SC",
      "is_active": 1,
      "created_at": "2025-08-05 08:47:54",
      "updated_at": "2025-08-05 08:48:07",
      "user": {
        "id": 1,
        "name": "Admin",
        "email": "test@example.com",
        "password": null,
        "is_active": 1,
        "created_at": "2025-08-05 08:10:55",
        "updated_at": null
      }
    }
  ]
  ```

---

### POST /v1/customer/update

* **Descrição:** Atualiza os dados de um cliente existente.

* **Body (exemplo):**

  ```json
  {
    "id": 1,
    "name": "João da Silv 2a",
    "cpf": "123456412312",
    "email": "joao.silva@example.com",
    "zip_code": null,
    "street": "Rua das Flores",
    "number": "123",
    "complement": "Apartamento 45",
    "neighborhood": "Centro",
    "city": "São Paulo",
    "state": "",
    "is_active": 1
  }
  ```

* **Resposta de sucesso:**

  ```json
  {
    "id": 1,
    "users_id": 1,
    "name": "João da Silv 2a",
    "cpf": "123456412312",
    "email": "joao.silva@example.com",
    "zip_code": null,
    "street": "Rua das Flores",
    "number": "123",
    "complement": "Apartamento 45",
    "neighborhood": "Centro",
    "city": "São Paulo",
    "state": null,
    "is_active": 1,
    "created_at": "2025-08-05 08:43:14",
    "updated_at": "2025-08-05 14:11:31",
    "user": {
      "id": 1,
      "name": "Admin",
      "email": "test@example.com",
      "password": null,
      "is_active": 1,
      "created_at": "2025-08-05 08:10:55",
      "updated_at": null
    }
  }
  ```

---

### POST /v1/customer/delete

* **Descrição:** Remove um cliente pelo ID.

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

### POST /v1/sale/create

* **Descrição:** Cria uma nova venda com os produtos selecionados para um cliente específico, utilizando uma forma de pagamento.

* **Body (exemplo):**

  ```json
  {
    "payment_methods_id": 1,
    "customers_id": 1,
    "products": [
      {
        "products_id": 1,
        "quantity": 1
      },
      {
        "products_id": 2,
        "quantity": 1
      }
    ]
  }
  ```

* **Resposta de sucesso:**

  ```json
  {
    "id": 6,
    "payment_methods_id": 1,
    "users_id": 1,
    "customers_id": 1,
    "total_value": 34,
    "created_at": "2025-08-05 14:14:41",
    "updated_at": null,
    "payment_method": {
      "id": 1,
      "name": "Metodo 1",
      "installments": 8,
      "is_active": 1,
      "created_at": "2025-08-05 08:27:28",
      "updated_at": "2025-08-05 08:27:44",
      "user": null
    },
    "customer": {
      "id": 1,
      "users_id": 1,
      "name": "João da Silv 2a",
      "cpf": "123456412312",
      "email": "joao.silva@example.com",
      "zip_code": null,
      "street": "Rua das Flores",
      "number": "123",
      "complement": "Apartamento 45",
      "neighborhood": "Centro",
      "city": "São Paulo",
      "state": null,
      "is_active": 1,
      "created_at": "2025-08-05 08:43:14",
      "updated_at": "2025-08-05 14:11:31",
      "user": null
    },
    "sale_products": [
      {
        "id": 19,
        "sales_id": 6,
        "products_id": 1,
        "quantity": 1,
        "base_value": 10,
        "product": {
          "id": 1,
          "users_id": 1,
          "name": "Produto 1",
          "quantity": 43,
          "price": 10,
          "is_active": 1,
          "created_at": "2025-08-05 08:12:51",
          "updated_at": null,
          "user": {
            "id": 1,
            "name": "Admin",
            "email": "test@example.com",
            "password": null,
            "is_active": 1,
            "created_at": "2025-08-05 08:10:55",
            "updated_at": null
          }
        }
      },
      {
        "id": 20,
        "sales_id": 6,
        "products_id": 3,
        "quantity": 1,
        "base_value": 24,
        "product": {
          "id": 3,
          "users_id": 1,
          "name": "Produto 3",
          "quantity": 84,
          "price": 24,
          "is_active": 1,
          "created_at": "2025-08-05 08:13:27",
          "updated_at": null,
          "user": {
            "id": 1,
            "name": "Admin",
            "email": "test@example.com",
            "password": null,
            "is_active": 1,
            "created_at": "2025-08-05 08:10:55",
            "updated_at": null
          }
        }
      }
    ],
    "user": {
      "id": 1,
      "name": "Admin",
      "email": "test@example.com",
      "password": null,
      "is_active": 1,
      "created_at": "2025-08-05 08:10:55",
      "updated_at": null
    }
  }
  ```

------

### POST /v1/sale/get/{id}

* **Descrição:** Retorna os detalhes de uma venda específica, incluindo cliente, forma de pagamento, produtos e valores.

* **Resposta de sucesso:**

  ```json
  {
    "id": 6,
    "payment_methods_id": 1,
    "users_id": 1,
    "customers_id": 1,
    "total_value": 34,
    "created_at": "2025-08-05 14:14:41",
    "updated_at": null,
    "payment_method": {
      "id": 1,
      "name": "Metodo 1",
      "installments": 8,
      "is_active": 1,
      "created_at": "2025-08-05 08:27:28",
      "updated_at": "2025-08-05 08:27:44",
      "user": null
    },
    "customer": {
      "id": 1,
      "users_id": 1,
      "name": "João da Silv 2a",
      "cpf": "123456412312",
      "email": "joao.silva@example.com",
      "zip_code": null,
      "street": "Rua das Flores",
      "number": "123",
      "complement": "Apartamento 45",
      "neighborhood": "Centro",
      "city": "São Paulo",
      "state": null,
      "is_active": 1,
      "created_at": "2025-08-05 08:43:14",
      "updated_at": "2025-08-05 14:11:31",
      "user": null
    },
    "sale_products": [
      {
        "id": 19,
        "sales_id": 6,
        "products_id": 1,
        "quantity": 1,
        "base_value": 10,
        "product": {
          "id": 1,
          "users_id": 1,
          "name": "Produto 1",
          "quantity": 43,
          "price": 10,
          "is_active": 1,
          "created_at": "2025-08-05 08:12:51",
          "updated_at": null,
          "user": null
        }
      },
      {
        "id": 20,
        "sales_id": 6,
        "products_id": 3,
        "quantity": 1,
        "base_value": 24,
        "product": {
          "id": 3,
          "users_id": 1,
          "name": "Produto 3",
          "quantity": 84,
          "price": 24,
          "is_active": 1,
          "created_at": "2025-08-05 08:13:27",
          "updated_at": null,
          "user": null
        }
      }
    ],
    "user": {
      "id": 1,
      "name": "Admin",
      "email": "test@example.com",
      "password": null,
      "is_active": 1,
      "created_at": "2025-08-05 08:10:55",
      "updated_at": null
    }
  }
  ```

---

### POST /v1/sale/get-all

* **Descrição:** Retorna uma lista paginada de vendas com detalhes do cliente, forma de pagamento, produtos e usuário responsável.

* **Body (exemplo):**

  ```json
  {
    "search": "",
    "page": 1,
    "page_count": 10
  }
  ```

* **Resposta de sucesso:**

  ```json
  [
    {
      "id": 6,
      "payment_methods_id": 1,
      "users_id": 1,
      "customers_id": 1,
      "total_value": 34,
      "created_at": "2025-08-05 14:14:41",
      "updated_at": null,
      "payment_method": {
        "id": 1,
        "name": "Metodo 1",
        "installments": 8,
        "is_active": 1,
        "created_at": "2025-08-05 08:27:28",
        "updated_at": "2025-08-05 08:27:44",
        "user": null
      },
      "customer": {
        "id": 1,
        "users_id": 1,
        "name": "João da Silv 2a",
        "cpf": "123456412312",
        "email": "joao.silva@example.com",
        "zip_code": null,
        "street": "Rua das Flores",
        "number": "123",
        "complement": "Apartamento 45",
        "neighborhood": "Centro",
        "city": "São Paulo",
        "state": null,
        "is_active": 1,
        "created_at": "2025-08-05 08:43:14",
        "updated_at": "2025-08-05 14:11:31",
        "user": null
      },
      "sale_products": [
        {
          "id": 19,
          "sales_id": 6,
          "products_id": 1,
          "quantity": 1,
          "base_value": 10,
          "product": {
            "id": 1,
            "users_id": 1,
            "name": "Produto 1",
            "quantity": 43,
            "price": 10,
            "is_active": 1,
            "created_at": "2025-08-05 08:12:51",
            "updated_at": null,
            "user": null
          }
        },
        {
          "id": 20,
          "sales_id": 6,
          "products_id": 3,
          "quantity": 1,
          "base_value": 24,
          "product": {
            "id": 3,
            "users_id": 1,
            "name": "Produto 3",
            "quantity": 84,
            "price": 24,
            "is_active": 1,
            "created_at": "2025-08-05 08:13:27",
            "updated_at": null,
            "user": null
          }
        }
      ],
      "user": {
        "id": 1,
        "name": "Admin",
        "email": "test@example.com",
        "password": null,
        "is_active": 1,
        "created_at": "2025-08-05 08:10:55",
        "updated_at": null
      }
    }
  ]
  ```

---

### POST /v1/sale/update

* **Descrição:** Atualiza uma venda existente com nova forma de pagamento, cliente e lista de produtos.

* **Body (exemplo):**

  ```json
  {
    "id": 6,
    "payment_methods_id": 1,
    "customers_id": 1,
    "products": [
      {
        "products_id": 1,
        "quantity": 1
      },
      {
        "products_id": 3,
        "quantity": 1
      }
    ]
  }
  ```

* **Resposta de sucesso:**

  ```json
  {
    "id": 6,
    "payment_methods_id": 1,
    "users_id": 1,
    "customers_id": 1,
    "total_value": 34,
    "created_at": "2025-08-05 14:14:41",
    "updated_at": "2025-08-05 14:19:04",
    "payment_method": {
      "id": 1,
      "name": "Metodo 1",
      "installments": 8,
      "is_active": 1,
      "created_at": "2025-08-05 08:27:28",
      "updated_at": "2025-08-05 08:27:44",
      "user": null
    },
    "customer": {
      "id": 1,
      "users_id": 1,
      "name": "João da Silv 2a",
      "cpf": "123456412312",
      "email": "joao.silva@example.com",
      "zip_code": null,
      "street": "Rua das Flores",
      "number": "123",
      "complement": "Apartamento 45",
      "neighborhood": "Centro",
      "city": "São Paulo",
      "state": null,
      "is_active": 1,
      "created_at": "2025-08-05 08:43:14",
      "updated_at": "2025-08-05 14:11:31",
      "user": null
    },
    "sale_products": [
      {
        "id": 21,
        "sales_id": 6,
        "products_id": 1,
        "quantity": 1,
        "base_value": 10,
        "product": {
          "id": 1,
          "users_id": 1,
          "name": "Produto 1",
          "quantity": 43,
          "price": 10,
          "is_active": 1,
          "created_at": "2025-08-05 08:12:51",
          "updated_at": null,
          "user": null
        }
      },
      {
        "id": 22,
        "sales_id": 6,
        "products_id": 3,
        "quantity": 1,
        "base_value": 24,
        "product": {
          "id": 3,
          "users_id": 1,
          "name": "Produto 3",
          "quantity": 84,
          "price": 24,
          "is_active": 1,
          "created_at": "2025-08-05 08:13:27",
          "updated_at": null,
          "user": null
        }
      }
    ],
    "user": {
      "id": 1,
      "name": "Admin",
      "email": "test@example.com",
      "password": null,
      "is_active": 1,
      "created_at": "2025-08-05 08:10:55",
      "updated_at": null
    }
  }
  ```

---

### POST /v1/sale/delete

* **Descrição:** Remove uma venda pelo seu ID.

* **Body (exemplo):**

  ```json
  {
    "id": 6
  }
  ```

* **Resposta de sucesso:**

  ```json
  null
  ```

---

### GET /v1/address/get/{cep}

* **Descrição:** Consulta endereço pelo CEP.

* **Parâmetros:**

  * `cep` (string) — Código postal (CEP) para consulta. Exemplo: `89708052`.

* **Resposta de sucesso:**

  ```json
  {
    "cep": "89708-052",
    "logradouro": "Travessa Felipe Turmena",
    "complemento": "",
    "unidade": "",
    "bairro": "Sunti",
    "localidade": "Concórdia",
    "uf": "SC",
    "estado": "Santa Catarina",
    "regiao": "Sul",
    "ibge": "4204301",
    "gia": "",
    "ddd": "49",
    "siafi": "8083"
  }
  ```

---
