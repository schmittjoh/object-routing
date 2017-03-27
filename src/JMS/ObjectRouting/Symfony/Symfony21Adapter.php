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

namespace JMS\ObjectRouting\Symfony;

use JMS\ObjectRouting\RouterInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class Symfony21Adapter
 *
 * @package JMS\ObjectRouting\Symfony
 */
class Symfony21Adapter implements RouterInterface
{
    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $delegate;

    /**
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(\Symfony\Component\Routing\RouterInterface $router)
    {
        $this->delegate = $router;
    }

    /**
     * @param string         $name
     * @param array          $params
     * @param bool|false|int $absolute
     *
     * @return string
     */
    public function generate($name, array $params, $absolute = UrlGeneratorInterface::ABSOLUTE_URL)
    {
        return $this->delegate->generate($name, $params, $absolute);
    }
}