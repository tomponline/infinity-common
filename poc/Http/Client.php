<?php

set_include_path(
    get_include_path()
    . PATH_SEPARATOR . __DIR__ . '/../../src/'
);

require 'Infinity/Common/ClassLoader/Autoloader.php';

use Infinity\Common\ClassLoader\Autoloader;
use Infinity\Common\Http\Client;

$autoloader = new Autoloader();
$autoloader->register();

$client = new Client();
$client->setRequestUri( '127.0.0.1' );

echo 'Request: ' . var_dump( $client->sendRequest() );
echo 'User-Agent: ' . var_dump( $client->getResponseHeader( 'User-Agent' ) );
echo 'Response Code: ' . var_dump( $client->getResponseCode() );
echo 'Response Size: ' . var_dump( $client->getResponseSize() );
echo 'Downloaded Size: ' . var_dump( $client->getDownloadedSize() );
echo 'Response Result: ' . var_dump( $client->getResponseResult() );
echo 'Response Handle: ' . var_dump( stream_get_contents( $client->getResponseHandle() ) ) . "\n";
