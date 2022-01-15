#!/bin/sh

# If you would like to do some extra provisioning you may
# add any commands you wish to this file and they will
# be run after the Homestead machine is provisioned.
#
# If you have user-specific configurations you would like
# to apply, you may also create user-customizations.sh,
# which will be run after this script.

# timezone
sudo timedatectl set-timezone "Europe/Amsterdam"

# install nvm
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.1/install.sh | bash

export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"  # This loads nvm
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"  # This loads nvm bash_completion

# switch to correct node version
cd /home/vagrant/code
nvm install
nvm use

# switch to correct php version
sudo update-alternatives --set php /usr/bin/php8.0
sudo update-alternatives --set php-config /usr/bin/php-config8.0
sudo update-alternatives --set phpize /usr/bin/phpize8.0
