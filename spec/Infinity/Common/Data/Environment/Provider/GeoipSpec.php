<?php

namespace spec\Infinity\Common\Data\Environment\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Infinity\Common\Data\Environment;

class GeoipSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(
            'Infinity\Common\Data\Environment\Provider\Geoip');
    }

    function it_has_a_getClientIpCountry_method()
    {
        //Can't test with an IP because it relies on Maxmind database.
        $this->getClientIpCountry()->shouldReturn( NULL );
    }

    /**
     * @param \Infinity\Common\Data\Environment $environment
     */
    function it_has_a_register_method( Environment $environment )
    {
        $this->register($environment)->shouldReturn(NULL);
    }
}
