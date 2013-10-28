<?php

set_include_path(
        get_include_path()
        . PATH_SEPARATOR . __DIR__ . '/../../src/'
);

require 'Infinity/Common/ClassLoader/Autoloader.php';

use Infinity\Common\ClassLoader\Autoloader;
use Infinity\Common\Data\Environment;

$autoload = new Autoloader();
$autoload->register();

$environment = new Environment();

$_SERVER[ 'REMOTE_ADDR' ] = '8.8.8.8';

var_dump($environment->get('hostname'));
var_dump($environment->get('geoip_country'));
