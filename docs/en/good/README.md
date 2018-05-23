# Good 

## Add 
 
Add new good in stock 
 
```shell 
curl --request POST \ 
  --url 'http://{{url}}/good/add' \ 
  --header 'Authorization: Bearer {{token}}' \ 
  --header 'Content-Type: application/json' \ 
  --data '{"name":"Тестовый товар","bar_code":"{{good_bar_code}}"}' 
``` 
 
> The above command returns JSON structured like this: 
 
```json 
{ 
  "status": "OK", 
  "response": { 
    "id": "13", 
    "name": "Тестовый товарdb57879e-5dea-4040-b6e8-6efaa10611d5", 
    "description": null, 
    "bar_code": "1234567890123", 
    "datetime_сreate": null 
  } 
} 
``` 
 
### Query Parameters 
 
Parameter | Description | Required | 
--------- | ----------- | -------- | 
name | Name | Required | 
bar_code | Bar code | Required | 
 
# Photo 
 
## Get size 
 
Get variations size for photo 
 
```shell 
curl --request GET \ 
  --url 'http://{{url}}/photo/size' \ 
  --header 'Authorization: Bearer {{token}}' \ 
  --header 'Content-Type: application/json' 
``` 
 
> The above command returns JSON structured like this: 
 
```json 
{ 
  "status": "OK", 
  "response": [ 
    { 
      "id": 1, 
      "width": "2.5", 
      "height": "3", 
      "variations": { 
        "4": 100 
      } 
    }, 
    { 
      "id": 2, 
      "width": "3", 
      "height": "4", 
      "variations": { 
        "4": 100, 
        "6": 140 
      } 
    }, 
    { 
      "id": 3, 
      "width": "3.6", 
      "height": "4.6", 
      "variations": { 
        "2": 55, 
        "4": 110 
      } 
    }, 
    { 
      "id": 4, 
      "width": "4", 
      "height": "6", 
      "variations": { 
        "2": 100 
      } 
    }, 
    { 
      "id": 5, 
      "width": "5", 
      "height": "5", 
      "variations": { 
        "2": 110 
      } 
    }, 
    { 
      "id": 6, 
      "width": "9", 
      "height": "12", 
      "variations": { 
        "1": 110 
      } 
    }, 
    { 
      "id": 7, 
      "width": "10", 
      "height": "15", 
      "variations": { 
        "1": 110 
      } 
    } 
  ] 
} 
```