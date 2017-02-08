<?php

umask(0000);

if(function_exists('xdebug_disable')) {
    xdebug_disable();
}

include(__DIR__.'/vendor/autoload.php');