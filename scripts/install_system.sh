#!/bin/bash
if [[ $(/usr/bin/id -u) -ne 0 ]]; then
    echo "Not running as root"
    exit
fi

psql -V || (
    echo 'better start installin'
    touch /etc/apt/sources.list.d/pgdg.list
    echo "deb http://apt.postgresql.org/pub/repos/apt/ $(lsb_release -sc)-pgdg main" >> /etc/apt/sources.list.d/pgdg.list
    wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | \
      apt-key add -

    apt-get update -y
    apt-get upgrade -y
    apt-get dist-upgrade -y
    apt-get autoremove -y

    apt-get install -y \
        p7zip-rar p7zip-full unace unrar zip unzip sharutils rar uudeview mpack arj cabextract file-roller \
        git curl \
        nano screen \
        gcc g++ postgresql-10 \
        silversearcher-ag \
        dnsutils linkchecker \
        nodejs \
        postgresql-10 \
        redis-server \
        nginx \
        php7.2 \
        php7.2-fpm php7.2-cli php7.2-curl php7.2-gd php7.2-dev php7.2-pgsql \
        imagemagick php7.2-imagick \
        php7.2-bcmath php7.2-mbstring php7.2-imap php7.2-soap \
        php7.2-zip \

    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer

cat >/etc/php/7.2/fpm/conf.d/99-dev-machine.ini << EOF2
upload_max_filesize=25M
post_max_size=25M
display_startup_errors=1
display_errors=1
EOF2

cat >/etc/php/7.2/cli/conf.d/99-dev-machine.ini << EOF3
upload_max_filesize=25M
post_max_size=25M
display_startup_errors=1
display_errors=1
EOF3

    apt-get -f install
    apt-get -y autoremove
    apt-get -y autoclean
    apt-get -y clean

    chown $SUDO_USER:$SUDO_USER /var/run/php/php7.2-fpm.sock
    # echo "nano /etc/php/7.2/fpm/pool.d/www.conf"
    sed -i "s/www-data/$SUDO_USER/g" /etc/php/7.2/fpm/pool.d/www.conf

);
