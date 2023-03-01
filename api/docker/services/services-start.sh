#!/bin/bash
composer install --no-dev --ignore-platform-reqs \
&& chmod 777 -R storage \
&& php-fpm -F --pid /opt/bitnami/php/tmp/php-fpm.pid -y /opt/bitnami/php/etc/php-fpm.conf