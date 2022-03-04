<?php

namespace JMS\Tests\ObjectRouting\Symfony;

use JMS\ObjectRouting\Symfony\Symfony21Adapter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;

class Symfony21AdapterTest extends TestCase
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

    protected function setUp(): void
    {
        $this->adapter = new Symfony21Adapter(
            $this->router = $this->getMockBuilder(RouterInterface::class)->getMock()
        );
    }
}
