<?php
namespace Infinity\Common\ClassLoader;

/**
 * This class is based on the PSR-0 autoloader reference implementation.
 * https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
 * Use this to autoload classes if you are not using the Composer autoloader.
 * @author Thomas Parrott <thomas.parrott@infinitycloud.com>
 * @package infinity-common
 */
class Autoloader
{
    /**
     * This method is used to autoload a class based on its name.
     * Backslashes and underscores in the name are converted to
     * directory separators.
     * @param string $className Class name to load.
     * @return NULL
     */
    public function autoload($className)
    {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';

        if ($lastNsPos = strrpos($className, '\\'))
        {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace)
                . DIRECTORY_SEPARATOR;
        }

        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        require $fileName;
    }

    /**
     * This method registers the autoload method with PHP.
     * @return NULL
     */
    public function register()
    {
        spl_autoload_register(array($this,'autoload'), TRUE);
    }
}
