#!/bin/bash
set -e
cd "`dirname "$0"`"

if [ ! -f app/config/parameters.yml ]; then
    cp app/config/parameters.yml.dist app/config/parameters.yml
fi

if [ ! -f composer.phar ]; then
    curl -s http://getcomposer.org/installer | php
fi

php composer.phar install

rm -rf app/cache/* app/logs/*

./app/console assets:install --symlink
./app/console doctrine:database:drop --force || true
./app/console doctrine:database:create
./app/console doctrine:schema:create
./app/console doctrine:fixtures:load --append
./app/console assetic:dump --env=prod --no-debug
