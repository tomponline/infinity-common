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

var_dump( $environment->get('slowVar') ); //This will take 2 seconfs
var_dump( $environment->get('slowVar') ); //This will be instant
```
