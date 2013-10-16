<?php
namespace Infinity\Common\Status;

/**
 * This class contains constants for status codes used in Infinity.
 * They are modelled on HTTP status codes as these are well defined and varied.
 * @author Thomas Parrott <thomas.parrott@infinitycloud.com>
 * @package infinity-common
 */
class Status
{
    //Status unknown, this is probably an error so treat it as such.
    const UNKNOWN       = 0;

    // Request is being processed but no response is available yet.
    const PROCESSING    = 102;

    //Resource is active or job completed OK.
    const OK            = 200;

    //Resource created OK.
    const CREATED       = 201;

    //Request accepted for offline/asynchronous processing.
    const ACCEPTED      = 202;

    //Resource is active or job completed OK but no content returned.
    const NO_CONTENT    = 204;

    //Bad request, request or job is malformed and cannot be used, do not retry.
    const BAD_REQUEST   = 400;

    //You're not allowed to make this request or run this job.
    const UNAUTHORISED  = 401;

    //Resource specified in job or request is not found.
    const NOT_FOUND     = 404;

    //Request conflicts with existing data.
    const CONFLICT      = 409;

    //Resource is gone and will not be available again ever.
    const GONE          = 410;

    //Resource is forbidden, it has been disabled due to client error.
    const FORBIDDEN     = 403;

    //Internal error, there is an unknown problem, retry later.
    const ERROR         = 500;

    //Resource unavailable, it has been disabled for maintenance, retry later.
    const UNAVAILABLE   = 503;

    private $_statusLabels;

    public function __construct()
    {
        $this->_statusLabels = array(
            self::UNKNOWN       => 'Unknown',
            self::OK            => 'OK',
            self::CREATED       => 'Created',
            self::ACCEPTED      => 'Accepted',
            self::NO_CONTENT    => 'No Content',
            self::BAD_REQUEST   => 'Bad Request',
            self::UNAUTHORISED  => 'Unauthorised',
            self::NOT_FOUND     => 'Not Found',
            self::CONFLICT      => 'Conflict',
            self::GONE          => 'Gone',
            self::FORBIDDEN     => 'Forbidden',
            self::ERROR         => 'Error',
            self::UNAVAILABLE   => 'Unavailable',
        );
    }

    /**
     * This method provides a translation between status code and label.
     * @param string Status code.
     * @return mixed String label on recognised status code, NULL if not.
     */
    public function getStatusLabel( $statusCode )
    {
        if( isset( $this->_statusLabels[ $statusCode ] ) )
        {
            return $this->_statusLabels[ $statusCode ];
        }
    }
}
