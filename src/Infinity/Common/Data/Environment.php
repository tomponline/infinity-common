<?php
namespace Infinity\Common\Data;

use Infinity\Common\Exception\Exception;
use Infinity\Common\Data\Environment\Provider\ProviderInterface;

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
                    //Strip prefix and delimiters from key.
                    $callbackKey = ltrim(
                        substr( $key, strlen( $prefix ) ),
                        '_-:. ~'
                    );

                    //Execute the callback with non-prefixed key if set.
                    if( '' !== $callbackKey )
                    {
                        $ret = call_user_func(
                            $conf[ 'callback' ], $callbackKey );
                    }
                    //Execure the callback without key if not set.
                    else
                    {
                        $ret = call_user_func( $conf[ 'callback' ] );
                    };

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

    /**
     * This method registers a provider object with the Enviroment object.
     * This will call the register() method on the supplied $provider passing
     * $this as an argument. It will allow the provider to register the bundled
     * variable prefixes it provides.
     * @param ProviderInterface $provider Provider object.
     * @return NULL
     */
    public function registerProvider( ProviderInterface $provider )
    {
        $provider->register( $this );
    }
}
