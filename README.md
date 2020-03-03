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
docker-compose exec php composer install
```
### Миграции
```bash
docker-compose exec php vendor/bin/phinx migrate
```
### Сиды
```bash
docker-compose exec php vendor/bin/phinx seed:run
```

## Тесты
```bash
docker-compose exec tests bash -c 'cd /code/tests; composer install;'
docker-compose exec tests tests/vendor/bin/behat --config=tests/behat/behat.yml
docker-compose exec tests tests/vendor/bin/phpcs /code

```
