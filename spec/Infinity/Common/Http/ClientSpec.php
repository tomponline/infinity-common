<?php

namespace spec\Infinity\Common\Http;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClientSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType( 'Infinity\Common\Http\Client' );
    }

    function it_has_a_getChecksMode_method()
    {
        $this->getChecksMode()->shouldReturn( TRUE );
    }

    function it_has_a_getProxy_method()
    {
        $this->getProxy()->shouldReturn( FALSE );
    }

    function it_has_a_getProxyPort_method()
    {
        $this->getProxyPort()->shouldReturn( FALSE );
    }

    function it_has_a_getResponseHeader_method()
    {
        $this->setRequestUri( 'localhost' );
        $this->sendRequest()->shouldReturn( TRUE );
        $this->getResponseHeader( 'User-Agent' )->shouldReturn( FALSE );
    }

    function it_has_a_getResponseCode_method()
    {
        $this->setRequestUri( 'localhost' );
        $this->sendRequest()->shouldReturn( TRUE );
        $this->getResponseCode()->shouldReturn( 200 );
    }

    function it_has_a_getResponseHandle_method()
    {
        $this->getResponseHandle()->shouldReturn( FALSE );
    }

    function it_has_a_getResponseSize_method()
    {
        $this->setRequestUri( 'localhost' );
        $this->sendRequest()->shouldReturn( TRUE );
        $this->getResponseSize()->shouldReturn( 0 );
    }

    function it_has_a_getDownloadedSize_method()
    {
        $this->setRequestUri( 'localhost' );
        $this->sendRequest()->shouldReturn( TRUE );
        $this->getResponseSize()->shouldReturn( 0 );
    }

    function it_has_a_getResponseResult_method()
    {
        $this->setRequestUri( 'localhost' );
        $this->sendRequest()->shouldReturn( TRUE );
        $this->getResponseResult()->shouldReturn( TRUE );
    }

    function it_has_a_getResponseError_method()
    {
        $this->getResponseError()->shouldReturn( FALSE );
    }

    function it_has_a_getError_method()
    {
        $this->getError()->shouldReturn( FALSE );
    }

    function it_has_a_getCurlHandle_method()
    {
        $this->getCurlHandle()->shouldReturn( NULL );
    }

    function it_has_a_getRequestUri_method()
    {
        $this->getRequestUri()->shouldReturn( FALSE );
    }

    function it_has_a_sendRequest_method()
    {
        $this->sendRequest()->shouldReturn( FALSE );
    }

    //Set checks
    function it_has_a_setReadTimeout_method()
    {
        $this->setReadTimeout( 30 )->shouldReturn( NULL );
    }

    function it_has_a_setRequestTimeout_method()
    {
        $this->setRequestTimeout( 30 )->shouldReturn( NULL );
    }

    function it_has_a_setConnectTimeout_method()
    {
        $this->setConnectTimeout( 30 )->shouldReturn( NULL );
    }

    function it_has_a_setRequestUri_method()
    {
        $this->setRequestUri( 'localhost' )->shouldReturn( NULL );
        $this->getRequestUri()->shouldReturn( 'localhost' );
    }

    function it_has_a_setChecksMode_method()
    {
        $this->setChecksMode( FALSE )->shouldReturn( NULL );
        $this->getChecksMode()->shouldReturn( FALSE );
    }

    function it_has_a_setFollowRedirects_method()
    {
        $this->setFollowRedirects( FALSE )->shouldReturn( NULL );
    }

    function it_has_a_setRequestHeader_method()
    {
        $this->setRequestHeader( 'User-Agent', 'test' )->shouldReturn( NULL );
    }

    function it_has_a_setRequestCredentials_method()
    {
        $this->setRequestCredentials( 'user', 'passwd' )->shouldReturn( NULL );
    }

    function it_has_a_setRequestMethod_method()
    {
        $this->setRequestMethod( 'POST' )->shouldReturn( NULL );
    }

    function it_has_a_setRequestData_method()
    {
        $data = array(
            'foo'   => 'bar',
        );

        $this->setRequestData( $data )->shouldReturn( NULL );
    }

    function it_has_a_setMemoryLimit_method()
    {
        $memoryLimit = ( 2 * 1024 * 1024 );

        $this->setMemoryLimit( $memoryLimit )->shouldReturn( NULL );
    }

    function it_has_a_setProxy_method()
    {
        $this->setProxy( '12.34.56.789' )->shouldReturn( NULL );
        $this->getProxy()->shouldReturn( '12.34.56.789' );
    }

    function it_has_a_setProxyPort_method()
    {
        $this->setProxyPort( '8080' )->shouldReturn( NULL );
        $this->getProxyPort()->shouldReturn( '8080' );
    }

    //Reset checks
    function it_has_a_resetRequestHeaders_method()
    {
        $this->resetRequestHeaders()->shouldReturn( NULL );
    }

    function it_has_a_resetRequestCredentials_method()
    {
        $this->resetRequestCredentials()->shouldReturn( NULL );
    }

    function it_has_a_resetRequest_method()
    {
        $this->setRequestUri( 'localhost' );
        $this->setRequestMethod( 'POST' );
        $this->setRequestData( array() );
        $this->setRequestCredentials( 'user', 'passwd' );
        $this->setRequestHeader( 'test', 'value' );

        $this->resetRequest()->shouldReturn( NULL );

        $this->getRequestUri()->shouldReturn( FALSE );
    }
}
