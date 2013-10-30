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

    function it_should_provide_a_get_method()
    {
        $this->registerPrefixHandler( 'hello',
            (function() { return 'hello world'; }) );
        $this->get('hello')->shouldReturn('hello world');
    }
}
