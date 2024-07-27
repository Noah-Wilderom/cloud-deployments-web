#!/bin/bash

apt update && apt upgrade -y

apt install nginx -y

ufw allow "Nginx Full"