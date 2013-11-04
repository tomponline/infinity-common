<?php

set_include_path(
        get_include_path()
        . PATH_SEPARATOR . __DIR__ . '/../../src/'
);

require 'Infinity/Common/ClassLoader/Autoloader.php';

use Infinity\Common\ClassLoader\Autoloader;
use Infinity\Common\Http\Request;

$autoloader = new Autoloader();
$autoloader->register();

$request = new Request();

var_dump($request->getBodyParameters());
var_dump($request->getRawBody());
