#!/bin/bash

if [ $(uname -s) = "Darwin" ]; then
    #assume OS X
    sudo mkdir -p /opt/local
    cd /opt/local
    sudo git clone https://github.com/istvan-antal/wt.git
    sudo ln -s /opt/local/wt/wt.php /opt/local/bin/wt
else
    sudo mkdir -p /opt
    cd /opt
    sudo git clone https://github.com/istvan-antal/wt.git
    sudo ln -s /opt/wt/wt.php /usr/local/bin/wt
fi