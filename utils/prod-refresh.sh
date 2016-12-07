#!/usr/bin/env bash

bin/console doctrine:database:create --if-not-exists -e prod
bin/console doctrine:schema:update --force -e prod
bin/console doctrine:fixtures:load -n
bin/console fos:js-routing:dump -e prod
composer install -n -o
bin/console assetic:dump -e prod
