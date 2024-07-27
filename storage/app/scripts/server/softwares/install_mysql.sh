#!/bin/bash

apt update && apt upgrade -y

apt install mysql-server -y

systemctl start mysql.service

ufw allow 3306

sed -i "s/^bind-address\s*=.*/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf && systemctl restart mysql

systemctl restart mysql

mysql -u root -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH auth_socket;"