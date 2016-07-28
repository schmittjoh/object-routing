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

namespace JMS\ObjectRouting\Twig;

use JMS\ObjectRouting\ObjectRouter;

/**
 * Class Routing20Extension
 * @package JMS\ObjectRouting\Twig
 * @author Sebastian Kroczek <sk@xbug.de>
 */
class Routing20Extension extends \Twig_Extension
{
    private $router;

    public function __construct(ObjectRouter $router)
    {
        $this->router = $router;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('object_path', [$this, 'path']),
            new \Twig_SimpleFunction('object_url', [$this, 'url']),
        );
    }

    public function url($type, $object, array $extraParams = array())
    {
        return $this->router->generate($type, $object, true, $extraParams);
    }

    public function path($type, $object, array $extraParams = array())
    {
        return $this->router->generate($type, $object, false, $extraParams);
    }

    public function getName()
    {
        return 'jms.object_routing';
    }
}