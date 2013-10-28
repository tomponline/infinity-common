<?php
namespace Infinity\Common\Data;

use Infinity\Common\Exception\Exception;
use Infinity\Common\Db\Geoip;

/**
 * This class contains provides an intelligent data container object that is
 * able to extract variables about the environment it is running in based on a
 * prefixed variable name. It interrogates the relevant data objects based on
 * the prefixed variable requested and caches the results internally.
 * @author Thomas Parrott <thomas.parrott@infinitycloud.com>
 * @package infinity-common
 */
class Environment
{
    private $_keyPrefixes;
    private $_cachedValues;

    public function __construct()
    {
        $this->_keyPrefixes     = array();
        $this->_cachedValues    = array();

        //Register global prefixes.
        $this->registerPrefixHandler( 'hostname',
            array( $this,  '_getHostname' ) );

        $this->registerPrefixHandler( 'geoip',
            array( $this,  '_getGeoIp' ), TRUE );
    }

    /**
     * This method provides the ability to get a variable based on a key.
     * @param string $key Key name to retrieve.
     * @throws Exception
     * @return mixed The value of the key if recognised, NULL if not.
     */
    public function get( $key )
    {
        foreach( $this->_keyPrefixes as $prefix => $conf )
        {
            //Check that $key matches a known prefix.
            if( 0 === strpos( $key, $prefix ) )
            {
                //Return value if already cached for this key.
                if( !empty( $conf[ 'cache' ] ) &&
                    isset( $this->_cachedValues[ $key ] ) )
                {
                    return $this->_cachedValues[ $key ];
                }
                //If not check there is a valid callback and execute it.
                elseif( !empty( $conf[ 'callback' ] ) &&
                        is_callable( $conf[ 'callback' ] ) )
                {
                    //Execute the callback, passing the key requested.
                    $ret = call_user_func( $conf[ 'callback' ], $key );

                    //Cache the return value if cache is enabled for prefix.
                    if( !empty( $conf[ 'cache' ] ) )
                    {
                        $this->_cachedValues[ $key ] = $ret;
                    }

                    //Return the value for the key back to the requester.
                    return $ret;
                }
                else
                {
                    throw new Exception(
                        'Invalid callback provided for prefix ' . $prefix );
                }
            }
        }
    }

    /**
     * This method allows a callback function to be registerd for a certain key
     * prefixe.
     * @param string $prefix Key prefix to register callback for.
     * @param callable $callback The callback to run when a key matches prefix.
     * @param boolean $cache Whether or not to cache the response for a key.
     * @return NULL
     */
    public function registerPrefixHandler(
        $prefix, callable $callback, $cache = FALSE )
    {
        $this->_keyPrefixes[ $prefix ] = array(
            'callback'  => $callback,
            'cache'     => $cache,
        );
    }

    private function _getHostname()
    {
        return gethostname();
    }

    private function _getGeoIp( $key )
    {
        $geoip = new Geoip();

        if( 'geoip_country' === $key )
        {
            if( isset( $_SERVER[ 'REMOTE_ADDR' ] ) )
            {
                return $geoip->getCountryCode( $_SERVER[ 'REMOTE_ADDR' ] );
            }
        }
    }
}
