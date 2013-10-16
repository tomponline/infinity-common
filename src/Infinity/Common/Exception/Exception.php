<?php
namespace Infinity\Common\Exception;

/**
 * This method provides the default Exception type for all Infinity software.
 * @author Thomas Parrott <thomas.parrott@infinitycloud.com>
 * @package infinity-common
 */
class Exception extends \Exception
{
    /**
     * How many times to attempt the request that triggered this
     * exception before giving up.
     * @var int
     */
    protected $_retryLimit;

    /**
     * How long to wait (seconds) before retrying the request that
     * triggered this exception.
     * @var int
     */
    protected $_retryDelay;


    /**
     * Constructor.
     * @param string $message The internal exception message
     * @param int $code The status code from Icc_Status.
     * @param Exception $previous The previous exception
     */
    public function __construct(
        $message, $code = NULL, Exception $previous = NULL )
    {
        if( NULL === $code )
        {
            $code = Icc_Status::ERROR; //Default to error code for exceptions.
        }

        $this->_retryLimit  = NULL;
        $this->_retryDelay  = NULL;

        parent::__construct( $message, $code, $previous );
    }

    /**
     * Sets the number of times to attempt the request that
     * triggered this exception before giving up.
     * @param int $retryLimit
     */
    public function setRetryLimit( $retryLimit )
    {
        $opts = array( 'options' => array( 'min_range' => 0 ) );
        if ( FALSE !== filter_var( $retryLimit, FILTER_VALIDATE_INT, $opts ) )
        {
            $this->_retryLimit = $retryLimit;
        }
    }

    /**
     * Returns the number of times to attempt the request that
     * triggered this exception before giving up.
     * @return int Retry limit or NULL if not defined.
     */
    public function getRetryLimit()
    {
        return $this->_retryLimit;
    }

    /**
     * Sets the amount of time (seconds) to wait before retrying
     * the request that triggered this exception.
     * @param int $retryDelay
     */
    public function setRetryDelay( $retryDelay )
    {
        $opts = array( 'options' => array( 'min_range' => 0 ) );
        if ( FALSE !== filter_var( $retryDelay, FILTER_VALIDATE_INT, $opts ) )
        {
            $this->_retryDelay = $retryDelay;
        }
    }

    /**
     * Returns the amount of time (seconds) to wait before retrying
     * the request that triggered this exception.
     * @return int Retry delay or NULL if not defined.
     */
    public function getRetryDelay()
    {
        return $this->_retryDelay;
    }
}
