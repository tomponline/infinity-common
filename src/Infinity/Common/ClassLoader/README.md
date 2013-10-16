ClassLoader
=====================

The Autoloader class provides the ability to automatically load class files
when they are needed.

Example use:

```php
set_include_path(
        get_include_path()
        . PATH_SEPARATOR . __DIR__ . '/src/'
);

require 'Infinity/Common/ClassLoader/Autoloader.php';

use Infinity\Common\ClassLoader\Autoloader;

$autoload = new Autoloader();
$autoload->register();
```
