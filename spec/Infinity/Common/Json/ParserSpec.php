<?php

namespace spec\Infinity\Common\Json;

use stdClass;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ParserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Infinity\Common\Json\Parser');
    }

    function it_should_decode_json_to_a_stdclass()
    {
        $this->decode('{}')->shouldHaveType('stdClass');
        $this->decode('{"foo":"asd"}')->shouldHaveType('stdClass');
        $this->decode('{"foo":"asd"}')->shouldHaveProperty('foo');
    }

    function it_should_decode_json_to_an_array_if_specified()
    {
        $this->decode('{}', true)->shouldBeArray();
        $this->decode('{"foo":"asd"}', true)->shouldBeArray('array');
        $this->decode('{"foo":"asd"}', true)->shouldHaveKey('foo');
    }

    function it_should_encode_to_json_correctly()
    {
        $array = array(1, array(
            'foo' => 'bar'
        ));
        $this->encode($array)->shouldReturn('[1,{"foo":"bar"}]');

        $obj = new stdClass();
        $obj->fob   = 'baz';
        $obj->items = array('a', 4, 'top');
        $array = array(1, $obj, array(
            'foo' => 'bar'
        ));
        $this->encode($array)->shouldReturn('[1,{"fob":"baz","items":["a",4,"top"]},{"foo":"bar"}]');
    }

    function it_should_handle_invalid_decode_using_exception()
    {
        $this->shouldThrow('Exception')->duringDecode('{');
        $this->shouldThrow('Exception')->duringDecode('[{]');
    }

    function it_should_handle_invalid_encode_using_exception()
    {
        $this->shouldThrow('Exception')->duringEncode(fopen('php://temp', 'r'));
    }

    public function getMatchers()
    {
        return [
            'haveProperty' => function($subject, $key) {
                return isset($subject->$key);
            }
        ];
    }
}
