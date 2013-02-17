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
        return array(
            'object_path' => new \Twig_Function_Method($this, 'path'),
            'object_url'  => new \Twig_Function_Method($this, 'url'),
        );
    }

    public function url($type, $object)
    {
        return $this->router->generate($type, $object, true);
    }

    public function path($type, $object)
    {
        return $this->router->generate($type, $object);
    }

    public function getName()
    {
        return 'jms.object_routing';
    }
}