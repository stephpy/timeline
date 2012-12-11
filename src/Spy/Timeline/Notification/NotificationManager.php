<?php

namespace Spy\Timeline\Notification;

use Spy\Timeline\Model\ActionInterface;
use Spy\Timeline\Model\ComponentInterface;
use Spy\Timeline\Notification\Notifier\NotifierInterface;

/**
 * NotificationManager
 *
 * @uses NotificationManagerInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class NotificationManager implements NotificationManagerInterface
{
    /**
     * @var array
     */
    private $notifiers = array();

    /**
     * {@inheritdoc}
     */
    public function addNotifier(NotifierInterface $notifier)
    {
        $this->notifiers[] = $notifier;
    }

    /**
     * {@inheritdoc}
     */
    public function notify(ActionInterface $action, $context, ComponentInterface $subject)
    {
        foreach ($this->notifiers as $notifier) {
            $notifier->notify($action, $context, $subject);
        }
    }
}
