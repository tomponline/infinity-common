<?php
namespace Infinity\Common\Http;

/**
 * This class contains provides the ability to parse information about an
 * HTTP request.
 * @author Thomas Parrott <thomas.parrott@infinitycloud.com>
 * @package infinity-common
 */
class Request
{
    const SEGMENT_LAST = -1;

    private $_segments;
    private $_noOfSegments;
    private $_uri;
    private $_path;
    private $_bodyParams;

    /**
     * This method reads information from the GET request
     * and initialises internal variables.
     * @return null
     */
    public function __construct()
    {
        $this->_segments    = array();
        $this->_uri         = '';
        $this->_path        = '';

        //Initialise this so on first use we can decode the body if required.
        $this->_bodyParams = NULL;

        //Store the request URI.
        if( isset( $_SERVER[ 'REQUEST_URI' ] ) )
        {
            $this->_uri = $_SERVER[ 'REQUEST_URI' ];
        }

        //Decode the path info in the HTTP request.
        if( isset( $_SERVER[ 'PATH_INFO' ] ) )
        {

            //Remove slashes at end and begining if present.
            //This ensures that the first segment is valid and not emtpy string.
            //It also ensures that route maps don't have to worry about
            //trailing slashes.
            $this->_path            = trim( $_SERVER[ 'PATH_INFO' ], '/' );
            $this->_segments        = explode( '/', $this->_path );
            $this->_noOfSegments    = count( $this->_segments );
        }
    }

    /**
     * This method retrieves a URI segment from the request environment.
     * @param string $index Which segment is being requested?
     * @return mixed Segment value if exists, NULL if not.
     */
    public function getSegment( $index )
    {
        $ret = NULL;

        //Using -1 as $index gets the last segment value.
        if( self::SEGMENT_LAST === $index )
        {
            $ret = $this->_segments[ $this->_noOfSegments - 1 ];
        }
        //Otherwise positive integers for $index gets the segment at position.
        elseif( isset( $this->_segments[ $index - 1 ] ) )
        {
            $ret = $this->_segments[ $index - 1 ];
        }

        return rawurldecode( $ret );
    }

    /**
     * Returns the number of segments
     * @return int The number of segments in the URI
     */
    public function getSegmentCount()
    {
        return $this->_noOfSegments;
    }

    /**
     * Return the request URI.
     * @return string Request URI.
     */
    public function getUri()
    {
        return $this->_uri;
    }

    /**
     * Return the request path.
     * @return string Request path.
     */
    public function getPath()
    {
        //Prefix forward slash to restore the one was removed when processing
        //segments.
        return '/' . $this->_path;
    }

    /**
     * Get the file extension of the requested URI
     * @return mixed Extension string or NULL if no extension.
     */
    public function getExtension()
    {
        $ret = NULL;

        //Get last segment value.
        if( $seg = $this->getSegment( self::SEGMENT_LAST ) )
        {
            $extension = explode( '.', $seg );
            $ret = ( isset( $extension[ 1 ] ) ? $extension[ 1 ] : NULL );
        }

        return $ret;
    }

    /**
     * This method retrieves an entry from the URI query string.
     * @param string $index Which URI query param is wanted
     * @return mixed Returns parameter value if present, NULL if not.
     */
    public function getUriParameter( $index )
    {
        $ret = NULL;

        if( isset( $_GET[ $index ] ) )
        {
            $ret = $_GET[ $index ];
        }

        return $ret;
    }

    /**
     * This method retrieves all entries from the URI query string.
     * @return array Returns array of URI parameters if present.
     */
    public function getUriParameters()
    {
        if( isset( $_GET ) && is_array( $_GET ) )
        {
            return $_GET;
        }
        else
        {
            return array();
        }
    }

    private function _decodeBodyData()
    {
        mb_parse_str( $this->getRawBody() , $this->_bodyParams );
    }

    /**
     * This method retrieves a parameter that is supplied in the body data.
     * @param string $index
     * @return string value or NULL if missing.
     */
    public function getBodyParameter( $index )
    {
        $ret = NULL;

        //Prefer reading the PHP _POST array first if there is a POST request.
        if( $this->getMethod() === 'POST' )
        {
            if( isset( $_POST[ $index ] ) )
            {
                $ret = $_POST[ $index ];
            }
        }
        //But for other types of request, attempt to decode the raw body data.
        else
        {
            if( !is_array( $this->_bodyParams ) )
            {
                $this->_decodeBodyData();
            }

            if ( isset( $this->_PUT[ $index ] ) )
            {
                $ret = $this->_PUT[ $index ];
            }
        }

        return $ret;
    }

