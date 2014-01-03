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

//GeoIP provider
$environment = new Environment();
$geoip = new Provider\Geoip();
$environment->registerProvider( $geoip );

//IP Provider
$ip = new Provider\Ip();
$environment->registerProvider( $ip );
$_SERVER[ 'REMOTE_ADDR' ] = '8.8.8.8';

//URL Provider
$url = new Provider\Url();
$environment->registerProvider( $url );
$_GET[ 'test' ] = 'test url param value';
$_SERVER[ 'HTTP_HOST' ] = 'test.com';

//Cookie Provider
$cookie = new Provider\Cookie();
$environment->registerProvider( $cookie );
$_COOKIE[ 'test' ] = 'test cookie value';

//Visit Count Provider
$visitCount = new Provider\VisitCount();
$environment->registerProvider( $visitCount );
$_SERVER[ 'HTTP_HOST' ] = 'example.com';
$_SERVER[ 'HTTP_REFERER' ] = 'http://www.example.com/page/?query=params';
$_COOKIE[ 'ic_visits' ] = 2;

//Local callback prefix
function helloWorld()
{
    return "hello world";
}

$environment->registerPrefixHandler( 'hello', 'helloWorld' );

//Access variables
var_dump($environment->get('visit_count'));
var_dump($environment->get('ip_address'));
var_dump($environment->get('geoip_client_country'));
var_dump($environment->get('url_param_test'));
var_dump($environment->get('url_domain'));
var_dump($environment->get('cookie_test'));
var_dump($environment->get('hello'));
