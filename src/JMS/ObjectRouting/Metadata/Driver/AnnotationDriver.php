<?php

/*
 * Copyright 2013 Johannes M. Schmitt <schmittjoh@gmail.com>
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace JMS\ObjectRouting\Metadata\Driver;

use Doctrine\Common\Annotations\Reader;
use JMS\ObjectRouting\Annotation\ObjectRoute;
use JMS\ObjectRouting\Metadata\ClassMetadata;
use Metadata\Driver\DriverInterface;

/**
 * Class AnnotationDriver
 *
 * @package JMS\ObjectRouting\Metadata\Driver
 */
class AnnotationDriver implements DriverInterface
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param \ReflectionClass $class
     *
     * @return ClassMetadata|null
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $metadata = new ClassMetadata($class->name);

        $hasMetadata = false;
        foreach ($this->reader->getClassAnnotations($class) as $annot) {
            if ($annot instanceof ObjectRoute) {
                $hasMetadata = true;
                $metadata->addRoute($annot->type, $annot->name, $annot->params);
            }
        }

        return $hasMetadata ? $metadata : null;
    }
}