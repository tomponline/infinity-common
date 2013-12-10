<?php
namespace Infinity\Common\String;

/**
* This class contains an implementation of a basic string logic language.
* The following operands are supported:
* bool      = Tests whether a field is set and not empty.
* eq        = Tests whether a field value equals a fixed value (case sensitve).
* ne        = Tests whether a field value does not equal a fixed value (case sensitive).
* eqi       = Tests whether a field value equals a fixed value (case insensitve).
* nei       = Tests whether a field value does not equal a fixed value (case insensitive).
* gt        = Tests whether a field value is greater than a fixed value.
* lt        = Tests whether a field value is less than a fixed value.
* ge        = Tests whether a field value is greater or equal to a fixed value.
* le        = Tests whether a field value is less or equal to a fixed value.
* inc       = Tests whether a field value includes a fixed value (case sensitve).
* ninc      = Tests whether a field value not includes a fixed value (case sensitve).
* inci      = Tests whether a field value includes a fixed value (case insensitve).
* ninci     = Tests whether a field value not includes a fixed value (case insensitve).
* begins    = Tests whether a field value begins with a fixed value (case sensitive).
* beginsi   = Tests whether a field value begins with a fixed value (case insensitive).
* nbegins   = Tests whether a field value does not begin with a fixed value (case sensitive).
* nbeginsi  = Tests whether a field value does not begin with a fixed value (case insensitive).
* ends      = Tests whether a field value ends with a fixed value (case sensitive).
* endsi     = Tests whether a field value ends with a fixed value (case insensitive).
* re        = Tests whether a field value matches a regular expression.
* nre       = Tests whether a field value does not match a regular expression.
* in        = Tests whether a field value equals one of the comma delimited options (case sensitive).
* ini       = Tests whether a field value equals one of the comma delimited options (case insensitive).
* nin       = Tests whether a field value does not equal one of the comma delimited options (case sensitive).
* nini      = Tests whether a field value does not equal one of the comma delimited options (case insensitive).
* innet     = Tests whether a field value contains an IP in a CIDR network (e.g. 192.168.0.0/24).
* glob      = Tests whether a field value matches the supplied pattern using fnmatch.
* @author Thomas Parrott <thomas.parrott@infinity-tracking.com>
* @package icc
*/
class Logic
{
    private static $_matches;
    private static $_operands = array(
        'begins'   => 'Begins With',
        'beginsi'  => 'Begins With (case insensitive)',
        'nbegins'  => 'Does Not Begin With',
        'nbeginsi' => 'Does Not Begin With (case insensitive)',
        'bool'     => 'Boolean',
        'ends'     => 'Ends With',
        'endsi'    => 'Ends With (case insensitive)',
        'eq'       => 'Equals',
        'eqi'      => 'Equals (case insensitive)',
        'ne'       => 'Not Equals',
        'nei'      => 'Not Equals (case insensitive)',
        'gt'       => 'Greater Than',
        'ge'       => 'Greater Than or Equal To',
        'lt'       => 'Less Than',
        'le'       => 'Less Than or Equal To',
        'in'       => 'In',
        'ini'      => 'In (case insensitive)',
        'nin'      => 'Not In',
        'nini'     => 'Not In (case insensitive)',
        'inc'      => 'Includes',
        'inci'     => 'Includes (case insensitive)',
        'ninc'     => 'Not Includes',
        'ninci'    => 'Not Includes (case insensitive)',
        're'       => 'Matches Regex',
        'nre'      => 'Does Not Match Regex',
        'glob'     => 'Matches Pattern',
        'innet'    => 'In Network Range',
    );

