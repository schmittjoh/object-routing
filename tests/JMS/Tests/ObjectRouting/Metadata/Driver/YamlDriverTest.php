<?php

namespace JMS\Tests\ObjectRouting\Metadata\Driver;

use Doctrine\Common\Annotations\AnnotationReader;
use JMS\ObjectRouting\Metadata\Driver\AnnotationDriver;
use JMS\ObjectRouting\Metadata\Driver\YamlDriver;
use Metadata\Driver\FileLocator;
use PHPUnit\Framework\TestCase;

class YamlDriverTest extends TestCase
{
    /** @var YamlDriver */
    private $driver;

    public function testLoad()
    {
        $metadata = $this->driver->loadMetadataForClass(new \ReflectionClass('JMS\Tests\ObjectRouting\Metadata\Driver\Fixture\BlogPost'));
        $this->assertCount(2, $metadata->routes);

        $routes = array(
            'view' => array('name' => 'blog_post_view', 'params' => array('slug' => 'slug')),
            'edit' => array('name' => 'blog_post_edit', 'params' => array('slug' => 'slug')),
        );
        $this->assertEquals($routes, $metadata->routes);
    }

    public function testLoadReturnsNullWhenNoRoutes()
    {
        $this->assertNull($this->driver->loadMetadataForClass(new \ReflectionClass('stdClass')));
    }

    protected function setUp(): void
    {
        $this->driver = new YamlDriver(new FileLocator(array('' => realpath(__DIR__.'/../../Resources/config'))));
    }
}
