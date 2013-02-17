Object Routing Library
======================
This library allows you to define routes for your objects. It can be used with any router implementation, the library
has an adapter for Symfony 2.1 Router built-in.

Installation
------------
You can install this library through composer:

.. code-block :: bash

    composer require jms/object-routing

or add it to your ``composer.json`` file directly.

Usage
-----
At the moment, routes can only be defined via Doctrine annotations::

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

Route parameters are key-value pairs where keys represent the placeholder in the URL template, and values can be any
value that is supported by Symfony2's PropertyAccess Component.

Assuming that you're using Symfony2 (although you do not have to), the corresponding view action could look like::

    class BlogPostController
    {
        /**
         * @Route("/blog-posts/{slug}", name = "the-actual-route-name")
         */
        public function viewAction(BlogPost $post)
        {
        }
    }

and routes could be generated like this::

    $objectRouter->generate('view', $blogPost);


License
-------

The code is released under the business-friendly `Apache2 license`_.

Documentation is subject to the `Attribution-NonCommercial-NoDerivs 3.0 Unported
license`_.

.. _Apache2 license: http://www.apache.org/licenses/LICENSE-2.0.html
.. _Attribution-NonCommercial-NoDerivs 3.0 Unported license: http://creativecommons.org/licenses/by-nc-nd/3.0/

