    <?php

set_include_path(
        get_include_path()
        . PATH_SEPARATOR . __DIR__ . '/../../src/'
);

require 'Infinity/Common/ClassLoader/Autoloader.php';

use Infinity\Common\ClassLoader\Autoloader;
use Infinity\Common\Data\Environment;
use Infinity\Common\Data\VariantHelper;

$autoload = new Autoloader();
$autoload->register();

$environment = new Environment();
$environment->registerPrefixHandler( 'ip_country', function(){
    return 'UK';
} );
$environment->registerPrefixHandler( 'visits', function(){
    return 2;
} );

$variantHelper = new VariantHelper();
$variantHelper->setEnvironment( $environment );

$variantConfig = array(
    'variants'  => array(
        array(
            'criteria'    => array(
                array(
                    'field'   => 'ip_country',
                    'op'    => 'eq',
                    'value' => 'UK',
                ),
                array(
                    'field'   => 'visits',
                    'op'    => 'gt',
                    'value' => 1,
                ),
            ),
            'return'     => 'block1',
        ),
        array(
            'criteria'    => array(
                array(
                    'field'   => 'ip_country',
                    'op'    => 'eq',
                    'value' => 'US',
                ),
                array(
                    'field'   => 'visits',
                    'op'    => 'gt',
                    'value' => 1,
                ),
            ),
            'return'     => 'block2',
        ),
    ),
    'defaultReturn'   => 'block3',
);

try
{
    $variantHelper->setConfig( $variantConfig );
    $ret = $variantHelper->run();
    var_dump( $ret );
}
catch( Exception $e )
{
    echo 'Error: ' . $e->getMessage() . "\n";
}
