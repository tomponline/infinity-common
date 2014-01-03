<?php

namespace spec\Infinity\Common\Data\Environment\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Infinity\Common\Data\Environment;

class CookieSpec extends ObjectBehavior
{
    function let()
    {
        $_COOKIE[ 'ic_visits' ] = 2;
        $_SERVER[ 'HTTP_REFERER' ] = 'http://www.example.com/page/?query=params';
        $_SERVER[ 'HTTP_HOST' ] = 'example.com';
    }

    function letgo()
    {
        $_COOKIE = array();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(
            'Infinity\Common\Data\Environment\Provider\VisitCount');
    }

    function it_has_a_getCookieValue_method()
    {
        $this->getVisitCountValue()->shouldReturn( 2 );
    }

    /**
     * @param \Infinity\Common\Data\Environment $environment
     */
    function it_has_a_register_method( Environment $environment )
    {
        $this->register($environment)->shouldReturn(NULL);
    }
}
