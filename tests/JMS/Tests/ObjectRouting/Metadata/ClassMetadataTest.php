<?php

namespace JMS\Tests\ObjectRouting\Metadata;

use JMS\ObjectRouting\Metadata\ClassMetadata;

class ClassMetadataTest extends \PHPUnit_Framework_TestCase
{
    public function testMerge()
    {
        $base = new ClassMetadata(\PHPUnit_Framework_TestCase::class);
        $base->addRoute('test', 'base-route');

        $merged = new ClassMetadata(self::class);
        $merged->addRoute('test', 'merged-route');

        $base->merge($merged);

        $this->assertEquals(self::class, $base->name);
        $this->assertEquals(['test' => ['name' => 'merged-route', 'params' => []]], $base->routes);
    }
}
