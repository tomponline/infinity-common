<?php
namespace Infinity\Common\Http;

/**
 * This class provides the ability to setup HTTP requests and process their results
 * @author Thomas Parrott <thomas.parrot@infinitycloud.com>
 * @package infinity-common
 */
class Client
{
    private $_connectTimeout;
    private $_readTimeout;
    private $_requestTimeout;
    private $_metaData;
    private $_fh;
    private $_filename;
    private $_fhStats;
    private $_error;
    private $_maxMem;
    private $_ch;
    private $_curlErrno;
    private $_checksEnabled;
    private $_proxy;
    private $_proxyPort;
    private $_followRedirects;

    // Per-request settings.
    private $_requestUri;
    private $_requestMethod;
    private $_postData;
    private $_credentials;
    private $_requestHeaders;

    const DEFAULT_USER_AGENT    = 'Infinity HTTP Client';

    /**
     * This method is the constructor and is used to
     * initialise variables to a known state on instantiation.
     * @return NULL
     */
    public function __construct()
    {
        $this->_connectTimeout      = 5;
        $this->_readTimeout         = 120;
        $this->_requestTimeout      = 300;
        $this->_fh                  = FALSE;
        $this->_metaData            = array();
        $this->_fhStats             = FALSE;
        $this->_filename            = FALSE;
        $this->_error               = FALSE;
        $this->_maxMem              = 1048576; //1MB memory limit.
        $this->_ch                  = NULL;
        $this->_curlErrno           = NULL;
        $this->_checksEnabled       = TRUE;
        $this->_proxy               = FALSE;
        $this->_proxyPort           = FALSE;
        $this->_followRedirects     = TRUE;

        $this->resetRequest();
    }

    /**
     * This method is called when the object falls out of scope.
     * Tries to ensure that files get cleaned up.
     * @return NULL
     */
    function __destruct()
    {
        if( isset( $this->_fh ) && is_resource( $this->_fh ) )
        {
            fclose( $this->_fh );
        }
    }

    /**
     * This method sets the read timeout of a request.
     * @param integer The number of seconds that the timeout should be set to.
     * @return NULL
     */
    public function setReadTimeout( $data )
    {
        $this->_readTimeout = $data;
    }

    /**
     * This method sets the total timeout of the request.
     * @param integer The number of seconds that the timeout should be set to.
     * @return NULL
     */
    public function setRequestTimeout( $data )
    {
        $this->_requestTimeout = $data;
    }

    /**
     * This method sets the connect timeout.
     * @param integer The number of seconds that the timeout should be set to.
     * @return NULL
     */
    public function setConnectTimeout( $data )
    {
        $this->_connectTimeout = $data;
    }

    /**
     * This method sets the request URI.
     * @param string $data Contains the request URI
     * @return null
     */
    public function setRequestUri( $data )
    {
        $this->_requestUri = $data;
    }

    /**
     * This method enables/disables sanity checks on the response data.
     * @param boolean TRUE to enable, FALSE to disable checks.
     * @return NULL
     */
    public function setChecksMode( $mode )
    {
        $this->_checksEnabled = $mode;
    }

    /**
     * This method enables/disables following of redirects.
     * @param boolean TRUE to enable, FALSE to disable checks.
     * @return NULL
     */
    public function setFollowRedirects( $mode )
    {
        $this->_followRedirects = $mode;
    }

    /**
     * Returns the mode for sanity checks on the response data.
     * @return boolean TRUE when enabled, FALSE otherwise.
     */
    public function getChecksMode()
    {
        return $this->_checksEnabled;
    }

    /**
     * This method sets a request header.
     * @param string $index Contains the index of the header
     * @param string $data Contains the header data
     * @return NULL
     */
    public function setRequestHeader( $index, $data )
    {
        $this->_requestHeaders[ $index ] = str_replace( "\n", '', $data );
    }

    /**
     * Resets any previously set request headers.
     * @return NULL
     */
    public function resetRequestHeaders()
    {
        //Set default request headers
        $this->_requestHeaders = array();
        $this->_requestHeaders['User-Agent'] = self::DEFAULT_USER_AGENT;
    }

    /**
     * This method sets the request HTTP Auth credentials.
     * @param string $user Contains the username of the HTTP auth
     * @param string $pass Contains the password of the HTTP auth
     * @return NULL
     */
    public function setRequestCredentials( $user, $pass )
    {
        $this->_credentials = $user . ':' . $pass;
    }

    /**
     * Clears out any previously set request credentials.
     * @return NULL
     */
    public function resetRequestCredentials()
    {
        $this->_credentials = FALSE;
    }

    /**
     * This method sets the request method.
     * @param string $data Sets the request method (GET, POST, PUT, DELETE)
     * @return NULL
     */
    public function setRequestMethod( $data )
    {
        $this->_requestMethod = strtoupper( $data );
    }

    /**
     * This method sets the request body data.
     * @todo Add RFC argument to http_build_query when available in PHP 5.4.
     * @param mixed Sets POST data, can be either string or array.
     * @return NULL
     */
    public function setRequestData( $data )
    {
        if ( is_array( $data ) )
        {
            $this->_postData = http_build_query( $data );
        }
        else
        {
            $this->_postData = $data;
        }
    }

