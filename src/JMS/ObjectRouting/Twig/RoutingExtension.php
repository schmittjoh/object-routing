<?php

namespace JMS\ObjectRouting\Twig;

use JMS\ObjectRouting\ObjectRouter;

class RoutingExtension extends \Twig_Extension
{
    private $router;

    public function __construct(ObjectRouter $router)
    {
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('object_path', [$this, 'path']),
            new \Twig_SimpleFunction('object_url', [$this, 'url']),
        ];
    }

    public function url($type, $object, array $extraParams = [])
    {
        return $this->router->generate($type, $object, true, $extraParams);
    }

    public function path($type, $object, array $extraParams = [])
    {
        return $this->router->generate($type, $object, false, $extraParams);
    }
}
