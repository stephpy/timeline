Redis driver
------------

Redis driver does not support delivery wait, it must deliverate immediatly actions.

.. code-block:: php

    require "vendor/autoload.php";

    $redis = new \Redis();
    $redis->connect('127.0.0.1');
    // support \Redis or PRedis (other not tested).

    $serviceLocator = new \Spy\Timeline\ServiceLocator();
    $serviceLocator->addRedisDriver($redis);

    $c = $serviceLocator->getContainer();

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
