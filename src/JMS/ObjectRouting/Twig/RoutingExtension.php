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