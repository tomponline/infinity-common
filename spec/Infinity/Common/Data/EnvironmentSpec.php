<?php

namespace spec\Infinity\Common\Data;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EnvironmentSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Infinity\Common\Data\Environment');
    }

    function it_has_a_registerPrefixHander_method()
    {
        $this->registerPrefixHandler( 'hello',
            (function() { return 'hello world'; }) )->shouldReturn( NULL );
    }

    function it_has_a_get_method()
    {
        $this->registerPrefixHandler( 'hello',
            (function() { return 'hello world'; }) );
        $this->get('hello')->shouldReturn('hello world');
    }

    /**
     * @param \Infinity\Common\Data\Environment\Provider\Ip $provider
     */
    function it_has_a_registerProvider_method( $provider )
    {
        $provider->register($this)->willReturn(NULL);
        $this->registerProvider($provider)->shouldReturn(NULL);
    }
}
