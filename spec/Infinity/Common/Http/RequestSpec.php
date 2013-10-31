<?php

namespace spec\Infinity\Common\Http;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RequestSpec extends ObjectBehavior
{
    public function __construct()
    {
        $_SERVER['REQUEST_URI'] =
            'http://www.infinitycloud.com/segment1/segment2?queryParam1=queryVal1&queryParam2=queryVal2';
        $_SERVER['PATH_INFO'] =
            '/segment1/segment2';
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Infinity\Common\Http\Request');
    }

    function it_has_a_getSegment_method()
    {
        $this->getSegment( 1 )->shouldReturn( 'segment1' );
        $this->getSegment( 2 )->shouldReturn( 'segment2' );
        $this->getSegment( -1 )->shouldReturn( 'segment2' );
        $this->getSegment( 0 )->shouldReturn( '' );
    }

    function it_has_a_getSegmentCount_method()
    {
        $this->getSegmentCount()->shouldReturn(2);
    }
}
