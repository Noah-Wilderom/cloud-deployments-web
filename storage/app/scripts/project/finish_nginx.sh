#!/bin/bash

apt update && apt upgrade -y

mv /etc/nginx/sites-available/{{DOMAIN}} /etc/nginx/sites-available/{{DOMAIN}}.http
mv /etc/nginx/sites-available/{{DOMAIN}}.https /etc/nginx/sites-available/{{DOMAIN}}

systemctl restart nginx
