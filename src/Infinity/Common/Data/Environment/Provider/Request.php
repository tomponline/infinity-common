<?php
namespace Infinity\Common\Data\Environment\Provider;

use Infinity\Common\Data\Environment;
use Infinity\Common\Db\Geoip as GeoipDb;

class Request extends ProviderBase implements ProviderInterface
{
    public function register( Environment $env )
    {
        $env->registerPrefixHandler(
            'request_ip', array( $this, 'getIp') );
    }

    public function getIp()
    {
        if( isset( $_SERVER[ 'REMOTE_ADDR' ] ) )
        {
            return $_SERVER[ 'REMOTE_ADDR' ];
        }
    }
}
