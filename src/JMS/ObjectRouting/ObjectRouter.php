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

namespace JMS\ObjectRouting;

use Doctrine\Common\Annotations\AnnotationReader;
use JMS\ObjectRouting\Metadata\ClassMetadata;
use JMS\ObjectRouting\Metadata\Driver\AnnotationDriver;
use Metadata\MetadataFactory;
use Metadata\MetadataFactoryInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class ObjectRouter
 *
 * @package JMS\ObjectRouting
 */
class ObjectRouter
{
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var MetadataFactoryInterface
     */
    private $metadataFactory;
    /**
     * @var PropertyAccessor
     */
    private $accessor;

    /**
     * @param RouterInterface $router
     *
     * @return ObjectRouter
     */
    public static function create(RouterInterface $router)
    {
        return new self(
            $router,
            new MetadataFactory(
                new AnnotationDriver(new AnnotationReader())
            )
        );
    }

    /**
     * @param RouterInterface          $router
     * @param MetadataFactoryInterface $metadataFactory
     */
    public function __construct(RouterInterface $router, MetadataFactoryInterface $metadataFactory)
    {
        $this->router = $router;
        $this->metadataFactory = $metadataFactory;
        $this->accessor = new PropertyAccessor();
    }

    /**
     * Generates a path for an object.
     *
     * @param string  $type
     * @param object  $object
     * @param boolean $absolute
     * @param array   $extraParams
     *
     * @throws \InvalidArgumentException
     */
    public function generate($type, $object, $absolute = UrlGeneratorInterface::ABSOLUTE_URL, array $extraParams = array())
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException(sprintf('$object must be an object, but got "%s".', gettype($object)));
        }

        /** @var $metadata ClassMetadata */
        $metadata = $this->metadataFactory->getMetadataForClass(get_class($object));
        if (null === $metadata) {
            throw new \RuntimeException(sprintf('There were no object routes defined for class "%s".', get_class($object)));
        }

        if (!isset($metadata->routes[$type])) {
            throw new \RuntimeException(sprintf(
                                            'The object of class "%s" has no route with type "%s". Available types: %s',
                                            get_class($object),
                                            $type,
                                            implode(', ', array_keys($metadata->routes))
                                        ));
        }

        $route = $metadata->routes[$type];

        $params = $extraParams;
        foreach ($route['params'] as $k => $path) {
            $params[$k] = $this->accessor->getValue($object, $path);
        }

        return $this->router->generate($route['name'], $params, $absolute);
    }

    /**
     * @param string $type
     * @param object $object
     * @param array  $extraParams
     *
     * @return mixed
     */
    public function path($type, $object, array $extraParams = array())
    {
        return $this->generate($type, $object, UrlGeneratorInterface::ABSOLUTE_URL, $extraParams);
    }

    /**
     * @param string $type
     * @param object $object
     * @param array  $extraParams
     *
     * @return mixed
     */
    public function url($type, $object, array $extraParams = array())
    {
        return $this->generate($type, $object, UrlGeneratorInterface::ABSOLUTE_URL, $extraParams);
    }
}