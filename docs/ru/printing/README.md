# Распечатка

## Получение

Получить все виды распечаток с ценами

* **URL**

    `GET`  **/printing**

* **Ответ**

    ```json
    {
      "status": "OK",
      "response": [
        {
          "id": 1,
          "name": "A4",
          "color": false,
          "photo": false,
          "ext": false,
          "price": 4
        },
        {
          "id": 2,
          "name": "A4",
          "color": true,
          "photo": false,
          "ext": false,
          "price": 20
        }
      ]
    }
    ```