#!/usr/bin/env bash

env | sed "s/\(.*\)=\(.*\)/env \1;/" >> /usr/local/openresty/nginx/conf/nginx.conf

/usr/local/openresty/bin/openresty -g 'daemon off;'