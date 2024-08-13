#!/bin/bash

apt update && apt upgrade -y

mysql -u root -e "CREATE DATABASE {{DB_NAME}};"
mysql -u root -e "CREATE USER '{{DB_USER}}'@'%' IDENTIFIED BY '{{DB_PASSWORD}}';"
mysql -u root -e "GRANT CREATE, ALTER, DROP, INSERT, UPDATE, INDEX, DELETE, SELECT, REFERENCES ON {{DB_NAME}}.* TO '{{DB_USER}}'@'%';"
mysql -u root -e "FLUSH PRIVILEGES;"
