<?php

set_include_path(
        get_include_path()
        . PATH_SEPARATOR . __DIR__ . '/../../src/'
);

require 'Infinity/Common/ClassLoader/Autoloader.php';

use Infinity\Common\ClassLoader\Autoloader;
use Infinity\Common\Db\Geoip;

$autoload = new Autoloader();
$autoload->register();

$ip = '8.8.8.8';

$geoip = new Geoip();
var_dump( $geoip->getCountryCode( $ip ) );
var_dump( $geoip->getCityInfo( $ip ) );
