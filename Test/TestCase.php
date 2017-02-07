<?php

namespace Test;


use Prophecy\Argument;

/**
 * Class TestCase
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * @param $responseBody
     *
     * @return \Prophecy\Prophecy\ObjectProphecy
     */
    protected function getFakeResponse($responseBody)
    {

        $fakeResponse = $this->prophesize(Response::class);
        $stream = $this->prophesize(Stream::class);
        $stream->getContents()->willReturn($responseBody);
        $stream->rewind()->willReturn(null)->shouldBeCalled();
        $fakeResponse->getBody()->willReturn($stream->reveal());

        return $fakeResponse;

    }




}
