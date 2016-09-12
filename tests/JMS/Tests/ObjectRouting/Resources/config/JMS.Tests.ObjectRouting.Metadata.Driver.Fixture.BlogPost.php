<?php

$metadata = new JMS\ObjectRouting\Metadata\ClassMetadata('JMS\Tests\ObjectRouting\Metadata\Driver\Fixture\BlogPost');

$metadata->addRoute('view', 'blog_post_view', array('slug' => 'slug'));
$metadata->addRoute('edit', 'blog_post_edit', array('slug' => 'slug'));

return $metadata;