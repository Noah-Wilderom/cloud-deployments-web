#!/bin/bash

add-apt-repository ppa:longsleep/golang-backports -y
apt update
apt install golang-go -y
