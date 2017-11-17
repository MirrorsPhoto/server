#!/bin/sh

cd /app
php composer.phar update
vendor/bin/phinx migrate