#!/bin/bash

if [ $(uname -s) = "Darwin" ]; then
    #assume OS X
    cd /opt/local/wt
    sudo git pull
else
    cd /opt/wt
    sudo git pull
fi