    /**
     * Resets any per-request settings ready for use with another URI.
     * Clears the URI, request data, credentials and headers.
     * Does not change timeout behaviour, proxy settings, etc.
     * @return NULL
     */
    public function resetRequest()
    {
        $this->setRequestUri( FALSE );
        $this->setRequestMethod( 'GET' );
        $this->setRequestData( FALSE );
        $this->resetRequestCredentials();
        $this->resetRequestHeaders();
    }

    /**
     * Sets the maximum memory buffer for downloaded data.
     * @param int $data Sets the memory limit in bytes.
     * @return NULL
     */
    public function setMemoryLimit( $bytes )
    {
        $this->_maxMem = intval( $bytes );
    }

    /**
     * Sets the proxy destination.
     * @param string $proxy
     * @return NULL
     */
    public function setProxy( $proxy )
    {
        $this->_proxy = $proxy;
    }

    /**
     * Gets the proxy destination
     * @return string
     */
    public function getProxy()
    {
        return $this->_proxy;
    }

    /**
     * Sets the proxy port.
     * @param string $proxyPort
     * @return NULL
     */
    public function setProxyPort( $proxyPort )
    {
        $this->_proxyPort = $proxyPort;
    }

    /**
     * Gets the proxy port
     * @return string
     */
    public function getProxyPort()
    {
        return $this->_proxyPort;
    }

    /**
     * This method retrieves a header after a request has been made.
     * @param string $index The index of the response header you want
     * @return mixed String of header if it exists in response, or FALSE.
     */
    public function getResponseHeader( $index )
    {
        $ret = false;

        if( is_string( $index ) )
        {
            $index = strtolower( $index );

            //Convert Curl meta data into HTTP header equivalent.
            if( $index == 'content-type' &&
                isset( $this->_metaData[ 'content_type' ] ) )
            {
                $ret = $this->_metaData[ 'content_type' ];
            }
             //Convert Curl meta data into HTTP header equivalent.
            elseif( $index == 'content-length' &&
                    isset( $this->_metaData[ 'download_content_length' ] ) )
            {
                $ret = intval( $this->_metaData[ 'download_content_length' ] );
            }
            //Provide access to other headers stored by callback function.
            elseif( isset( $this->_metaData[ $index ] ) )
            {
                $ret = $this->_metaData[ $index ];
            }
        }

        return $ret;
    }

    /**
     * This method returns the response HTTP code.
     * @return mixed Returns numeric HTTP code on success or false on error.
     */
    public function getResponseCode()
    {
        $ret = FALSE;

        if( isset( $this->_metaData[ 'http_code' ] ) )
        {
            $ret = $this->_metaData[ 'http_code' ];
        }

        return $ret;
    }

    /**
     * This method seeks to the begining of the file handle where the
     * response is stored and returns it.
     * @return mixed Filehandle on success, false on error.
     */
    public function getResponseHandle()
    {
        $ret = FALSE;

        if( isset( $this->_fh ) && $this->_fh )
        {
            rewind( $this->_fh );
            $ret = $this->_fh;
        }

        return $ret;
    }

    /**
     * This method gets the response temporary file size.
     * @return mixed Integer representing bytes of temporary file, or FALSE.
     */
    public function getResponseSize()
    {
        return ( isset( $this->_fhStats[ 'size' ] )
            ? $this->_fhStats[ 'size' ] : FALSE );
    }

    /**
     * This method returns the number of bytes actually downloaded by cURL. For
     * GZIP content this will be the number of bytes downloaded before
     * decompression.
     * @return mixed Int representing the number of bytes downloaded, or FALSE
     */
    public function getDownloadedSize()
    {
        return ( isset( $this->_metaData[ 'size_download' ] )
            ? $this->_metaData[ 'size_download' ] : FALSE );
    }

    /**
     * This method returns the result of the HTTP request.
     * @return boolean True on success, false on error.
     */
    public function getResponseResult()
    {
        return ( $this->_curlErrno === 0 ) ? TRUE : FALSE;
    }

    /**
     * This method compares the size of the downloaded file compared to
     * the content-length header.
     * @return mixed True if content-length and file size match or if
     * content-length is 0. False if they content-length is greater
     * than 0 and file size is different.
     */
    public function isResponseComplete()
    {
        $ret = FALSE;

        //Check curl completed OK
        if( TRUE === $this->getResponseResult() )
        {
            $contentLength  = $this->getResponseHeader( 'content-length' );
            $responseSize   = $this->getDownloadedSize();

            //Test whether there is a postive content-length.
            if( $contentLength > 0 )
            {
                //We got the full file according to the content-length header.
                if( $contentLength == $responseSize )
                {
                    $ret = true;
                }
            }
            else
            {
                //Nno way of comparing whether the file is complete or not.
                $ret = true;
            }
        }

        return $ret;
    }

