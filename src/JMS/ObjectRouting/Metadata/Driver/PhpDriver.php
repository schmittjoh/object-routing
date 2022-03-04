<?php

/*
 * Copyright 2016 Sebastian Kroczek <sk@xbug.de>
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


use JMS\ObjectRouting\Exception\RuntimeException;
use JMS\ObjectRouting\Metadata\ClassMetadata;
use Metadata\Driver\AbstractFileDriver;

/**
 * Class PhpDriver
 * @package JMS\ObjectRouting\Metadata\Driver
 * @author  Sebastian Kroczek <sk@xbug.de>
 */
class PhpDriver extends AbstractFileDriver
{
    protected function loadMetadataFromFile(\ReflectionClass $class, $file): ?ClassMetadata
    {
        $metadata = require $file;
        if (!$metadata instanceof ClassMetadata) {
            throw new RuntimeException(sprintf('The file %s was expected to return an instance of ClassMetadata, but returned %s.', $file, json_encode($metadata)));
        }
        if ($metadata->name !== $class->name) {
            throw new RuntimeException(sprintf('The file %s was expected to return metadata for class %s, but instead returned metadata for class %s.', $class->name, $metadata->name));
        }

        return $metadata;
    }

    protected function getExtension(): string
    {
        return 'php';
    }
}
