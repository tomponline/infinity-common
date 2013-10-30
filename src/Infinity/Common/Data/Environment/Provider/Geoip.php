<?php
namespace Infinity\Common\Data\Environment\Provider;

use Infinity\Common\Data\Environment;
use Infinity\Common\Db\Geoip as GeoipDb;

/**
 * This class is a data provider for Geo IP lookups.
 * @author Thomas Parrott <thomas.parrott@infinitycloud.com>
 * @package infinity-common
 */
class Geoip extends ProviderBase implements ProviderInterface
{
    public function register( Environment $env )
    {
        $env->registerPrefixHandler(
            'geoip_client_country', array($this, 'getClientIpCountry'), TRUE );
    }

    /**
     * This method performs a GeoIP country lookup for the client's IP.
     * @return mixed Country code on success, NULL when not found.
     */
    public function getClientIpCountry()
    {
        $geoipDb = new GeoipDb();

        if( isset( $_SERVER[ 'REMOTE_ADDR' ] ) )
        {
            return $geoipDb->getCountryCode( $_SERVER[ 'REMOTE_ADDR' ] );
        }
    }
}
