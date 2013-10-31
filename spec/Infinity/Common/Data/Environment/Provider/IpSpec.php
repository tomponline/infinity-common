<?php

namespace spec\Infinity\Common\Data\Environment\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Infinity\Common\Data\Environment;

class IpSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(
            'Infinity\Common\Data\Environment\Provider\Ip');
    }

    function it_has_a_getClientIp_method()
    {
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $this->getClientIp()->shouldReturn( '127.0.0.1' );
    }

    /**
     * @param \Infinity\Common\Data\Environment $environment
     */
    function it_has_a_register_method( Environment $environment )
    {
        $this->register($environment)->shouldReturn(NULL);
    }
}
