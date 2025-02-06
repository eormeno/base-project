#!/bin/bash

sudo docker exec -i mysql-container mysql -u root -p'root' -e "DROP DATABASE games; CREATE DATABASE games;"
sudo docker compose run --rm artisan migrate
sudo docker compose run --rm artisan db:seed
sudo docker compose run --rm artisan games

echo -e "\e[32mDatabase has been reset.\e[0m"