Exception
=====================

The Exception class provides a default Infinity Exception object that can be
used with the Infinity Status codes.

Example use:


```php
use Infinity\Common\Exception\Exception;

try
{
    throw new Exception( "There has been a problem" );
}
catch( Exception $e )
{
    var_dump($e);
}
```
