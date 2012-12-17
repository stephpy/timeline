Spy Timeline Documentation
==========================

.. image:: https://secure.travis-ci.org/stephpy/timeline.png?branch=master
   :target: http://travis-ci.org/stephpy/timeline

Standalone library to make timeline with php.

At this moment, only one driver is **redis**, use `Symfony2` and `Bundle <https://github.com/stephpy/TimelineBundle>`_ to have other drivers.

Installation
------------
Use `Composer <https://github.com/composer/composer/>`_ to install: ``stephpy/timeline``.

In your `composer.json` you should have:

.. code-block:: yaml

    {
        "require": {
            "stephpy/timeline": "1.0.*"
        }
    }

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
Thanks to all `timeline contributors <https://github.com/stephpy/timeline/graphs/contributors>`_ and `bundle contributors <https://github.com/stephpy/TimelineBundle/graphs/contributors>`_.
