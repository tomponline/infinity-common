<?php
namespace Infinity\Common\Data\Environment\Provider;

use Infinity\Common\Data\Environment;
use Infinity\Common\Http\Request;

/**
 * This class is a data provider for URL information.
 * @author Thomas Parrott <thomas.parrott@infinitycloud.com>
 * @package infinity-common
 */
class Url implements ProviderInterface
{
    private $_request;

    public function __construct()
    {
        $this->_request = new Request();
    }

    public function register( Environment $env )
    {
        $env->registerPrefixHandler(
            'url_param', array( $this, 'getUrlParam') );
        $env->registerPrefixHandler(
            'url_domain', array( $this, 'getUrlDomain') );
    }

    /**
     * This method returns the value of a parameter in the URL query string.
     * @param string $param Parameter to get from URL query string.
     * @return mixed Parameter value on success, NULL when not found.
     */
    public function getUrlParam( $param )
    {
        return $this->_request->getUriParameter( $param );
    }

    /**
     * This method returns the value of the host in the URL.
     * @return mixed Host domain value on success, NULL when not found.
     */
    public function getUrlDomain()
    {
        return $this->_request->getHost();
    }
}
