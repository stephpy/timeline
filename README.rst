Spy Timeline Documentation
==========================

.. image:: https://secure.travis-ci.org/stephpy/timeline.png?branch=master
   :target: http://travis-ci.org/stephpy/timeline

Standalone library to make timeline with php.

At this moment, only one driver is **redis**, use `Symfony2` and `Bundle <https://github.com/stephpy/timeline-bundle>`_ to have other drivers.

Description
-----------

A timeline is a collection of actions which can be represented by:

- **Subject**
- **Verb**
- **Complements** (directComplement, indirectComplement, etc...)

Example:

+--------------+---------+--------------------------------------------------------------------+
|   Subject    |  Verb   | Complements                                                        |
+==============+=========+====================================================================+
| Chuck Norris | owns    | the world (directComplement), with Vic Mc Key (indirectComplement) |
+--------------+---------+--------------------------------------------------------------------+
| Sheldon      | says    | Bazinga (directComplement)                                         |
+--------------+---------+--------------------------------------------------------------------+

There are two types of action lists to retrieve:

Timeline
~~~~~~~~

Stream of actions where the subject is involved + all actions of its **spreads**, see `spread.rst <https://github.com/stephpy/timeline/tree/master/doc/spread.rst>`_

SubjectAction
~~~~~~~~~~~~~

All actions the subject performed.

Context
~~~~~~~

Imagine Chuck Norris has 233 friends and follow 20 companies.

If we have one context, like facebook, his wall will return each action performed by his friends and companies.

You can also use **Contexts** to filter timelines, for example, we can have 3 contexts:

- GLOBAL: actions of his friends and companies
- FRIEND: actions of his friends
- COMPANIES: actions of his companies

You can define as many contexts as you want.
If you have only one context, you'll get each action without being able to easily filter them to return only "OWN" actions or have only actions performed by ChuckNorris' friends

That's why we have a "GLOBAL" context, and you can easily add other contexts.

Installation
------------
Use `Composer <https://github.com/composer/composer/>`_ to install: ``stephpy/timeline``.

In your `composer.json` you should have:

.. code-block:: yaml

    {
        "require": {
            "stephpy/timeline": "~1.0.0",
            "pimple/pimple": "*"
        }
    }

Pimple is not mandatory but if you use this library without the bundle, it'll be really easier.

Requirements
~~~~~~~~~~~~

Timeline uses php **>=5.3.2**.

Documentation
-------------

- `Index <https://github.com/stephpy/timeline/tree/master/README.rst>`_
- `Basic usage <https://github.com/stephpy/timeline/tree/master/doc/basic_usage.rst>`_
- `Redis driver <https://github.com/stephpy/timeline/tree/master/doc/drivers/redis.rst>`_
- `Query Builder <https://github.com/stephpy/timeline/tree/master/doc/query_builder.rst>`_
- `Filters <https://github.com/stephpy/timeline/tree/master/doc/filter.rst>`_
- `Notification <https://github.com/stephpy/timeline/tree/master/doc/notification.rst>`_
- `Pagination <https://github.com/stephpy/timeline/tree/master/doc/pagination.rst>`_
- `Spreads <https://github.com/stephpy/timeline/tree/master/doc/spread.rst>`_

Unit Tests
----------

You can run tests with:

.. code-block:: sh

    bin/atoum -d tests/units

Thanks
------
Thanks to all `timeline contributors <https://github.com/stephpy/timeline/graphs/contributors>`_ and `bundle contributors <https://github.com/stephpy/timeline-bundle/graphs/contributors>`_.