    /**
     * This method retrieves all parameters supplied in the body data.
     * @return array All body parameters present, empty array if no data.
     */
    public function getBodyParameters()
    {
        $ret = array();

        //Prefer reading the PHP _POST array first if there is a POST request.
        if( $this->getMethod() === 'POST' )
        {
            $ret = $_POST;
        }
        //But for other types of request, attempt to decode the raw body data.
        else
        {
            if( !is_array( $this->_bodyParams ) )
            {
                $this->_decodeBodyData();
            }

            if( is_array( $this->_bodyParams ) )
            {
                $ret = $this->_bodyParams;
            }
        }

        return $ret;
    }

    /**
     * This method retrieves the raw body data if present.
     * @return mixed Returns raw body data as a string, or FALSE on failure.
     */
    public function getRawBody()
    {
        return file_get_contents( 'php://input' );
    }

    /**
     * This method retrieves the HTTP request method.
     * @return mixed Returns HTTP request method as a string,
     * or FALSE if not defined.
     */
    public function getMethod()
    {
        $ret = FALSE;

        if( isset( $_SERVER[ 'REQUEST_METHOD' ] ) )
        {
            $ret = strtoupper( $_SERVER[ 'REQUEST_METHOD' ] );
        }

        return $ret;
    }

    /**
     * This method retrieves the HTTP accept string.
     * @return mixed Returns HTTP accept as a string, or false if not defined.
     */
    public function getAccept()
    {
        $ret = FALSE;

        if( isset( $_SERVER[ 'HTTP_ACCEPT' ] ) )
        {
            $ret = $_SERVER[ 'HTTP_ACCEPT' ];
        }

        return $ret;
    }

    /**
     * This method returns the clients IP.
     * @return mixed string IP address, or NULL is missing.
     */
    public function getClientIp()
    {
        $ret = NULL;

        //Otherwise, just use the REMOTE_ADDR variable.
        if( isset( $_SERVER[ 'REMOTE_ADDR' ] ) )
        {
            $ret = $_SERVER[ 'REMOTE_ADDR' ];
        }

        return $ret;
    }

    /**
     * This method retrieves a cookie variable.
     * @param string $index The bit of the cookie you want
     * @return mixed Returns string of cookie variable if set, NULL if not.
     */
    public function getCookie( $index )
    {
        $ret = NULL;

        if( isset( $_COOKIE[ $index ] ) && $_COOKIE[ $index ] != '' )
        {
            $ret = $_COOKIE[ $index ];
        }

        return $ret;
    }

    /**
     * This method retrieves information about the clients user-agent.
     * @return mixed String of user-agent if supplied otherwise NULL.
     */
    public function getUserAgent()
    {
        $ret = NULL;

        if( isset( $_SERVER[ 'HTTP_USER_AGENT' ] ) &&
            $_SERVER[ 'HTTP_USER_AGENT' ] != '' )
        {
            $ret = $_SERVER[ 'HTTP_USER_AGENT' ];
        }

        return $ret;
    }

    /**
     * This method retrieves the HTTP Host header from the request.
     * @return mixed Returns HTTP Host header if defined,
     * otherwise returns virtual host ServerName if defined, otherwise NULL.
     */
    public function getHost()
    {
        $ret = NULL;

        if( isset( $_SERVER[ 'HTTP_HOST' ] ) )
        {
            $ret = $_SERVER[ 'HTTP_HOST' ];
        }
        elseif( isset( $_SERVER[ 'SERVER_NAME' ] ) )
        {
            $ret = $_SERVER[ 'SERVER_NAME' ];
        }

        return $ret;
    }

    /**
     * This method retrieves the HTTP Referrer.
     * @return mixed Returns HTTP referrer if present, FALSE otherwise.
     */
    public function getReferrer()
    {
        $ret = NULL;

        if( isset( $_SERVER[ 'HTTP_REFERER' ] ) )
        {
            $ret = $_SERVER[ 'HTTP_REFERER' ];
        }

        return $ret;
    }

    /**
     * This method gets the credentials password through HTTP auth.
     * @return array Returns an assocative array containing username
     * and password elements. The elements will be NULL if not present.
     */
    public function getCredentials()
    {
        $ret = array( 'username' => NULL, 'password' => NULL );

        if( isset( $_SERVER[ 'PHP_AUTH_USER' ] ) )
        {
            $ret[ 'username' ] = $_SERVER[ 'PHP_AUTH_USER' ] ;
        }

        if( isset( $_SERVER[ 'PHP_AUTH_PW' ] ) )
        {
            $ret[ 'password' ] = $_SERVER[ 'PHP_AUTH_PW' ] ;
        }

        return $ret;
    }
}
