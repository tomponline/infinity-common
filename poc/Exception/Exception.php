<?php

set_include_path(
        get_include_path()
        . PATH_SEPARATOR . __DIR__ . '/../../src/'
);

require 'Infinity/Common/ClassLoader/Autoloader.php';

use Infinity\Common\ClassLoader\Autoloader;
use Infinity\Common\Exception\Exception;

$autoload = new Autoloader();
$autoload->register();

try
{
    throw new Exception( "There has been a problem" );
}
catch( Exception $e )
{
    var_dump($e);
}
