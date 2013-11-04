<?php

namespace spec\Infinity\Common\Http;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RequestSpec extends ObjectBehavior
{
    function let()
    {
        $_SERVER['REQUEST_URI']         =
            'http://www.infinitycloud.com/segment1/segment2/testing.txt?queryParam1=queryVal1&queryParam2=queryVal2';
        $_SERVER['PATH_INFO']           =
            '/segment1/segment2/testing.txt';
        $_GET['queryParam1']            = 'queryVal1';
        $_POST['bodyParam1']            = 'bodyVal1';
        $_SERVER['REQUEST_METHOD']      = 'POST';
        $_SERVER['HTTP_ACCEPT']         = 'text/html';
        $_SERVER[ 'REMOTE_ADDR' ]       = '127.0.0.1';
        $_COOKIE[ 'cookie1' ]           = 'testVal1';
        $_SERVER[ 'HTTP_USER_AGENT' ]   = 'User Agent';

        //Uppercase to test for lowercase conversion
        $_SERVER[ 'HTTP_HOST' ]         = 'LOCALHOST';
        $_SERVER[ 'HTTP_REFERER' ]      = 'http://somesite.com';
        $_SERVER[ 'PHP_AUTH_USER' ]     = 'user';
        $_SERVER[ 'PHP_AUTH_PW' ]       = 'pass';
    }

    function letgo()
    {
        $_SERVER = array();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Infinity\Common\Http\Request');
    }

    function it_has_a_getSegment_method()
    {
        $this->getSegment( 1 )->shouldReturn( 'segment1' );
        $this->getSegment( 2 )->shouldReturn( 'segment2' );
        $this->getSegment( 3 )->shouldReturn( 'testing.txt' );
        $this->getSegment( -1 )->shouldReturn( 'testing.txt' );
        $this->getSegment( 0 )->shouldReturn( '' );
    }

    function it_has_a_getSegmentCount_method()
    {
        $this->getSegmentCount()->shouldReturn(3);
    }

    function it_has_a_getUri_method()
    {
        $this->getUri()->shouldReturn(
            'http://www.infinitycloud.com/segment1/segment2/testing.txt?queryParam1=queryVal1&queryParam2=queryVal2');
    }

    function it_has_a_getPath_method()
    {
        $this->getPath()->shouldReturn('/segment1/segment2/testing.txt');
    }

    function it_has_a_getExtension_method()
    {
        $this->getExtension()->shouldReturn('txt');
    }

    function it_has_a_getUriParameter_method()
    {
        $this->getUriParameter('queryParam1')->shouldReturn('queryVal1');
    }

    function it_has_a_getUriParameters_method()
    {
        $this->getUriParameters('queryParam1')->shouldReturn($_GET);
    }

    function it_has_a_getMethod_method()
    {
        $this->getMethod()->shouldReturn('POST');
    }

    function it_has_a_getBodyParameter_method()
    {
        $this->getBodyParameter('bodyParam1')->shouldReturn('bodyVal1');
    }

    function it_has_a_getBodyParameters_method()
    {
        $this->getBodyParameters('queryParam1')->shouldReturn($_POST);
    }

    function it_has_a_getAccept_method()
    {
        $this->getAccept()->shouldReturn('text/html');
    }

    function it_has_a_getClientIp_method()
    {
        $this->getClientIp()->shouldReturn('127.0.0.1');
    }

    function it_has_a_getCookie_method()
    {
        $this->getCookie('cookie1')->shouldReturn('testVal1');
    }

    function it_has_a_getUserAgent_method()
    {
        $this->getUserAgent()->shouldReturn('User Agent');
    }

    function it_has_a_getHost_method()
    {
        $this->getHost()->shouldReturn('localhost');
        $this->getHost()->shouldNotReturn('LOCALHOST');
    }

    function it_has_a_getReferrer_method()
    {
        $this->getReferrer()->shouldReturn('http://somesite.com');
    }

    function it_has_a_getCredentials_method()
    {
        $this->getCredentials()->shouldReturn(array(
            'username'  => 'user',
            'password'  => 'pass' ));
    }
}
