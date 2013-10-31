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

$ip = new Provider\Ip();
$environment->registerProvider( $ip );

$_SERVER[ 'REMOTE_ADDR' ] = '8.8.8.8';

var_dump($environment->get('ip_address'));
var_dump($environment->get('geoip_client_country'));


function helloWorld()
{
    return "hello world";
}

$environment->registerPrefixHandler( 'hello', 'helloWorld' );

var_dump( $environment->get('hello') );
