<?php
namespace Infinity\Common\Data\Environment\Provider;

use Infinity\Common\Data\Environment;
use Infinity\Common\Db\Geoip as GeoipDb;

class Geoip extends ProviderBase implements ProviderInterface
{
    public function register( Environment $env )
    {
        $env->registerPrefixHandler(
            'geoip_country', array( $this, 'getCountry') );
    }

    public function getCountry()
    {
        $geoipDb = new GeoipDb();

        if( isset( $_SERVER[ 'REMOTE_ADDR' ] ) )
        {
            return $geoipDb->getCountryCode( $_SERVER[ 'REMOTE_ADDR' ] );
        }
    }
}
