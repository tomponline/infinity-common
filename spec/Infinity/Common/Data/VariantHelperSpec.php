<?php

namespace spec\Infinity\Common\Data;

use Infinity\Common\Data\Environment;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class VariantHelperSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Infinity\Common\Data\VariantHelper');
    }

    function it_has_asetConfig_method()
    {
        $config = array(
            'variants'      => array(),
            'defaultReturn' => 'test',
        );

        $this->setConfig( $config )->shouldReturn( NULL );
    }

    /**
     * @param \Infinity\Common\Data\Environment $environment
     */
    function it_has_a_setEnvironment_method( Environment $environment )
    {
        $this->setEnvironment( $environment )->shouldReturn( NULL );
    }

    /**
     * @param \Infinity\Common\Data\Environment $environment
     */
    function it_has_a_run_method( Environment $environment )
    {
        $config = array(
            'variants'      => array(),
            'defaultReturn' => 'test',
        );

        $this->setConfig( $config )->shouldReturn( NULL );
        $this->setEnvironment( $environment )->shouldReturn( NULL );

        $this->run()->shouldReturn( 'test' );
    }

    /**
     * @param \Infinity\Common\Data\Environment $environment
     */
    function it_should_return_the_correct_variant_if_specified(
        Environment $environment )
    {
        $config = array(
            'variants'      => array(
                array(
                    'criteria'  => array(
                        array(
                            'field' => 'ip_country',
                            'op'    => 'eq',
                            'value' => 'UK',
                        ),
                    ),
                    'return'    => 1,
                ),
                array(
                    'criteria'  => array(
                        array(
                            'field' => 'ip_country',
                            'op'    => 'eq',
                            'value' => 'US',
                        ),
                    ),
                    'return'    => 2,
                ),
            ),
            'defaultReturn' => 'test',
        );

        $environment->get('ip_country')->willReturn('UK');

        $this->setConfig( $config )->shouldReturn( NULL );
        $this->setEnvironment( $environment )->shouldReturn( NULL );

        $this->run()->shouldReturn( 1 );
    }

    /**
     * @param \Infinity\Common\Data\Environment $environment
     */
    function it_should_return_the_correct_variant_if_mulitple_specified(
        Environment $environment )
    {
        $config = array(
            'variants'      => array(
                array(
                    'criteria'  => array(
                        array(
                            'field' => 'ip_country',
                            'op'    => 'eq',
                            'value' => 'UK',
                        ),
                        array(
                            'field' => 'visits',
                            'op'    => 'gt',
                            'value' => 2,
                        ),
                    ),
                    'return'    => 1,
                ),
                array(
                    'criteria'  => array(
                        array(
                            'field' => 'ip_country',
                            'op'    => 'eq',
                            'value' => 'US',
                        ),
                        array(
                            'field' => 'visits',
                            'op'    => 'gt',
                            'value' => 2,
                        ),
                    ),
                    'return'    => 2,
                ),
            ),
            'defaultReturn' => 'test',
        );

        $environment->get('ip_country')->willReturn('US');
        $environment->get('visits')->willReturn(3);

        $this->setConfig( $config )->shouldReturn( NULL );
        $this->setEnvironment( $environment )->shouldReturn( NULL );

        $this->run()->shouldReturn( 2 );
    }

    /**
     * @param \Infinity\Common\Data\Environment $environment
     */
    function it_should_return_defaultReturn_if_no_variant_matches(
        Environment $environment )
    {
        $config = array(
            'variants'      => array(
                array(
                    'criteria'  => array(
                        array(
                            'field' => 'ip_country',
                            'op'    => 'eq',
                            'value' => 'UK',
                        ),
                   ),
                    'return'    => 1,
                ),
                array(
                    'criteria'  => array(
                        array(
                            'field' => 'ip_country',
                            'op'    => 'eq',
                            'value' => 'US',
                        ),
                    ),
                    'return'    => 2,
                ),
            ),
            'defaultReturn' => 'test',
        );

        $environment->get('ip_country')->willReturn('someothercountry');

        $this->setConfig( $config )->shouldReturn( NULL );
        $this->setEnvironment( $environment )->shouldReturn( NULL );

        $this->run()->shouldReturn( 'test' );
    }
}
