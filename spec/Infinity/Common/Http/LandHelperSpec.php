<?php

namespace spec\Infinity\Common\Http;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LandHelperSpec extends ObjectBehavior
{
    function letgo()
    {
        $_SERVER = array();
        $_GET = array();
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

    function it_has_a_setPersistedLandParamRules_method()
    {
        $config = array(
            'testParam'     => 'P1D',
            'testParam2'    => 'session',
        );

        $this->setPersistedLandParamRules( $config )->shouldReturn( NULL );
    }

    function it_has_a_runPersistedLandParamDetection_method()
    {
        $config = array(
            'testParam'     => 'P1D',
            'testParam2'    => 'session',
        );

        $_GET['testParam']  = 'hello world';
        $_GET['testParam2'] = 'session value';

        $this->setPersistedLandParamRules( $config )->shouldReturn( NULL );
        $this->runPersistedLandParamDetection()->shouldReturn( NULL );
    }
}
