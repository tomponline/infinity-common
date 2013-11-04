<?php

namespace spec\Infinity\Common\Data\Environment\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Infinity\Common\Data\Environment;

class UrlSpec extends ObjectBehavior
{
    function let()
    {
        $_GET[ 'test' ] = 'test url param value';
        $_SERVER[ 'HTTP_HOST' ] = 'test.com';
    }

    function letgo()
    {
        $_GET = array();
        $_SERVER = array();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(
            'Infinity\Common\Data\Environment\Provider\Url');
    }

    function it_has_a_getUrlParam_method()
    {
        //Can't test with an IP because it relies on Maxmind database.
        $this->getUrlParam( 'test' )->shouldReturn( 'test url param value' );
    }

    function it_has_a_getUrlHost_method()
    {
        //Can't test with an IP because it relies on Maxmind database.
        $this->getUrlDomain()->shouldReturn( 'test.com' );
    }

    /**
     * @param \Infinity\Common\Data\Environment $environment
     */
    function it_has_a_register_method( Environment $environment )
    {
        $this->register($environment)->shouldReturn(NULL);
    }
}
