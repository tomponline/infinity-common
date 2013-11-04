<?php
namespace Infinity\Common\Data\Environment\Provider;

use Infinity\Common\Data\Environment;
use Infinity\Common\Http\Request;

/**
 * This class is a data provider for Cookie information.
 * @author Thomas Parrott <thomas.parrott@infinitycloud.com>
 * @package infinity-common
 */
class Cookie implements ProviderInterface
{
    private $_request;

    public function __construct()
    {
        $this->_request = new Request();
    }

    public function register( Environment $env )
    {
        $env->registerPrefixHandler(
            'cookie', array( $this, 'getCookieValue') );
    }

    /**
     * This method returns the value of a named cookie.
     * @param string $key Which cookie value to get.
     * @return mixed Cookie value on success, NULL when not found.
     */
    public function getCookieValue( $key )
    {
        return $this->_request->getCookie( $key );
    }
}
