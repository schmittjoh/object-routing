<?php

namespace JMS\Tests\ObjectRouting\Metadata\Driver\Fixture;

use JMS\ObjectRouting\Annotation\ObjectRoute;

/**
 * @ObjectRoute(type = "view", name = "blog_post_view", params = {
 *     "slug": "slug"
 * })
 * @ObjectRoute(type = "edit", name = "blog_post_edit", params = {
 *     "slug": "slug"
 * })
 */
class BlogPost
{
    private $slug;

    public function __construct($slug)
    {
        $this->slug = $slug;
    }

    public function getSlug()
    {
        return $this->slug;
    }
}