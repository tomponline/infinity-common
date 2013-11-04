<?php
namespace Infinity\Common\Http;

/**
 * This class contains contains the business logic to detect web site landings.
 * @author Thomas Parrott <thomas.parrott@infinitycloud.com>
 * @package infinity-common
 */
class LandHelper
{
    private $_request;

    public function __construct()
    {
        $this->_request = new Request;
    }

    /**
     * This method should be run on every page view of your app.
     * It provides land detection and URL parameter persistence functionality.
     * @return NULL
     */
    public function run()
    {

    }

    /**
     * This method sets the persisted URL parameters config options.
     * This provides the ability to detect certain URL parameters in the
     * query string and then persists them to a cookie for a configurable
     * period of time.
     * @param array $config Persisted params configuration.
     * @return NULL
     */
    public function setPersistParams( array $config )
    {

    }

    /**
     * This method returns whether the current page view should be considered
     * a landing from an external source.
     * @return boolean TRUE on land, FALSE on page view.
     */
    public function isLand()
    {
        $ret            = TRUE;
        $currentHost    = $this->_request->getHost();
        $referrerHost   = $this->getUrlHost( $this->_request->getReferrer() );

        if( $currentHost === $referrerHost )
        {
            $ret = FALSE;
        }

        return $ret;
    }

    /**
     * This method retrieves the host domain name part of a URL.
     * @param string $url URL to get host domain part from.
     * @return mixed Returns string of host domain, or NULL on parse error.
     */
    public function getUrlHost( $url )
    {
        $ret = NULL;

        if( $url && $parts = parse_url( $url ) )
        {
            if( isset( $parts[ 'host' ] ) )
            {
                $ret = strtolower( $parts[ 'host' ] );
            }
        }

        return $ret;
    }
}
