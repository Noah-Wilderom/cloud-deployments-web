#!/bin/bash

HOMEDIR="/projects/{{USER_NAME}}"

apt update && apt upgrade -y

useradd -s /bin/bash -m -d $HOMEDIR -p $(openssl passwd -1 '{{USER_PASSWORD}}') {{USER_NAME}}
usermod -aG {{USER_NAME}} www-data

sudo chmod o+x $HOMEDIR

sudo -u {{USER_NAME}} mkdir -p $HOMEDIR/.ssh

echo "{{SSH_PUB_KEY}}" >> $HOMEDIR/.ssh/id_rsa.pub
echo "{{SSH_PRIV_KEY}}" >> $HOMEDIR/.ssh/id_rsa

chmod 750 $HOMEDIR
chown -R {{USER_NAME}}:{{USER_NAME}} $HOMEDIR

chmod 700 $HOMEDIR/.ssh
chmod 600 $HOMEDIR/.ssh/id_rsa
chmod 644 $HOMEDIR/.ssh/id_rsa.pub
