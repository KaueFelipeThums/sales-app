
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

# Grupo Auth - Rotas de Autenticação

