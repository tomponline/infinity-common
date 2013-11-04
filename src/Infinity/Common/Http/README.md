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
