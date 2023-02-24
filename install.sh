#!/bin/bash

composer install
yarn run dev

bin/console doctrine:database:create
bin/console doctrine:migrations:migrate
