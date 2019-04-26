# Авторизация

## Получение токена

* **URL**

    `POST`  **/login**
    
* **Параметры**

  | Название      | Описание  | Обязательный |
  |:--------------|:---------:| :-----------:|
  | `login`       | Логин     | **+**        |
  | `password`    | Пароль    | **+**        |

* **Ответ**

	```json
	{
	  "status": "OK",
	  "response": {
	      "token": "eyJ0eXAiOiJKV1Qi0CJhbGciOiJIUzI1NiJ9.eyJpZCI6MSwidXNlcm5hbWUiOiJhZG1pbiIsImZpcnN0X25hbWUiOiJcdTA0MTBcdTA0MzRcdTA0M2NcdTA0MzhcdTA0M2QiLCJtaWRkbGVfbmFtZSI6bnVsbCwibGFzdF9uYW1lIjoiXHUwNDEwXHUwNDM0XHUwNDNjXHUwNDM4XHUwNDNkXHUwNDQxXHUwNDNhXHUwNDM4XHUwNDM5IiwiZW1haWwiOiJhZG1pbkBtaXJyb3JzLXBob3RvLnJ1Iiwicm9sZV9pZCI6Miwicm9sZV9waHJhc2UiOiJ1c2VyLnJvbGVzLmFkbWluIiwiYXZhdGFyIjoiIn0.STzqaq2NCTbDxO6wFHBx-sup2wIPibUCE7SztbQEhN8"
	  }
	}
	```
	
## Проверка токена

Возвращает `true` если токен действителен

* **URL**

    `GET`  **/auth/check**

* **Ответ**

	```json
	{
	  "status": "OK",
	  "response": {
	      "token": "eyJ0eXAiOiJKV1Qi0CJhbGciOiJIUzI1NiJ9.eyJpZCI6MSwidXNlcm5hbWUiOiJhZG1pbiIsImZpcnN0X25hbWUiOiJcdTA0MTBcdTA0MzRcdTA0M2NcdTA0MzhcdTA0M2QiLCJtaWRkbGVfbmFtZSI6bnVsbCwibGFzdF9uYW1lIjoiXHUwNDEwXHUwNDM0XHUwNDNjXHUwNDM4XHUwNDNkXHUwNDQxXHUwNDNhXHUwNDM4XHUwNDM5IiwiZW1haWwiOiJhZG1pbkBtaXJyb3JzLXBob3RvLnJ1Iiwicm9sZV9pZCI6Miwicm9sZV9waHJhc2UiOiJ1c2VyLnJvbGVzLmFkbWluIiwiYXZhdGFyIjoiIn0.STzqaq2NCTbDxO6wFHBx-sup2wIPibUCE7SztbQEhN8"
	  }
	}
	```