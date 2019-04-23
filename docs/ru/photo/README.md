# Фото

## Получение размеров

Получить все варианты размеров с ценами и количеством штук

* **URL**

    `GET`  **/photo/size**

* **Ответ**

    ```json
    {
      "status": "OK",
      "response": [
        {
          "width": 2.5,
          "height": 3,
          "variations": [
            {
              "id": 1,
              "count": 4,
              "price": 100
            }
          ]
        },
        {
          "width": 3,
          "height": 4,
          "variations": [
            {
              "id": 2,
              "count": 4,
              "price": 100
            },
            {
              "id": 3,
              "count": 6,
              "price": 140
            }
          ]
        }
      ]
    }
    ```