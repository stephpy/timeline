<?php

namespace tests\units\Spy\Timeline\Spread;

require_once __DIR__ . '/../../../../../vendor/autoload.php';

use mageekguy\atoum;
use Spy\Timeline\Model\ActionInterface;
use Spy\Timeline\Model\TimelineInterface;
use Spy\Timeline\Spread\Deployer as TestedModel;

/**
 * Deployer
 *
 * @uses atoum\test
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Deployer extends atoum\test
{
    public function testDeployUnpublishedAction()
    {
        $this->if($this->mockClass('\Spy\Timeline\Driver\TimelineManagerInterface', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Spread\Entry\EntryCollection', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Driver\ActionManagerInterface', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Model\ActionInterface', '\Mock'))
            ->and($entryCollection = new \Mock\EntryCollection())
            ->and($action = new \Mock\ActionInterface())
            ->and($action->getMockController()->getStatusWanted = 'notpublished')
            ->and($deployer = new TestedModel(new \Mock\TimelineManagerInterface(), $entryCollection))
            ->when($deployer->deploy($action, new \Mock\ActionManagerInterface()))
                ->mock($entryCollection)
                    ->call('setActionManager')
                ->never();
            ;
    }

    public function testDeploy()
    {
        $this->if($this->mockClass('\Spy\Timeline\Driver\TimelineManagerInterface', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Spread\Entry\EntryCollection', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Spread\Entry\Entry', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Driver\ActionManagerInterface', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Notification\NotificationManagerInterface', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Model\ActionInterface', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Model\ComponentInterface', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Spread\Deployer', '\Mock'))
            // ---- notification ----
            ->and($notifManager = new \Mock\NotificationManagerInterface())
            ->and($notifManager->getMockController()->notify = null)
            // ---- entries ----
            ->and($component = new \Mock\ComponentInterface())
            ->and($entry = new \Mock\Entry($component))
            ->and($entryCollection = new \Mock\EntryCollection())
            ->and($entryCollection->getMockController()->getEntries = new \ArrayIterator(array('CONTEXT' => array($entry))))
            ->and($entryCollection->getMockController()->loadUnawareEntries = null)
            // ---- managers ----
            ->and($timelineManager = new \Mock\TimelineManagerInterface())
            ->and($actionManager = new \Mock\ActionManagerInterface())
            // ---- action ----
            ->and($action = new \Mock\ActionInterface())
            ->and($action->getMockController()->getStatusWanted = ActionInterface::STATUS_PUBLISHED)
            // ---- deployer ----
            ->and($deployer = new \Mock\Deployer($timelineManager, $entryCollection))
            ->and($deployer->getMockController()->processSpreads     = $entryCollection)
            ->and($deployer->setNotificationManager($notifManager))
            ->when($deployer->deploy($action, $actionManager))
                ->mock($timelineManager)->call('createAndPersist')->withArguments($action, $component, 'CONTEXT', TimelineInterface::TYPE_TIMELINE)->once()
                ->mock($notifManager)->call('notify')->withArguments($action, 'CONTEXT', $component)->once()
                ->mock($timelineManager)->call('flush')->once()
                ->mock($action)->call('setStatusWanted')->withArguments(ActionInterface::STATUS_FROZEN)->once()
                ->mock($action)->call('setStatusCurrent')->withArguments(ActionInterface::STATUS_PUBLISHED)->once()
                ->mock($actionManager)->call('updateAction')->withArguments($action)->once()
                ->mock($entryCollection)->call('clear')->once()
            ;
    }

    public function testSetDelivery()
    {
        $this->if($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockClass('\Spy\Timeline\Spread\Deployer', '\Mock'))
            ->and($deployer = new \Mock\Deployer())
            ->exception(function () use ($deployer) {
                $deployer->setDelivery('unknown');
            })
            ->isInstanceOf('\InvalidArgumentException')
            ->hasMessage('Delivery "unknown" is not supported, (immediate, wait)')
            // now ok
            ->and($deployer->setDelivery(TestedModel::DELIVERY_IMMEDIATE))
            ->boolean($deployer->isDeliveryImmediate())->isTrue()
            ->and($deployer->setDelivery(TestedModel::DELIVERY_WAIT))
            ->boolean($deployer->isDeliveryImmediate())->isFalse()
            ;
    }

    public function testIsDeliveryImmediate()
    {
        $this->if($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockClass('\Spy\Timeline\Spread\Deployer', '\Mock'))
            ->and($deployer = new \Mock\Deployer())
            ->and($deployer->setDelivery(TestedModel::DELIVERY_IMMEDIATE))
            ->boolean($deployer->isDeliveryImmediate())->isTrue()
            ->and($deployer->setDelivery(TestedModel::DELIVERY_WAIT))
            ->boolean($deployer->isDeliveryImmediate())->isFalse()
            ;
    }

    public function testProcessSpreads()
    {
        $this->if($this->mockClass('\Spy\Timeline\Driver\TimelineManagerInterface', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Spread\Entry\EntryCollection', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Spread\SpreadInterface', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Model\ActionInterface', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Model\ComponentInterface', '\Mock'))

            ->and($timelineManager = new \Mock\TimelineManagerInterface())
            ->and($entryCollection = new \Mock\EntryCollection())
            ->and($entryCollection->getMockController()->add = null)

            // -- action --
            ->and($component = new \Mock\ComponentInterface())
            ->and($action = new \Mock\ActionInterface())
            ->and($action->getMockController()->getSubject = $component)

            // -- spreads --
            ->and($spread1 = new \Mock\SpreadInterface())
            ->and($spread1->getMockController()->supports = true)
            ->and($spread1->getMockController()->process = null)
            ->and($spread2 = new \Mock\SpreadInterface())
            ->and($spread2->getMockController()->supports = false)

            ->and($deployer = new TestedModel($timelineManager, $entryCollection, true, 50))
            ->and($deployer->addSpread($spread1))
            ->and($deployer->addSpread($spread2))

            ->object($deployer->processSpreads($action))->isEqualTo($entryCollection)
            ->mock($entryCollection)
                ->call('add')->once()
            ->mock($spread1)
                ->call('supports')->once()
                ->call('process')->once()
            ->mock($spread2)
                ->call('supports')->once()
                ->call('process')->never()
            ;
    }
}
