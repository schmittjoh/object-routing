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
use JMS\ObjectRouting\Exception\XmlErrorException;
use JMS\ObjectRouting\Metadata\ClassMetadata;
use Metadata\Driver\AbstractFileDriver;

/**
 * Class XmlDriver
 * @package JMS\ObjectRouting\Metadata\Driver
 * @author  Sebastian Kroczek <sk@xbug.de>
 */
class XmlDriver extends AbstractFileDriver
{

    /**
     * Parses the content of the file, and converts it to the desired metadata.
     *
     * @param \ReflectionClass $class
     * @param string           $file
     *
     * @return \Metadata\ClassMetadata|null
     */
    protected function loadMetadataFromFile(\ReflectionClass $class, $file)
    {
        $previous = libxml_use_internal_errors(true);
        $elem = simplexml_load_file($file);
        libxml_use_internal_errors($previous);
        if (false === $elem) {
            throw new XmlErrorException(libxml_get_last_error());
        }
        $metadata = new ClassMetadata($name = $class->name);
        if (!$elems = $elem->xpath("./class[@name = '".$name."']")) {
            throw new RuntimeException(sprintf('Could not find class %s inside XML element.', $name));
        }
        $elem = reset($elems);
        $metadata->fileResources[] = $file;
        $metadata->fileResources[] = $class->getFileName();

        if (null !== $xmlRootName = $elem->attributes()->{'xml-root-name'}) {
            $metadata->xmlRootName = (string)$xmlRootName;
        }
        if (null !== $xmlRootNamespace = $elem->attributes()->{'xml-root-namespace'}) {
            $metadata->xmlRootNamespace = (string)$xmlRootNamespace;
        }

        foreach ($elem->xpath('./route') as $r) {
            if ('' === $type = (string)$r->attributes()->{'type'}) {
                throw new RuntimeException('Could not find attribute "type" inside XML element.');
            }
            if ('' === $name = (string)$r->attributes()->{'name'}) {
                throw new RuntimeException('Could not find attribute "name" inside XML element.');
            }

            $params = array();
            foreach ($r->xpath('./param') as $p) {
                $params[(string)$p->attributes()] = (string)$p;
            }

            $metadata->addRoute($type, $name, $params);
        }

        return $metadata;
    }

    /**
     * Returns the extension of the file.
     *
     * @return string
     */
    protected function getExtension()
    {
        return 'xml';
    }
}