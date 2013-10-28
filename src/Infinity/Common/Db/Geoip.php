<?php
namespace Infinity\Common\Db;

use Infinity\Common\Exception\Exception;

/**
 * This class provides helper methods for accessing the Maxmind GeoIP database.
 * @author Thomas Parrott <thomas.parrott@infinitycloud.com>
 * @package infinity-common
 */
class Geoip
{
    /**
     * This method performs a country code lookup for an IP.
     * It uses the Maxmind GEO IP database.
     * @param string IP address to lookup.
     * @return mixed Country code if IP is found in DB, FALSE if not.
     * @throws Exception
     */
    public function getCountryCode( $ip )
    {
        $ret = FALSE;

        if( geoip_db_avail( GEOIP_COUNTRY_EDITION ) )
        {
            //Suppress errors as otherwise we get notices when IP not found.
            $ret = @geoip_country_code_by_name( $ip );
        }
        else
        {
            throw new Exception( __FUNCTION__ . ': MaxMind DB not found '
                . geoip_db_filename( GEOIP_COUNTRY_EDITION ) );
        }

        return $ret;
    }

    /**
     * This method performs a city lookup for an IP.
     * It uses the Maxmind GEO IP City database.
     * @param string IP address to lookup.
     * @return mixed StdClass containing various fields, FALSE if not.
     * @throws Exception
     */
    public function getCityInfo( $ip )
    {
        $ret = FALSE;

        if( geoip_db_avail( GEOIP_CITY_EDITION_REV1 ) )
        {
            //Suppress errors as otherwise we get notices when IP not found.
            if( $row = @geoip_record_by_name( $ip ) )
            {
                //Convert array returned to camel case StdClass.
                $ret = new \StdClass;
                $ret->continentCode = $row[ 'continent_code' ];
                $ret->countryCode   = $row[ 'country_code' ];
                $ret->countryCode3  = $row[ 'country_code3' ];
                $ret->countryName   = $row[ 'country_name' ];
                $ret->region        = $row[ 'region' ];
                $ret->city          = $row[ 'city' ];
                $ret->postalCode    = $row[ 'postal_code' ];
                $ret->latitude      = $row[ 'latitude' ];
                $ret->longitude     = $row[ 'longitude' ];
                $ret->dmaCode       = $row[ 'dma_code' ];
                $ret->areaCode      = $row[ 'area_code' ];
            }
        }
        else
        {
            throw new Exception( __FUNCTION__ . ': MaxMind DB not found '
                . geoip_db_filename( GEOIP_CITY_EDITION_REV1 ) );
        }

        return $ret;
    }
}
