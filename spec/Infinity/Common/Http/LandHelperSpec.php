<?php

namespace spec\Infinity\Common\Http;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LandHelperSpec extends ObjectBehavior
{
    function let()
    {
    }

    function letgo()
    {
        $_SERVER = array();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Infinity\Common\Http\LandHelper');
    }

    function it_should_detectLands()
    {
        $_SERVER['HTTP_HOST']       = 'www.mysite.com';
        $_SERVER['HTTP_REFERER']    =
            'http://www.othersite.com/page1.html?someparam=somevalue';

        $this->isLand()->shouldReturn( TRUE );
    }

    function it_should_detectPageViews()
    {
        $_SERVER['HTTP_HOST']       = 'www.mysite.com';
        $_SERVER['HTTP_REFERER']    =
            'http://www.mysite.com/page1.html?someparam=somevalue';

        $this->isLand()->shouldReturn( FALSE );
    }
}
