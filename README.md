[![Build Status](https://travis-ci.com/MirrorsPhoto/server.svg?branch=master)](https://travis-ci.com/MirrorsPhoto/server)
[![CodeFactor](https://www.codefactor.io/repository/github/mirrorsphoto/server/badge)](https://www.codefactor.io/repository/github/mirrorsphoto/server)

![DB diagram](https://api.genmymodel.com/projects/_mgeDUIHKEeeveJPbhFhy-g/diagrams/_mgeDUoHKEeeveJPbhFhy-g/svg)


## Развернуть окружение
```bash
cp .env.dist .env
docker-compose up -d
```
### Установка зависимостей
```bash
docker exec -it mirrors_php_1 composer install
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
docker exec -it mirrors_tests_1 composer install
docker exec -it mirrors_tests_1 vendor/bin/behat --config=behat/behat.yml
```
