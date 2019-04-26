# Продажа

## Чеком

Продажа фото, копий, ламинаций, товаров, услуг пачкой

* **URL**

    `POST`  **/sale/batch**

* **Пример запроса**

    ```json
    {
      "items" : [
        {
          "type": "photo",
          "id": 1,
          "copies": 2
        },
        {
          "type": "copy",
          "copies": 2
        },
        {
          "type": "lamination",
          "id": 2,
          "copies": 4
        },
        {
          "type": "service",
          "id": 2,
          "copies": 4
        },
        {
          "type": "service",
          "id": 6,
          "copies": 4
        }
      ]
    }
    ```