Spread
------

Action example:

*Chuck Norris Own the World with Vic Mc Key*

We want to publish this action on:

- Chuck Norris timeline
- Sheldon timeline
- Franky Vincent timeline
- World timeline

When you publish an action, you can choose spreads (listed examples above) by defining component.

By default, spread are only published on subject action (Chuck norris) on this example. To enable publication
on other timelines, let's create a Spread class.

Create Spread Class
```````````````````

.. code-block:: php

    namespace Acme\Spread;

    use Spy\Timeline\Model\ActionInterface;
    use Spy\Timeline\Spread\SpreadInterface;
    use Spy\Timeline\Spread\Entry\EntryCollection;
    use Spy\Timeline\Spread\Entry\Entry;
    use Spy\Timeline\Spread\Entry\EntryUnaware;

    class MySpread implements SpreadInterface
    {
        public function supports(ActionInterface $action)
        {
            if ($action->getSubject()->getIdentifier() == 'ChuckNorris') {
                return true; // will process this spread and may be other spreads
            } else {
                return false; // will not process this spread, but may be others.
            }
        }

        public function process(ActionInterface $action, EntryCollection $coll)
        {
            // can define an Entry with a ComponentInterface as argument
            $coll->add(new Entry($action->getComponent('subject')));

            // or an EntryUnaware, on these examples, we are not aware about components and
            // we don't want to retrieve them, let library do it for us.

            // you can use composite identifier by adding an array as second argument
            $coll->add(new EntryUnaware('User', 'Sheldon'));
            $coll->add(new EntryUnaware('User', 'Francky Vincent'));
            $coll->add(new EntryUnaware('Object', 'world'));
        }
    }

Then, add it to spread manager.

.. code-block:: php

    $c['spread.deployer']->addSpread(new \Acme\Spread\MySpread());
    $c['spread.deployer']->addSpread(new \Acme\Spread\AnOtherOneSpread());


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
