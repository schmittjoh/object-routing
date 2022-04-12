<?php

namespace JMS\Tests\ObjectRouting\Metadata;

use JMS\ObjectRouting\Metadata\ClassMetadata;
use PHPUnit\Framework\TestCase;

class ClassMetadataTest extends TestCase
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
