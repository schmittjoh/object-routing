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
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlDriver
 * @package JMS\ObjectRouting\Metadata\Driver
 * @author  Sebastian Kroczek <sk@xbug.de>
 */
class YamlDriver extends AbstractFileDriver
{


    /**
     * Parses the content of the file, and converts it to the desired metadata.
     *
     * @param \ReflectionClass $class
     * @param string           $file
     *
     * @return \Metadata\ClassMetadata|null
     */
    protected function loadMetadataFromFile(\ReflectionClass $class, string $file): ?ClassMetadata
    {
        $config = Yaml::parse(file_get_contents($file));

        if (!isset($config[$name = $class->name])) {
            throw new RuntimeException(sprintf('Expected metadata for class %s to be defined in %s.', $class->name, $file));
        }


        $config = $config[$name];
        $metadata = new ClassMetadata($name);
        $metadata->fileResources[] = $file;
        $metadata->fileResources[] = $class->getFileName();

        foreach ($config as $type => $value) {
            if (!array_key_exists('name', $value)) {
                throw new RuntimeException('Could not find key "type" inside yaml element.');
            }
            $metadata->addRoute($type, $value['name'], array_key_exists('params', $value) ? $value['params'] : array());
        }

        return $metadata;

    }

    /**
     * Returns the extension of the file.
     *
     * @return string
     */
    protected function getExtension(): string
    {
        return 'yml';
    }
}
