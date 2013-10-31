<?php
namespace Infinity\Common\Data\Environment\Provider;

use Infinity\Common\Data\Environment;
use Infinity\Common\Db\Geoip as GeoipDb;

/**
 * This class is a data provider for IP information.
 * @author Thomas Parrott <thomas.parrott@infinitycloud.com>
 * @package infinity-common
 */
class Ip implements ProviderInterface
{
    public function register( Environment $env )
    {
        $env->registerPrefixHandler(
            'ip_address', array( $this, 'getClientIp') );
    }

    /**
     * This method returns the client's IP address.
     * @return mixed IP address on success, NULL when not found.
     */
    public function getClientIp()
    {
        if( isset( $_SERVER[ 'REMOTE_ADDR' ] ) )
        {
            return $_SERVER[ 'REMOTE_ADDR' ];
        }
    }
}
