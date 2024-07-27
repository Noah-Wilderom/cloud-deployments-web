#!/bin/bash

apt update && apt upgrade -y

snap install --classic certbot
ln -s /snap/bin/certbot /usr/bin/certbot