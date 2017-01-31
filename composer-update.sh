#!/usr/bin/env bash


#disable xdebug
sudo mv /usr/local/etc/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini.stop

#composer
php -d memory_limit=-1 composer.phar update -vvv --profile

#re-enable xdebug
sudo mv /usr/local/etc/php/conf.d/xdebug.ini.stop /usr/local/etc/php/conf.d/xdebug.ini

