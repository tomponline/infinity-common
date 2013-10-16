Json
=====================

The Parser class provides a helpful wrapper around the json_decode and
json_encode functions that detects parse errors and throws exceptions.

Example use:

Encoding:

```php
use Infinity\Common\Json;

$o = new StdClass;
$o->key = 'value';

$encoded = Json\Parser::encode( $o );
echo $encoded . "\n";
```

Decoding:

```php
$decoded = Json\Parser::decode( $encoded );
var_dump( $decoded );
```
