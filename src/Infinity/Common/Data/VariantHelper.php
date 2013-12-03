<?php
namespace Infinity\Common\Data;

use Infinity\Common\Exception\Exception;
use Infinity\Common\String\Logic;

/**
 * This class contains provides the ability to make variant decisions based
 * on a configuration using data from the application's Environment.
 * @author George Arscott <george.arscott@infinitycloud.com>
 * @package infinity-common
 */
class VariantHelper
{
    private $_blockConfig;
    private $_environment;

    /**
     * Sets the block config
     * @param array $blockConfig the block config
     * @throws Exception
     * @return NULL
     */
    public function setConfig( array $blockConfig )
    {
        //Validate variants
        if( ! is_array( $blockConfig['variants'] ) )
        {
            throw new Exception(
                'Block config variants must be supplied as an array' );
        }

        foreach( $blockConfig['variants'] as $variant )
        {
            //Check it has criteria and a return value
            if( ! isset( $variant['criteria'] ) )
            {
                throw new Exception(
                    'All block config variants must have criteria' );
            }

            if( ! isset( $variant['return'] ) )
            {
                throw new Exception(
                    'All block config variants must have a return value' );
            }

            //Validate criteria
            foreach( $variant['criteria'] as $criteria )
            {
                if( ! isset( $criteria['field'] ) || ! isset( $criteria['op'] ) ||
                    ! isset( $criteria['value'] ) )
                {
                    throw new Exception(
                        'All variant criteria must contain field, op and value' );
                }
            }
        }

        //Validate defaultReturn
        if( ! isset( $blockConfig['defaultReturn'] ) )
        {
            throw new Exception(
                'Block config must contain a defaultReturn value' );
        }

        //Set the block config
        $this->_blockConfig = $blockConfig;
    }

    /**
     * Sets the environment variable
     * @param Environment $environment environment object
     * @return NULL
     */
    public function setEnvironment( Environment $environment )
    {
        $this->_environment = $environment;
    }

    /**
     * Calculates the correct block to use based on the variant parameters
     * @throws Exception
     * @return mixed block ID
     */
    public function run()
    {
        if( ! is_null( $this->_blockConfig ) )
        {
            $ret = FALSE;

            foreach( $this->_blockConfig['variants'] as $variant )
            {
                //Check matching criteria
                if( $this->_matchAllCriteria( $variant['criteria'] ) )
                {
                    $ret = $variant['return'];
                }
            }

            //If no matches, use the defaultReturn value
            if( ! $ret )
            {
                $ret = $this->_blockConfig['defaultReturn'];
            }

            return $ret;
        }
        else
        {
            throw new Exception(
                'Can\'t run VariantHelper without a valid block config' );
        }
    }

    /**
     * Matches all input critera against their values
     * @param array $criteria input criteria
     * @return bool TRUE if match, FALSE otherwise
     */
    private function _matchAllCriteria( array $criteria )
    {
        $ret = TRUE;

        foreach( $criteria as $criterion )
        {
            //Get the environment value
            $envValue = $this->_environment->get( $criterion['field'] );

            //Comapre with operator
            if( ! Logic::compare( $envValue, $criterion['op'], $criterion['value'] ) )
            {
                //Value doesn't match, skip criteria
                $ret = FALSE;
            }
        }

        return $ret;
    }
}
