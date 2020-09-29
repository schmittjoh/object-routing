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

namespace JMS\ObjectRouting\Metadata;

use Metadata\MergeableClassMetadata;
use Metadata\MergeableInterface;

class ClassMetadata extends MergeableClassMetadata
{
    public $routes = array();

    public function addRoute($type, $name, array $params = array())
    {
        $this->routes[$type] = array(
            'name' => $name,
            'params' => $params,
        );
    }

    public function merge(MergeableInterface $object)
    {
        parent::merge($object);
        $this->routes = array_merge($this->routes, $object->routes);
    }

    public function serialize()
    {
        return serialize(
            array(
                $this->routes,
                parent::serialize(),
            )
        );
    }

    public function unserialize($str)
    {
        list(
            $this->routes,
            $parentStr
            ) = unserialize($str);

        parent::unserialize($parentStr);
    }

}
