#!/usr/bin/env bash

cd /app
composer update
vendor/bin/phinx migrate

#############################################
## Supervisord (start daemons)
#############################################

## Start services
exec /opt/docker/bin/service.d/supervisor.sh


