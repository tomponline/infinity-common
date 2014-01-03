<?php
namespace Infinity\Common\Data\Environment\Provider;

use Infinity\Common\Data\Environment;
use Infinity\Common\Http\Request;

/**
 * This class is a data provider for visit count information.
 * @author George Arscott <george.arscott@infinitycloud.com>
 * @package infinity-common
 */
class VisitCount implements ProviderInterface
{
    const COOKIE_NAME = 'ic_visits';
    const COOKIE_EXPIRES = 315360000; //10 years

    private $_request;
    private $_cookieExpires; //Dynamic value to add time()

    public function __construct()
    {
        $this->_request = new Request();
        $this->_cookieExpires = time() + self::COOKIE_EXPIRES;
    }

    public function register( Environment $env )
    {
        $env->registerPrefixHandler(
            'visit_count', array( $this, 'getVisitCountValue') );
    }

    /**
     * This method returns the value of the visit count cookie.
     * @return int Cookie value.
     */
    public function getVisitCountValue()
    {
        $visitCount = ( isset( $_COOKIE[ self::COOKIE_NAME ] ) ) ? $_COOKIE[ self::COOKIE_NAME ] : 0;

        if( ! headers_sent() )
        {
            $referrer = parse_url( $this->_request->getReferrer() );

            //Strip www. from host names
            $referrerHost   = ltrim( $referrer['host'], 'www.' );
            $requestHost    = ltrim( $this->_request->getHost(), 'www.' );

            if( $referrerHost != $requestHost )
            {
                $visitCount ++;
                setcookie( self::COOKIE_NAME, $visitCount, $this->_cookieExpires,
                    '/', $this->_request->getHost() );
            }
        }

        return $visitCount;
    }
}
