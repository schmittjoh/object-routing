<?php

namespace JMS\Tests\ObjectRouting\Symfony;

use JMS\ObjectRouting\Symfony\Symfony21Adapter;

class Symfony21AdapterTest extends \PHPUnit_Framework_TestCase
{
    /** @var Symfony21Adapter */
    private $adapter;
    private $router;

    public function testGenerate()
    {
        $this->router->expects($this->once())
            ->method('generate')
            ->with('foo', array('bar' => 'baz'), true)
            ->will($this->returnValue('/foo-bar-baz'));

        $this->assertEquals('/foo-bar-baz', $this->adapter->generate('foo', array('bar' => 'baz'), true));
    }

    protected function setUp()
    {
        $this->adapter = new Symfony21Adapter(
            $this->router = $this->getMock('Symfony\Component\Routing\RouterInterface')
        );
    }
}