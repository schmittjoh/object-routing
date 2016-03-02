<?php

namespace JMS\ObjectRouting\Twig;

use JMS\ObjectRouting\ObjectRouter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class RoutingExtension
 *
 * @package JMS\ObjectRouting\Twig
 */
class RoutingExtension extends \Twig_Extension
{
    /**
     * @var ObjectRouter
     */
    private $router;

    /**
     * @param ObjectRouter $router
     */
    public function __construct(ObjectRouter $router)
    {
        $this->router = $router;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('object_path', [
                $this,
                'path'
            ]),
            new \Twig_SimpleFunction('object_url', [
                $this,
                'url'
            ])
        ];
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
        return $this->router->generate($type, $object, UrlGeneratorInterface::ABSOLUTE_URL, $extraParams);
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
        return $this->router->generate($type, $object, UrlGeneratorInterface::ABSOLUTE_PATH, $extraParams);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jms.object_routing';
    }
}