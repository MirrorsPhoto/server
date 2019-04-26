[![CodeFactor](https://www.codefactor.io/repository/github/mirrorsphoto/server/badge)](https://www.codefactor.io/repository/github/mirrorsphoto/server)

![DB diagram](https://api.genmymodel.com/projects/_mgeDUIHKEeeveJPbhFhy-g/diagrams/_mgeDUoHKEeeveJPbhFhy-g/svg)


## Развернуть окружение
```bash
cp .env.dist .env
docker-compose up -d
```
### Установка зависимостей
```bash
docker exec -it mirrors_php_1 php composer.phar install
```
### Миграции
```bash
docker exec -it mirrors_php_1 vendor/bin/phinx migrate
```
### Сиды
```bash
docker exec -it mirrors_php_1 vendor/bin/phinx seed:run
```

## Тесты
```bash
docker exec -it mirrors_behat_1 vendor/bin/behat
```
