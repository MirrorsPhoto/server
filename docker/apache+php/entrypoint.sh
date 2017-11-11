#!/usr/bin/env bash

cd /app
composer update
./vendor/phalcon/devtools/phalcon.php migration run --config=./api/config/config.ini

#############################################
## Supervisord (start daemons)
#############################################

## Start services
exec /opt/docker/bin/service.d/supervisor.sh