    /**
    * This method compares two strings using one of the following operand
    * codes; bool, eq, eqi, ne, nei, lt, gt, ge, le, inc, inci, ninci,
    * begins, beginsi, nbegins, nbeginsi, ends, endsi, re and nre.
    * @param string Value A for comparison with Value B.
    * @param string Operand code.
    * @param string Value B.
    * @return boolean TRUE on match, FALSE on no match.
    */
    public static function compare( $valA, $operandCode, $valB )
    {
        self::$_matches = NULL; //Reset matches.

        //Boolean true - When a field is non-empty.
        if( $operandCode == 'bool' && $valB == 'true' )
        {
            if( isset( $valA ) && $valA != "" ) return TRUE;
        }
        //Boolean false - When a field missing or emtpy.
        elseif( $operandCode == 'bool' && $valB == 'false' )
        {
            if( !isset( $valA ) || $valA == "" ) return TRUE;
        }
        //Case sensitive equals.
        elseif( $operandCode == 'eq' )
        {
            if( $valB == $valA ) return TRUE;
        }
        //Case sensitive not equal.
        elseif( $operandCode == 'ne' )
        {
            if( $valB != $valA ) return TRUE;
        }
        //Case insensitive equals.
        elseif( $operandCode == 'eqi' )
        {
            if( strcasecmp( $valA, $valB ) === 0 ) return TRUE;
        }
        //Case insensitive not equal.
        elseif( $operandCode == 'nei' )
        {
            if( strcasecmp( $valA, $valB ) !== 0 ) return TRUE;
        }
        //Less than.
        elseif( $operandCode == 'lt' )
        {
            if( $valA < $valB ) return TRUE;
        }
        //Greater than.
        elseif( $operandCode == 'gt' )
        {
            if( $valA > $valB ) return TRUE;
        }
        //Greater than or equal to.
        elseif( $operandCode == 'ge' )
        {
            if( $valA >= $valB ) return TRUE;
        }
        //Less then or equal to.
        elseif( $operandCode == 'le' )
        {
            if( $valA <= $valB ) return TRUE;
        }
        //Case sensitive includes.
        elseif( $operandCode == 'inc' )
        {
            if( strpos( $valA, $valB ) !== FALSE ) return TRUE;
        }
        //Case sensitive does not include.
        elseif( $operandCode == 'ninc' )
        {
            if( strpos( $valA, $valB ) === FALSE ) return TRUE;
        }
        //Case insensitive includes.
        elseif( $operandCode == 'inci' )
        {
            if( stripos( $valA, $valB ) !== FALSE ) return TRUE;
        }
        //Case insensitive does not include.
        elseif( $operandCode == 'ninci' )
        {
            if( stripos( $valA, $valB ) === FALSE ) return TRUE;
        }
        //Case sensitive begins with.
        elseif( $operandCode == 'begins' )
        {
            $startStr = substr( $valA, 0, strlen( $valB ) );
            if( strcmp( $startStr, $valB ) === 0 ) return TRUE;
        }
        //Case insensitive begins with.
        elseif( $operandCode == 'beginsi' )
        {
            $startStr = substr( $valA, 0, strlen( $valB ) );
            if( strcasecmp ( $startStr, $valB ) === 0 ) return TRUE;
        }
        //Case sensitive does not begin with.
        elseif( $operandCode == 'nbegins' )
        {
            $startStr = substr( $valA, 0, strlen( $valB ) );
            if( strcmp( $startStr, $valB ) !== 0 ) return TRUE;
        }
        //Case insensitive does not begin with.
        elseif( $operandCode == 'nbeginsi' )
        {
            $startStr = substr( $valA, 0, strlen( $valB ) );
            if( strcasecmp ( $startStr, $valB ) !== 0 ) return TRUE;
        }
        //Case sensitive ends with.
        elseif( $operandCode == 'ends' )
        {
            $len    = strlen( $valB );
            $endStr = substr( $valA, ( 0 - $len ), $len );
            if( strcmp ( $endStr, $valB ) === 0 ) return TRUE;
        }
        //Case insensitive ends with.
        elseif( $operandCode == 'endsi' )
        {
            $len    = strlen( $valB );
            $endStr = substr( $valA, ( 0 - $len ), $len );
            if( strcasecmp ( $endStr, $valB ) === 0 ) return TRUE;
        }
        //Regular expression match.
        elseif( $operandCode == 're' )
        {
            if( preg_match( $valB, $valA, $matches ) )
            {
                self::$_matches = $matches;
                return TRUE;
            }
        }
        //Regular expression not match.
        elseif( $operandCode == 'nre' )
        {
            if( !preg_match( $valB, $valA, $matches ) )
            {
                return TRUE;
            }
        }
        //Multiple value case sensitive check.
        elseif( $operandCode == 'in' )
        {
            $parts = explode( ',', $valB );
            if( in_array( $valA, $parts ) )
            {
                return TRUE;
            }
        }
        //Multiple value case insensitive check.
        elseif( $operandCode == 'ini' )
        {
            $parts = explode( ',', strtolower( $valB ) );
            if( in_array( strtolower( $valA ), $parts ) )
            {
                return TRUE;
            }
        }
        //Multiple value case sensitive negative check.
        elseif( $operandCode == 'nin' )
        {
            $parts = explode( ',', $valB );
            if( !in_array( $valA, $parts ) )
            {
                return TRUE;
            }
        }
        //Multiple value case insensitive negative check.
        elseif( $operandCode == 'nini' )
        {
            $parts = explode( ',', strtolower( $valB ) );
            if( !in_array( strtolower( $valA ), $parts ) )
            {
                return TRUE;
            }
        }
        //Check for whether an IP is inside a CIDR network.
        elseif( $operandCode == 'innet' )
        {
            return self::_checkInNet( $valA, $valB );
        }
        //Check if value matches fnmatch glob pattern.
        elseif( $operandCode == 'glob' )
        {
            return fnmatch( $valB, $valA );
        }

        return FALSE; //No match or unrecognised operand.
    }

