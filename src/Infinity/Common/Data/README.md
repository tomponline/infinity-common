Environment
=====================

The Environment class provides an intelligent variable container that can
execute other functions when certain variable prefixes are retrieved.

Example use:

Using a Bundled Data Provider

Data Providers are pre-built collections of variables that can configure
their prefixes themselves by registering them with the Environment object.

```php
use Infinity\Common\Data\Environment;
use Infinity\Common\Data\Environment\Provider;

$environment = new Environment();
$geoip = new Provider\Geoip();
$environment->registerProvider( $geoip );

var_dump($environment->get('geoip_client_country'));
```

Using a custom function callback

You can also use custom callbacks to implement other environment variables.

```php
use Infinity\Common\Data\Environment;

$environment = new Environment();

function helloWorld()
{
    return "hello world";
}

$environment->registerPrefixHandler( 'hello', 'helloWorld' );

var_dump( $environment->get('hello') );
```

Using Caching

If a variable value is slow to lookup and may be used multiple times within
a single request, then you can indicate that the first
response should be cached.

```php
use Infinity\Common\Data\Environment;

$environment = new Environment();

function slowVariable()
{
    sleep(2);
    return "this is a slow variable to get";
}

$environment->registerPrefixHandler( 'slowVar', 'slowVariable', $cache = TRUE );

var_dump( $environment->get('slowVar') ); //This will take 2 seconds
var_dump( $environment->get('slowVar') ); //This will be instant
```

VariantHelper
=====================

The VariantHelper provides the ability to make variant decisions based on a configuration using data from the application's environment

###Basic configuration setup

The configuration array for the variants should have at minimum an array of ```variants``` and a ```defaultReturn``` value of any type

```php
$config = array(
    'variants'  => array(),
    'defaultReturn' => 'defaultReturnValue',
);
```

####Variant configuration

The variants should be configured as a two demensional array of criteria and return values

```php
$config = array(
    'variants'  => array(
        array(
            'criteria'  => array(),
            'return'    => 'returnValue',
        ),
        array(
            'criteria'  => array(),
            'return'    => 'someOtherReturnValue',
        ),
    ),
    'defaultReturn' => 'defaultReturnValue',
);
```

####Criteria configuration

Criterion are configured as a two dimensional array with three values. A ```field```, an ```op``` and a ```value```

```php
$config = array(
    'variants'  => array(
        array(
            'criteria'  => array(
                array(
                    'field' => 'visits',
                    'op'    => 'eq',
                    'value' => 0,
                ),
                array(
                    'field' => 'ip_country',
                    'op'    => 'eq',
                    'value' => 'US',
                ),
            ),
            'return'    => 'returnValue',
        ),
        array(
            'criteria'  => array(
                array(
                    'field' => 'visits',
                    'op'    => 'gt',
                    'value' => 0,
                ),
                array(
                    'field' => 'ip_country',
                    'op'    => 'eq',
                    'value' => 'US',
                ),
            ),
            'return'    => 'someOtherReturnValue',
        ),
    ),
    'defaultReturn' => 'defaultReturnValue',
);
```

###Usage

The basic process of using VariantHelper is to

* Setup the environment
* Create a variant config
* Set the config
* Set the environment
* Run it

```setConfig()```, ```setEnvironment()``` and ```run()``` will all throw exceptions on error

```php
//First we need to initialise some Environment variables
$environment = new Environment();
$environment->registerPrefixHandler( 'ip_country', function(){
    return 'US';
} );
$environment->registerPrefixHandler( 'visits', function(){
    return 2;
} );

//Initialise an instance of the VariantHelper
$variantHelper = new VariantHelper();

//Create the config
$config = array(
    //Config options
);

try
{
    //Set the config options
    $variantHelper->setConfig( $config );

    //Set the Environment we created earlier
    $variantHelper->setEnvironment( $environment );

    //Run it
    //$ret will be either the first variant that matched against the critera
    //or the defaultReturn value
    $ret = $variantHelper->run();
}
catch( Exception $e )
{
    echo $e->getMessage();
}
```
