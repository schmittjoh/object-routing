<?php

namespace JMS\Tests\ObjectRouting\Symfony;

use JMS\ObjectRouting\Symfony\Symfony22Adapter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Symfony22AdapterTest extends \PHPUnit_Framework_TestCase
{
    /** @var Symfony22Adapter */
    private $adapter;
    private $router;

    public function testGenerate()
    {
        if(!defined('Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL')){
            $this->markTestSkipped('Skipping this test because required constant UrlGeneratorInterface::ABSOLUTE_URL is not defined.');
        }
        $this->router->expects($this->once())
            ->method('generate')
            ->with('foo', array('bar' => 'baz'), UrlGeneratorInterface::ABSOLUTE_URL)
            ->will($this->returnValue('/foo-bar-baz'));

        $this->assertEquals('/foo-bar-baz', $this->adapter->generate('foo', array('bar' => 'baz'), true));
    }

    protected function setUp()
    {
        $this->adapter = new Symfony22Adapter(
            $this->router = $this->getMock('Symfony\Component\Routing\RouterInterface')
        );
    }
}