    /**
     * This method retrieves the error string from the last request made.
     * @return mixed String containing error or false if no error.
     */
    public function getResponseError()
    {
        return $this->getError();
    }

    /**
     * This method retrieves the error string from the last request made.
     * @return mixed String containing error or false if no error.
     */
    public function getError()
    {
        return $this->_error;
    }

    /**
     * This method prepares a curl compatible array of headers.
     * @return array Returns an array of headers.
     */
    protected function _getCurlHeaders()
    {
        $curlHeaders = array();
        foreach( $this->_requestHeaders as $header => $value )
        {
            $curlHeaders[] = $header . ': ' . $value;
        }
        return $curlHeaders;
    }

    /**
     * This method creates and configures Curl ready to make the request.
     * @return NULL
     */
    public function setupCurl()
    {
        //Open a temporary file for storing the response in.
        $this->_error = FALSE; //Reset error string.
        $this->_fh = fopen( 'php://temp/maxmemory:' . $this->_maxMem , 'w+' );

        //Setup options
        $this->_ch = curl_init( $this->_requestUri );
        curl_setopt( $this->_ch, CURLOPT_FILE, $this->_fh );
        curl_setopt( $this->_ch, CURLOPT_CUSTOMREQUEST, $this->_requestMethod );
        curl_setopt( $this->_ch, CURLOPT_TIMEOUT, $this->_requestTimeout );
        curl_setopt(
            $this->_ch, CURLOPT_CONNECTTIMEOUT, $this->_connectTimeout );

        //If proxy has been defined, pass in params
        if ( $this->_proxy )
        {
            curl_setopt( $this->_ch, CURLOPT_PROXY, $this->_proxy );
        }

        //If proxy port has been defined, pass in params
        if ( $this->_proxyPort )
        {
            curl_setopt( $this->_ch, CURLOPT_PROXYPORT, $this->_proxyPort );
        }

        //Accept all encoding types
        curl_setopt( $this->_ch, CURLOPT_ENCODING, '' );

        //Set low speed limit to 0 bytes/sec and read timeout.
        curl_setopt( $this->_ch, CURLOPT_LOW_SPEED_LIMIT, 1 );
        curl_setopt( $this->_ch, CURLOPT_LOW_SPEED_TIME, $this->_readTimeout );

        //We use 1.0 to prevent curl sending Expect headers on large POSTS
        curl_setopt( $this->_ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0 );

        //Set follow HTTP redirection option.
        curl_setopt(
            $this->_ch, CURLOPT_FOLLOWLOCATION, $this->_followRedirects );

        //Register callback function for response headers.
        curl_setopt( $this->_ch, CURLOPT_HEADERFUNCTION,
            array( $this , '_responseHeaderCallback' ) );

        //If credentials specified, set plain HTTP auth
        if( $this->_credentials )
        {
            curl_setopt( $this->_ch, CURLOPT_USERPWD, $this->_credentials );
        }

        //If POST data has been supplied, set POST fields
        if( $this->_postData )
        {
            curl_setopt( $this->_ch, CURLOPT_POSTFIELDS, $this->_postData );
        }

        //Set headers for request
        curl_setopt( $this->_ch, CURLOPT_HTTPHEADER, $this->_getCurlHeaders() );
    }

    /**
     * This method cleans up a Curl and records the results of a request.
     * @return NULL
     * @param int The error number from curl_errno() after making the request.
     */
    public function cleanupCurl( $errno )
    {
        $this->_metaData    =
            array_merge( $this->_metaData, curl_getinfo( $this->_ch ) );
        $this->_curlErrno   = $errno;

        //Check  response code for error.
        if( $this->_curlErrno !== CURLE_OK )
        {
            $this->_error =  __CLASS__
                . ': Request failed: ' . curl_error( $this->_ch );
        }

        //Cleanup
        curl_close( $this->_ch );
        fflush( $this->_fh );
        $this->_fhStats = fstat( $this->_fh );
    }

    /**
     * This method performs the actual HTTP request.
     * @return boolean True on success, false on failure.
     */
    public function sendRequest()
    {
        $this->setupCurl();
        $ret = curl_exec( $this->_ch );
        $this->cleanupCurl( curl_errno( $this->_ch ) );
        return $ret;
    }

    /**
     * This method returns the Curl handle used internally.
     * @return mixed Returns Curl handle if defined, NULL otherwise.
     */
    public function getCurlHandle()
    {
        return $this->_ch;
    }

    /**
     * This method returns the request URI.
     * @return mixed Returns string URI if defined, FALSE if not.
     */
    public function getRequestUri()
    {
        return $this->_requestUri;
    }

    /**
     * Processes the response headers from CURL by way of a callback.
     * @return int The number of bytes written.
     * @param handle The CURL handle.
     * @param string The header line.
     */
    private function _responseHeaderCallback( $ch, $lineStr )
    {
        $parts = explode( ':', $lineStr, 2 );

        if( count( $parts ) > 1 )
        {
            $header = strtolower( trim( $parts[0] ) );
            $data   = trim( $parts[1] );
            $this->_metaData[ $header ] = $data;
        }

        return strlen( $lineStr );
    }
}
