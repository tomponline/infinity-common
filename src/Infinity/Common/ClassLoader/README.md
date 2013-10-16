ClassLoader
=====================

The Autoloader class provides the ability to automatically load class files
when they are needed.

Example use:

```php
    require __DIR__.'/src/Infinity/Common/ClassLoader/Autoloader.php';

    use Infinity\Common\ClassLoader\Autoloader;

    $autoload = new Autoloader();
    $autoload->register();
```
