#!/bin/bash

apt update && apt upgrade -y

certbot certonly --nginx -d {{DOMAIN}} --non-interactive --agree-tos --email {{AUTH_EMAIL}}

systemctl reload nginx
