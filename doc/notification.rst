Notification
------------

During the deployment of an action, you can define some notifiers, they must implements **NotifierInterface**

UnreadNotification
``````````````````

UnreadNotification is already provided on this library. All actions spreads on your timeline will be stored and you'll
be able to mark them as read or retrieve unread notifications.

How to use it ?

.. code-block:: php

    // enable it (should be done at initialization of your project).
    $c['notification_manager']->addNotifier($c['unread_notifications']);

    // methods
    $actionManager = $c['action_manager'];
    $subject       = $actionManager->findOrCreateComponent('User', 'ChuckNorris');

    $unread        = $c['unread_notifications'];
    //count how many unread message for global context
    $count  = $unread->countKeys($subject); // on global context
    $count  = $unread->countKeys($subject, 'MyContext');

    // remove ONE unread notification
    $unread->markAsReadTimelineAction($subject, 'TimelineActionId'); // on global context
    $unread->markAsReadTimelineAction($subject, 'TimelineActionId', 'MyContext');

    // remove several unread notifications
    $unread->markAsReadTimelineActions(array(
        array('GLOBAL', $subject, 'TimelineActionId'),
        array('GLOBAL', $subject, 'TimelineActionId'),
        ...
    ));

    // all unread notifications
    $unread->markAllAsRead($subject); // on global context
    $unread->markAllAsRead($subject, 'MyContext');

    // retrieve timeline actions
    $actions = $unread->getUnreadNotifications($subject); // on global context, no options
    $actions = $unread->getUnreadNotifications($subject, 'MyContext', $options);
    // in options you can define offset, limit, etc ...

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
