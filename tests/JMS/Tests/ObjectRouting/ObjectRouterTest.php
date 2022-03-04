<?php

namespace JMS\Tests\ObjectRouting;

use Doctrine\Common\Annotations\AnnotationReader;
use JMS\ObjectRouting\Metadata\ClassMetadata;
use JMS\ObjectRouting\Metadata\Driver\AnnotationDriver;
use JMS\ObjectRouting\ObjectRouter;
use JMS\ObjectRouting\RouterInterface;
use Metadata\MetadataFactoryInterface;
use PHPUnit\Framework\TestCase;

class ObjectRouterTest extends TestCase
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

    public function testGenerateNonExistentType()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The object of class "stdClass" has no route with type "foo". Available types: view');

        $metadata = new ClassMetadata('stdClass');
        $metadata->addRoute('view', 'view_name');

        $this->factory->expects($this->once())
            ->method('getMetadataForClass')
            ->will($this->returnValue($metadata));

        $this->router->generate('foo', new \stdClass);
    }

    public function testGenerateNoMetadata()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('There were no object routes defined for class "stdClass".');

        $this->factory->expects($this->once())
            ->method('getMetadataForClass')
            ->will($this->returnValue(null));

        $this->router->generate('foo', new \stdClass);
    }

    protected function setUp(): void
    {
        $this->router = new ObjectRouter(
            $this->adapter = $this->getMockBuilder(RouterInterface::class)->getMock(),
            $this->factory = $this->getMockBuilder(MetadataFactoryInterface::class)->getMock()
        );
    }
}
