#!/bin/bash

sudo -u {{USER_NAME}} bash -c "GIT_SSH_COMMAND=\"ssh -o StrictHostKeyChecking=no\" git clone {{GITHUB_SSH}} ~/public_html"
