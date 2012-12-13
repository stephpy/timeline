<?php

namespace tests\units\Spy\Timeline\Notification;

require_once __DIR__ . '/../../../../../vendor/autoload.php';

use mageekguy\atoum;
use Spy\Timeline\Notification\NotificationManager as TestedModel;

/**
 * NotificationManager
 *
 * @uses atoum\test
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class NotificationManager extends atoum\test
{
    public function testNotify()
    {
        $this->if($tested = new TestedModel())
            ->and($this->mockClass('Spy\Timeline\Model\ActionInterface', '\Mock'))
            ->and($this->mockClass('Spy\Timeline\Model\ComponentInterface', '\Mock'))
            ->and($this->mockClass('Spy\Timeline\Notification\Notifier\NotifierInterface', '\Mock'))
            ->and($action    = new \Mock\ActionInterface())
            ->and($component = new \Mock\ComponentInterface())
            ->and($context   = 'context')
            // add notifier 1
            ->and($notifier = new \Mock\NotifierInterface())
            ->and($tested->addNotifier($notifier))
            // add two time notifier2.
            ->and($notifier2 = new \Mock\NotifierInterface())
            ->and($tested->addNotifier($notifier2))
            ->and($tested->addNotifier($notifier2))
            // notify
            ->when($tested->notify($action, $context, $component))
            ->mock($notifier)
                ->call('notify')
                ->withArguments($action, $context, $component)
                ->once()
            ->mock($notifier2)
                ->call('notify')
                ->withArguments($action, $context, $component)
                ->exactly(2)
            ;
    }
}
