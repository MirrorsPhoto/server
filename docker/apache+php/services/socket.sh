#!/bin/sh
exec 2>&1
exec php /app/websocket_server.php -d start;