QueryBuilder
------------

*This feature is at the moment only available for the `ORM` driver (available on the bundle).*

You can create a query_builder to fetch timeline actions like you can do with Doctrine ORM QueryBuilder.

Api of query Builder
````````````````````

.. code-block:: php

    $qb = $container->get('spy_timeline.query_builder');

    // filter on timeline subject(s)
    $qb->addSubject($subject); // accept a ComponentInterface
    $qb->setPage(1);
    $qb->setMaxPerPage(10);
    $qb->orderBy($fieldName, 'ASC'); // or DESC
    $qb->groupByAction(); // true or false on first argument. default: true
    $qb->toArray();
    $qb->fromArray($data);

    // add filters
    $qb->setCriterias($criterias); // see explanation below

Transform to array
``````````````````

You want to store the query **or** pass it to a webservice?

.. code-block:: php

    $qb = $container->get('spy_timeline.query_builder');
    //...

    $data = $qb->toArray();
    $qb   = $qb->fromArray($data);

Criteria
````````

Criteria will allow you to filter actions on theses fields:

- context
- createdAt: Date of timeline propagation
- verb
- type: Type of component (on ActionComponent table), see examples below
- text: Text on ActionComponent
- model
- identifier


Create criteria
'''''''''''''''

*Example 1) Fetch actions where something kicks something.*

.. code-block:: php

    $criteria = $qb->field('verb')->equals('kick');

*Example 2) Fetch actions where Chuck Norris kicks something.*

.. code-block:: php

    $criteria = $qb->logicalAnd(
        $qb->field('model')->equals('User'),
        $qb->field('identifier')->equals('ChuckNorris'),
        $qb->field('verb')->equals('kick')
    )
    // (component.model = User AND component.identifier = ChuckNorris and actionComponent.verb = kick)

*Example 3) Fetch actions where Chuck Norris is with Bruce Lee.*

.. code-block:: php

    $criteria = $qb->logicalAnd(
        $qb->field('model')->equals('User'),
        $qb->field('identifier')->equals('ChuckNorris'),
        $qb->field('model')->equals('User'),
        $qb->field('identifier')->equals('BruceLee'),
    )
    // but prefer (for readability)
    $criteria = $qb->logicalAnd(
        $qb->logicalAnd(
            $qb->field('model')->equals('User'),
            $qb->field('identifier')->equals('ChuckNorris')
        ),
        $qb->logicalAnd(
            $qb->field('model')->equals('User'),
            $qb->field('identifier')->equals('BruceLee')
        )
    )

*Example 4) Actions where Chuck Norris or Bruce Lee kick something.*

.. code-block:: php

    $criteria = $qb->logicalAnd(
        $qb->logicalOr(
            $qb->logicalAnd(
                $qb->field('model')->equals('User'),
                $qb->field('identifier')->equals('ChuckNorris')
            ),
            $qb->logicalAnd(
                $qb->field('model')->equals('User'),
                $qb->field('identifier')->equals('BruceLee')
            )
        ),
        $qb->field('verb', 'kick')
    )

You can query by each field listed above.

Fields methods:

.. code-block:: php

    $value = 'foo'; // you can provide a \DateTime,
    // for identifier, do not send a serialized data

    $qb->field('createdAt')->equals($value);
    $qb->field('createdAt')->notEquals($value);
    $qb->field('createdAt')->in(array($value));
    $qb->field('createdAt')->notIn(array($value));
    $qb->field('createdAt')->like('%'.$value);
    $qb->field('createdAt')->notLike($value.'%');
    $qb->field('createdAt')->lt($value); // lower than
    $qb->field('createdAt')->lte($value); // lower than equals
    $qb->field('createdAt')->gt($value); // greater than
    $qb->field('createdAt')->gte($value); // greater than equals

Fetch results (available only for ORM Driver)
`````````````````````````````````````````````

.. code-block:: php

    $qb = $container->get('spy_timeline.query_builder');
    $qb->setCriterias('....');

    $results = $qb->execute(Spy\TimelineBundle\Driver\ORM\QueryBuilder\QueryBuilder::APPLY_FILTER); // apply filters
    $results = $qb->execute(Spy\TimelineBundle\Driver\ORM\QueryBuilder\QueryBuilder::NOT_APPLY_FILTER); // not apply filters
    // return a pager of Actions.


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
