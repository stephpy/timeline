<?php

namespace tests\units\Spy\Timeline\Notification\Unread;

require_once __DIR__ . '/../../../../../../vendor/autoload.php';

use mageekguy\atoum;
use Spy\Timeline\Notification\Unread\UnreadNotificationManager as TestedModel;

/**
 * UnreadNotificationManager
 *
 * @uses atoum\test
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class UnreadNotificationManager extends atoum\test
{
    public function testNotify()
    {
        $this->if($this->mockClass('\Spy\Timeline\Driver\TimelineManagerInterface', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Model\ActionInterface', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Model\ComponentInterface', '\Mock'))
            ->and($manager = new \Mock\TimelineManagerInterface())
            ->and($notifier = new TestedModel($manager))
            ->and($action = new \Mock\ActionInterface())
            ->and($component = new \Mock\ComponentInterface())
            ->when($notifier->notify($action, 'GLOBAL', $component))
                ->mock($manager)
                    ->call('createAndPersist')
                    ->withArguments($action, $component, 'GLOBAL', 'notification')
                    ->once()
                ->mock($manager)
                    ->call('flush')
                    ->once()
            ;
    }

    public function testGetUnreadNotifications()
    {
        $this->if($this->mockClass('\Spy\Timeline\Driver\TimelineManagerInterface', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Model\ComponentInterface', '\Mock'))
            ->and($manager = new \Mock\TimelineManagerInterface())
            ->and($notifier = new TestedModel($manager))
            ->and($component = new \Mock\ComponentInterface())
            ->and($options = array('page' => 1))
            ->when($notifier->getUnreadNotifications($component, 'CONTEXT', $options))
                ->mock($manager)
                    ->call('getTimeline')
                    ->withArguments($component, array_merge($options, array('context' => 'CONTEXT', 'type' => 'notification')))
                    ->once()
            ;

    }

    public function testCountKeys()
    {
        $this->if($this->mockClass('\Spy\Timeline\Driver\TimelineManagerInterface', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Model\ComponentInterface', '\Mock'))
            ->and($manager = new \Mock\TimelineManagerInterface())
            ->and($notifier = new TestedModel($manager))
            ->and($component = new \Mock\ComponentInterface())
            ->when($notifier->countKeys($component, 'CONTEXT'))
                ->mock($manager)
                    ->call('countKeys')
                    ->withArguments($component, array('context' => 'CONTEXT', 'type' => 'notification'))
                    ->once()
            ;
    }

    public function testMarkAsReadAction()
    {
        $this->if($this->mockClass('\Spy\Timeline\Model\ComponentInterface', '\Mock'))
            ->and($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockClass('\Spy\Timeline\Notification\Unread\UnreadNotificationManager', '\Mock'))
            ->and($notifier = new \Mock\UnreadNotificationManager())
            ->and($notifier->getMockController()->markAsReadActions = null)
            ->and($component = new \Mock\ComponentInterface())
            ->when($notifier->markAsReadAction($component, 1, 'CONTEXT'))
            ->mock($notifier)
                ->call('markAsReadActions')
                ->withArguments(array(array('CONTEXT', $component, 1)))
                ->once()
            ;
    }

    public function testMarkAsReadActions()
    {
        $this->if($this->mockClass('\Spy\Timeline\Driver\TimelineManagerInterface', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Model\ComponentInterface', '\Mock'))
            ->and($manager = new \Mock\TimelineManagerInterface())
            ->and($notifier = new TestedModel($manager))
            ->and($component = new \Mock\ComponentInterface())
            ->and($component2 = new \Mock\ComponentInterface())
            ->when($notifier->markAsReadActions(array(
                array('CONTEXT', $component, 1),
                array('CONTEXT', $component2, 2)
            )))
            ->mock($manager)
                ->call('remove')
                    ->withArguments($component, '1', array('type' =>  'notification', 'context' => 'CONTEXT'))
                ->once()
                ->call('remove')
                    ->withArguments($component2, '2', array('type' =>  'notification', 'context' => 'CONTEXT'))
                ->once()
                ->call('flush')
                ->once()
            ;
    }

    public function testMarkAllAsRead()
    {
        $this->if($this->mockClass('\Spy\Timeline\Driver\TimelineManagerInterface', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Model\ComponentInterface', '\Mock'))
            ->and($manager = new \Mock\TimelineManagerInterface())
            ->and($notifier = new TestedModel($manager))
            ->and($component = new \Mock\ComponentInterface())
            ->when($notifier->markAllAsRead($component, 'CONTEXT'))
                ->mock($manager)
                    ->call('removeAll')
                        ->withArguments($component, array('context' => 'CONTEXT', 'type' => 'notification'))
                    ->once()
                    ->call('flush')
                    ->once()
            ;

    }
}
