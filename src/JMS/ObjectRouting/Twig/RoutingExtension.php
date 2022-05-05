<?php

namespace JMS\ObjectRouting\Twig;

use JMS\ObjectRouting\ObjectRouter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RoutingExtension extends AbstractExtension
{
    private $router;

    public function __construct(ObjectRouter $router)
    {
        $this->router = $router;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('object_path', [$this, 'path']),
            new TwigFunction('object_url', [$this, 'url']),
        ];
    }

    public function url($type, $object, array $extraParams = []): string
    {
        return $this->router->generate($type, $object, true, $extraParams);
    }

    public function path($type, $object, array $extraParams = []): string
    {
        return $this->router->generate($type, $object, false, $extraParams);
    }
}
