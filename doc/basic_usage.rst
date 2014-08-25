Basic usage
-----------

This example uses the `Pimple dependency injection container <http://pimple.sensiolabs.org/>`_.

1) Initialize container.
````````````````````````

.. code-block:: php

    require "vendor/autoload.php";

    $redis = new \Redis();
    $redis->connect('127.0.0.1'); // support \Redis or PRedis (other not tested).

    $serviceLocator = new \Spy\Timeline\ServiceLocator();
    $serviceLocator->addRedisDriver($redis);

    $c = $serviceLocator->getContainer();

2) Add spreads
``````````````

@todo

3) Add an action
````````````````

.. code-block:: php

    $actionManager = $c['action_manager'];
    $chuck         = $actionManager->findOrCreateComponent('User', 'ChuckNorris');
    $bruceLee      = $actionManager->findOrCreateComponent('User', 'BruceLee');

    $action = $actionManager->create($chuck, 'kick', array('directComplement' => $bruceLee))
    $actionManager->updateAction($action);

You should see some keys inserted on redis ;).

4) Fetch timeline for Chuck Norris
``````````````````````````````````

.. code-block:: php

    $actionManager   = $c['action_manager'];
    $timelineManager = $c['timeline_manager'];
    $chuck           = $actionManager->findOrCreateComponent('User', 'ChuckNorris');

    $timeline = $timelineManager->getTimeline($chuck);

5) Fetch actions for Chuck Norris
`````````````````````````````````

.. code-block:: php

    $actionManager   = $c['action_manager'];
    $chuck           = $actionManager->findOrCreateComponent('User', 'ChuckNorris');

    $timeline = $actionManager->getSubjectActions($chuck);


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
