#!/bin/bash

apt update && apt upgrade -y

ufw allow 22
ufw --force enable

systemctl restart ssh

mkdir -p /projects
chmod 711 /projects
