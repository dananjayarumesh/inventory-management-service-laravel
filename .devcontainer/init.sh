#!/bin/sh

echo loc 240417-1038: running init.sh ...

#################################################################################################################
# Provided the Dockerfile doesn't change the user, this script will run as 'root'.

# Start php-fpm
echo ************************************
php-fpm -D
echo loc 240606-1154: starting php-fpm as background demon ...

################################################################################
# Start nginx
echo ************************************
echo loc 240417-1039: starting nginx as background demon ...
nginx &

#################################################################################################################
echo Running init.sh complete

#################################################################################################################
# Finally invoke what has been specified as CMD in Dockerfile or command in docker-compose:
"$@"