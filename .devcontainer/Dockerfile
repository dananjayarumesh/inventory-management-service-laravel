FROM php:8.4-fpm
# https://hub.docker.com/_/php

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y \
        procps \
        iputils-ping \
        net-tools \
        lsb-release \
        libzip-dev \
        git \
        unzip && \
    docker-php-ext-install pdo_mysql zip
# procps: to support command 'ps'
# iputils-ping: to support command 'ping' (https://linuxconfig.org/ping-command-not-found-on-ubuntu-20-04-focal-fossa-linux)
# net-tools: to support command such as 'arp', 'ifconfig', 'netstat', etc. (https://helpmanual.io/packages/apt/net-tools/)
# lsb-release: to support commmand 'lsb_release -a'
# libzip-dev: required for building PHP's zip extension
# git: to manage source code repositories
# unzip: to extract .zip files

################################################################################
# Install nginx
# Instructions for Debian from: https://nginx.org/en/linux_packages.html
RUN apt-get install -y \
    gnupg2 \
    ca-certificates \
    debian-archive-keyring
RUN curl https://nginx.org/keys/nginx_signing.key | gpg --dearmor \
    | tee /usr/share/keyrings/nginx-archive-keyring.gpg > /dev/null
RUN gpg --dry-run --quiet --no-keyring --import --import-options import-show /usr/share/keyrings/nginx-archive-keyring.gpg
RUN echo "deb [signed-by=/usr/share/keyrings/nginx-archive-keyring.gpg] \
    http://nginx.org/packages/debian `lsb_release -cs` nginx" \
    | tee /etc/apt/sources.list.d/nginx.list
RUN apt-get update
RUN apt-get install -y nginx

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Create non-root user (zero trust principle, least privileged principle)
RUN groupadd -g 1000 -r dev && \
    useradd -u 1000 -r -g dev -m -s $(which bash) dev
# Option '-m' to create home directory (see https://askubuntu.com/a/393470)
# Option '-s' to set shell for this user (see comment in https://askubuntu.com/a/393470)
# Option '-r' creates a system user which does not expire (see https://linuxize.com/post/how-to-create-users-in-linux-using-the-useradd-command/)

# How to add sudo support for the non-root user is described at https://code.visualstudio.com/remote/advancedcontainers/add-nonroot-user#_creating-a-nonroot-user
# However, standard practice is not to add sudo support for security reasons. VS Code will connect
# to the running dev container as the user specified in file ".devcontainer/devcontainer.json" in
# property "remoteUser".

# Create working directory. Ownership will be changed in entrypoint.sh which
# executes *after* the volume has been mounted.
RUN mkdir /work
