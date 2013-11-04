<?php

namespace spec\Infinity\Common\Data\Environment\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Infinity\Common\Data\Environment;

class CookieSpec extends ObjectBehavior
{
    function let()
    {
        $_COOKIE[ 'test' ] = 'test cookie value';
    }

    function letgo()
    {
        $_COOKIE = array();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(
            'Infinity\Common\Data\Environment\Provider\Cookie');
    }

    function it_has_a_getCookieValue_method()
    {
        $this->getCookieValue( 'test' )->shouldReturn( 'test cookie value' );
    }

    /**
     * @param \Infinity\Common\Data\Environment $environment
     */
    function it_has_a_register_method( Environment $environment )
    {
        $this->register($environment)->shouldReturn(NULL);
    }
}
