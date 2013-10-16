<?php

set_include_path(
        get_include_path()
        . PATH_SEPARATOR . __DIR__ . '/../../src/'
);

require 'Infinity/Common/ClassLoader/Autoloader.php';

use Infinity\Common\ClassLoader\Autoloader;
use Infinity\Common\Json;

$autoload = new Autoloader();
$autoload->register();

$o = new StdClass;
$o->key = 'value';

$encoded = Json\Parser::encode( $o );

echo $encoded . "\n";

$decoded = Json\Parser::decode( $encoded );

var_dump( $decoded );
