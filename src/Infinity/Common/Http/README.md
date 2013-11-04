HTTP Request
=====================

The HTTP Request object allows you to access information about HTTP requests.

Example usage:

```php
use Infinity\Common\Http\Request;

$request = new Request();

var_dump($request->getBodyParameters());
var_dump($request->getRawBody());

```

Land Helper
=====================

The Land Helper provides a process for detecting whether a request is
a land or a page view (based on referrer domain) and provides the ability
to detect and store persisted land URL parameters.

Example usage:

```php
use Infinity\Common\Http\LandHelper;

$landHelper = new LandHelper();

$persistParams = array(
    'myTag' => 'session',   //Stores this in a cookie for the session.
    'tag2'  => 'P2D',       //Stores this in a cookie for 2 days
);

$landHelper->setPersistedLandParamRules( $persistPams );
$landHelper->run();

var_dump($landHelper->isLand()); //Returns TRUE or FALSE

```
