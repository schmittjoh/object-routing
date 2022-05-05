Object Routing Library
======================

This library makes generating routes for objects a breeze, and is not tied to any concrete router implementation. As
part of the library, we ship an adapter for Symfony's router.

Installation
------------
You can install this library through composer:

.. code-block :: bash

    composer require jms/object-routing

or add it to your ``composer.json`` file directly.

Usage
-----

Annotations
----------
::

    use JMS\ObjectRouting\Annotation\ObjectRoute;

    /**
     * @ObjectRoute(type = "view", name = "the-actual-route-name", params = {
     *     "slug": "slug",
     * })
     */
    class BlogPost
    {
        public function getSlug()
        {
            /** .. */
        }
    }

Php
----
::

    <?php
    // file: Acme.Model.BlogPost.php
    $metadata = new JMS\ObjectRouting\Metadata\ClassMetadata('Acme\Model\BlogPost');

    $metadata->addRoute('view', 'the-actual-route-name', array('slug' => 'slug'));

    return $metadata;

Yaml
----
file: Acme.Model.BlogPost.yml::

    Acme\Model\BlogPost:
        view:
            name: "the-actual-route-name"
            params:
                slug: "slug"

Xml
----
::

    <!-- file: Acme.Model.BlogPost.xml -->
    <?xml version="1.0" encoding="UTF-8" ?>
    <object-routing>
        <class name="Acme\Model\BlogPost">
            <route type="view" name="the-actual-route-name">
                <param name="slug">slug</param>
            </route>
        </class>
    </object-routing>


Route parameters are key-value pairs where keys represent the placeholder in the URL template, and values can be any
value that is supported by Symfony2's PropertyAccess Component.

If you are using Symfony2 and you defined a route like this::

    class BlogPostController
    {
        /**
         * @Route("/blog-posts/{slug}", name = "the-actual-route-name")
         */
        public function viewAction(BlogPost $post)
        {
        }
    }

you can generate this route with the object router very easily::

    $objectRouter->generate('view', $blogPost);
    // equivalent to
    $router->generate('the-actual-route-name', array('slug' => $blogPost->getSlug()));

For Twig, this library also provides two new functions:

.. code-block :: html+jinja

    {{ object_path('view', blogPost) }}
    {# equivalent to #}
    {{ path('the-actual-route-name', {'slug': blogPost.slug}) }}

    {{ object_url('view', blogPost) }}
    {# equivalent to #}
    {{ url('the-actual-route-name', {'slug': blogPost.slug}) }}

License
-------

The code is released under the business-friendly `Apache2 license`_.

Documentation is subject to the `Attribution-NonCommercial-NoDerivs 3.0 Unported
license`_.

.. _Apache2 license: http://www.apache.org/licenses/LICENSE-2.0.html
.. _Attribution-NonCommercial-NoDerivs 3.0 Unported license: http://creativecommons.org/licenses/by-nc-nd/3.0/

