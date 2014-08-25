Filter
------

Filters apply modifications to a collection of actions.

This bundle provides 2 filters, **DuplicateKey** and **DataHydrator**.

Add your own filter
```````````````````

In order to implement a filter you can define a class that implements `Spy\Timeline\Filter\FilterInterface` and add it to FilterManager.

.. code-block:: php

    // should be done at initialization of your project.
    $myFilter = new \Acme\MyFilter();
    $c['filter.manager']->add($myFilter);

DuplicateKey
````````````

Imagine these actions:

.. code-block:: txt

    Chuck    | fight | BruceLee
    BruceLee | fight | Chuck

You may not want to show on your page these two identical actions. This is what it is known as a **duplicateKey** entry.

When you create these two timeline actions you have a DuplicateKey.

When filtering a timeline with the DuplicateKey filter this will remove one of the two actions (the biggest duplicatePriority field, if you don't define it, it will delete the second entry). It will set to *true* the **isDuplicated** field on timeline_action.


Usage
~~~~~

.. code-block:: php

    // enable it (should be done at initialization of your project).
    $c['filter.manager']->add($c['filter.duplicate_key']);

    // example of push actions
    $chuck         = $actionManager->findOrCreateComponent('User', 'Chuck');
    $bruceLee      = $actionManager->findOrCreateComponent('User', 'BruceLee');

    $duplicateKey = uniqid();
    $action1 = $actionManager->create($chuck, 'fight', array('directComplement' => $bruceLee))
    $action1->setDuplicateKey($duplicateKey);
    $action1->setDuplicatePriority(1); // optional

    $action2 = $actionManager->create($chuck, 'fight', array('directComplement' => $bruceLee))
    $action2->setDuplicateKey($duplicateKey);
    $action2->setDuplicatePriority(2); // optional

    $actionManager->updateAction($action1);
    $actionManager->updateAction($action2);


DataHydrator
````````````

This filter will hydrate your related objects, this will regroup the queries to avoid n query calls per action. In this way, if you have two timelines:

.. code-block:: txt

    \Entity\User:1 | comment | \Entity\Article:2 | of | \Entity\User:2
    \Entity\User:2 | comment | \Entity\Article:7 | of | \Entity\User:1

It will execute 2 sql queries!

- \Entity\User -> whereIn 1 and 2
- \Entity\Article -> whereIn 2 and 7

Removing Actions with Unresolved References
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Use the `filter.data_hydrator.filter_unresolved = true` parameters (defined on container) to remove any actions which have unresolved references after the hydration process. This will prevent unexpected EntityNotFoundExceptions when accessing an action component which was removed from the database, but is marked for Lazy-Loading by the entity loading listener.

Locators
~~~~~~~~

Locators will search data to attribute to components. There is no locator provided on this library (only with the bundle).

You can add your own locator, for example if you store your components on a filesystem or on another storage.

Imagine you have a component which represents a file:

.. code-block:: php

    $component = $actionManager->findOrCreateComponent('file', '/path/to/file.txt');

You want to retrieve the content of this file when fetching the main timeline or the subjectAction timeline:

Define the locator:

.. code-block:: php

    namespace Acme\Demo;

    use Spy\Timeline\Filter\DataHydrator\Locator\LocatorInterface;

    class FileSystem implements LocatorInterface
    {
        public function supports($model)
        {
            return $model === 'file';
        }

        public function locate($model, array $components)
        {
            foreach ($components as $component) {
                $component->setData(file_get_contents($component->getIdentifier()));
            }
        }
    }

Add this locator to data_hydrator filter:

.. code-block:: php

    $c['filter.data_hydrator']->addLocator(new \Acme\Demo\FileSystem());


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
