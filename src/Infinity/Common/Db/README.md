GeoIP
=====================

The GeoIP class provides helper methods for accessing the Maxmind GeoIP DB.

Example use:

```php
use Infinity\Common\Db\Geoip;

$ip = '8.8.8.8';

$geoip = new Geoip();
var_dump( $geoip->getCountryCode( $ip ) );
var_dump( $geoip->getCityInfo( $ip ) );
```

