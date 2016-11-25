#!/usr/bin/env bash

bin/console doctrine:schema:update --force
bin/console doctrine:fixtures:load -n
composer install -n -o
bin/console assetic:dump
