<?php

set_include_path(
        get_include_path()
        . PATH_SEPARATOR . __DIR__ . '/../../src/'
);

require 'Infinity/Common/ClassLoader/Autoloader.php';

use Infinity\Common\ClassLoader\Autoloader;
use Infinity\Common\Http\LandHelper;

$autoloader = new Autoloader();
$autoloader->register();


$_SERVER['HTTP_HOST']       = 'www.mysite.com';
$_SERVER['HTTP_REFERER']    =
    'http://www.othersite.com/page1.html?someparam=somevalue';
$_GET['myTag']  = 'hello world';
$_GET['tag2']   = 'hello world 2';

$landHelper = new LandHelper();

$persistTags = array(
    'myTag' => 'session',
    'tag2'  => 'P2D',
);

$landHelper->setPersistedLandParamRules($persistTags);

$landHelper->run();

var_dump($_COOKIE);
