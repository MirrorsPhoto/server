# Ламинация

## Получение размеров

Получить все варианты размеров с ценами

* **URL**

    `GET`  **/lamination/size**

* **Ответ**

    ```json
    {
      "status": "OK",
      "response": [
        {
          "id": 1,
          "format": "A4",
          "price": 30
        },
        {
          "id": 2,
          "format": "A5",
          "price": 20
        }
      ]
    }
    ```