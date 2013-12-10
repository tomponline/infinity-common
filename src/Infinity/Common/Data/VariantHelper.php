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
    private $_variantConfig;
    private $_environment;

    /**
     * Sets the variant config
     * @param array $variantConfig the variant config
     * @throws Exception
     * @return NULL
     */
    public function setConfig( array $variantConfig )
    {
        //Validate variants
        if( ! is_array( $variantConfig['variants'] ) )
        {
            throw new Exception(
                'Variant config variants must be supplied as an array' );
        }

        foreach( $variantConfig['variants'] as $variant )
        {
            //Check it has criteria and a return value
            if( ! isset( $variant['criteria'] ) )
            {
                throw new Exception(
                    'All variant config variants must have criteria' );
            }

            if( ! isset( $variant['return'] ) )
            {
                throw new Exception(
                    'All variant config variants must have a return value' );
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
        if( ! isset( $variantConfig['defaultReturn'] ) )
        {
            throw new Exception(
                'Variant config must contain a defaultReturn value' );
        }

        //Set the variant config
        $this->_variantConfig = $variantConfig;
    }

    /**
     * Sets the environment variable
     * @param Environment $environment environment object
     * @throws Exception
     * @return NULL
     */
    public function setEnvironment( Environment $environment )
    {
        $this->_environment = $environment;
    }

    /**
     * Calculates the correct variant to use based on the variant criteria
     * @throws Exception
     * @return mixed
     */
    public function run()
    {
        //Validate prerequisites
        if( is_null( $this->_variantConfig ) )
        {
            throw new Exception(
                'Can\'t run VariantHelper without a valid variant config' );
        }

        if( is_null( $this->_environment ) )
        {
            throw new Exception(
                'Can\'t run VariantHelper without an environment' );
        }

        //Set default return
        $ret = FALSE;

        foreach( $this->_variantConfig['variants'] as $variant )
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
            $ret = $this->_variantConfig['defaultReturn'];
        }

        return $ret;
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
