<?php

set_include_path(
        get_include_path()
        . PATH_SEPARATOR . __DIR__ . '/../../src/'
);

require 'Infinity/Common/ClassLoader/Autoloader.php';

use Infinity\Common\ClassLoader\Autoloader;
use Infinity\Common\Data\Environment;
use Infinity\Common\Data\Environment\Provider;

$autoload = new Autoloader();
$autoload->register();

$environment = new Environment();
$geoip = new Provider\Geoip();
$environment->registerProvider( $geoip );

$request = new Provider\Request();
$environment->registerProvider( $request );

$_SERVER[ 'REMOTE_ADDR' ] = '8.8.8.8';

var_dump($environment->get('request_ip'));
var_dump($environment->get('geoip_country'));
