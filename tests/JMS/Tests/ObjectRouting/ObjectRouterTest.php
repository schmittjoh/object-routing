<?php

namespace JMS\Tests\ObjectRouting;

use Doctrine\Common\Annotations\AnnotationReader;
use JMS\ObjectRouting\Metadata\ClassMetadata;
use JMS\ObjectRouting\Metadata\Driver\AnnotationDriver;
use JMS\ObjectRouting\ObjectRouter;

class ObjectRouterTest extends \PHPUnit_Framework_TestCase
{
    /** @var ObjectRouter */
    private $router;
    private $adapter;
    private $factory;

    public function testGenerate()
    {
        $metadata = new ClassMetadata('stdClass');
        $metadata->addRoute('view', 'view_name');

        $this->factory->expects($this->once())
            ->method('getMetadataForClass')
            ->with('stdClass')
            ->will($this->returnValue($metadata));

        $this->adapter->expects($this->once())
            ->method('generate')
            ->with('view_name', array(), false)
            ->will($this->returnValue('/foo'));

        $this->assertEquals('/foo', $this->router->generate('view', new \stdClass));
    }

    public function testGenerateWithParams()
    {
        $metadata = new ClassMetadata('stdClass');
        $metadata->addRoute('view', 'view_name', array('foo' => 'bar'));

        $object = new \stdClass;
        $object->bar = 'baz';

        $this->factory->expects($this->once())
            ->method('getMetadataForClass')
            ->will($this->returnValue($metadata));

        $this->adapter->expects($this->once())
            ->method('generate')
            ->with('view_name', array('foo' => 'baz'), false)
            ->will($this->returnValue('/foobar'));

        $this->assertEquals('/foobar', $this->router->generate('view', $object));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The object of class "stdClass" has no route with type "foo". Available types: view
     */
    public function testGenerateNonExistentType()
    {
        $metadata = new ClassMetadata('stdClass');
        $metadata->addRoute('view', 'view_name');

        $this->factory->expects($this->once())
            ->method('getMetadataForClass')
            ->will($this->returnValue($metadata));

        $this->router->generate('foo', new \stdClass);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage There were no object routes defined for class "stdClass".
     */
    public function testGenerateNoMetadata()
    {
        $this->factory->expects($this->once())
            ->method('getMetadataForClass')
            ->will($this->returnValue(null));

        $this->router->generate('foo', new \stdClass);
    }

    protected function setUp()
    {
        $this->router = new ObjectRouter(
            $this->adapter = $this->getMock('JMS\ObjectRouting\RouterInterface'),
            $this->factory = $this->getMock('Metadata\MetadataFactoryInterface')
        );
    }
}