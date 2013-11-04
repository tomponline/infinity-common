<?php
namespace Infinity\Common\Http;

use Infinity\Common\Exception\Exception;

/**
 * This class contains contains the business logic to detect web site landings.
 * @author Thomas Parrott <thomas.parrott@infinitycloud.com>
 * @package infinity-common
 */
class LandHelper
{
    private $_request;
    private $_persistedParams;

    public function __construct()
    {
        $this->_request = new Request;
        $this->_persistedParams = array();
    }

    /**
     * This method should be run on every page view of your app.
     * It provides land detection and URL parameter persistence functionality.
     * @return NULL
     */
    public function run()
    {
        if( $this->isLand() )
        {
            $this->runPersistedLandParamDetection();
        }
    }

    /**
     * This method should be run on every land (the run() method does this)
     * It checks for persistend land parameters and if present stores them.
     * @return NULL
     */
    public function runPersistedLandParamDetection()
    {
        foreach( $this->_persistedParams as $param => $expire )
        {
            if( $val = $this->_request->getUriParameter( $param ) )
            {
                //Send a cookie in the response for next page view.
                setcookie( $param, $val, $expire );

                //Store the value in the cookie array for current page view.
                $_COOKIE[ $param ] = $val;
            }
        }
    }

    /**
     * This method sets the persisted URL parameters config options.
     * This provides the ability to detect certain URL parameters in the
     * query string of a land URL and then persists them to a cookie for
     * a configurable period of time.
     * @param array $configs Persisted params configuration.
     * @return NULL
     */
    public function setPersistedLandParamRules( array $configs )
    {
        //Convert each config to an expiry unix timestamp.
        foreach( $configs as $param => $intervalStr )
        {
            if( $intervalStr === 'session' )
            {
                //Expire at end of session.
                $this->_persistedParams[ $param ] = 0;
            }
            else
            {
                $di = new \DateInterval( $intervalStr );
                $dt = new \Datetime();
                $dt->add( $di );
                $expire = (int) $dt->format('U');
                $this->_persistedParams[ $param ] = $expire;
            }
        }
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