    /**
     * This method returns any matches found in the last check.
     * @return mixed Array of matched values or NULL.
     */
    public static function getMatches()
    {
        return self::$_matches;
    }

    /**
     * This method returns an array of all available operands.
     * @return array
     */
    public static function getOperands()
    {
        return array_keys( self::$_operands );
    }

    /**
     * This method returns an associative array of all valid operands
     * along with their friendly labels that can be displayed to the end
     * user
     * @return array
     */
    public static function getOperandsWithLabels()
    {
        return self::$_operands;
    }

    /**
     * This method checks whether an operand is valid.
     * @param string Operand to check.
     * @return boolean
     */
    public static function isValidOperand( $op )
    {
        $ret = FALSE;

        if( array_key_exists( $op, self::$_operands ) )
        {
            $ret = TRUE;
        }

        return $ret;
    }

    /**
     * This method checks whether an IP exists within a supplied CIDR network.
     * Inspired from comments on http://php.net/manual/en/ref.network.php
     * @param string IP address (e.g. 192.168.0.1)
     * @param string CIDR network (e.g. 192.168.0.0/24)
     * @return boolean TRUE if IP is within network, FALSE if not or invalid IP.
     * @throws Exception If invalid CIRD network supplied.
     */
    private static function _checkInNet( $ip, $cidr )
    {
        $ret = FALSE;

        //Only proceed if $ip is non-emtpy and valid.
        //Don't throw exception, as this method is used in string comparison,
        //and user input string may be emtpy or invalid (which we can't change).
        if( $ip && ( $ipLong = ip2long ( $ip ) ) !== FALSE )
        {
            $parts = explode ( '/', $cidr );

            if( count( $parts ) === 2 )
            {
                list( $net, $mask ) = $parts;
                $ip_net     = ip2long ( $net );
                $ip_mask    = ~ ( ( 1 << ( 32 - $mask ) ) - 1 );
                $ip_ip_net  = $ipLong & $ip_mask;
                $ret = ( $ip_ip_net == $ip_net );
            }
            else
            {
                //Throw exception if invalid configuration is supplied.
                throw new Exception( 'Invalid CIDR Network supplied' );
            }
        }

        return $ret;
    }
}
