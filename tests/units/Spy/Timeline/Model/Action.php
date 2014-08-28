<?php

namespace tests\units\Spy\Timeline\Model;

require_once __DIR__.'/../../../../../vendor/autoload.php';

use Spy\Timeline\Model\Action as TestedModel;
use Spy\Timeline\Model\ActionInterface;
use mageekguy\atoum;

class Action extends atoum\test
{
    public function testConstruct()
    {
        $this->if($object = new TestedModel())
            ->object($object->getCreatedAt())->isInstanceOf('\DateTime')
            ->boolean($object->isDuplicated())->isFalse()
            ->array($object->getActionComponents())->isEmpty()
            ->string($object->getStatusCurrent())->isEqualTo(ActionInterface::STATUS_PENDING)
            ->string($object->getStatusWanted())->isEqualTo(ActionInterface::STATUS_PUBLISHED)
        ;
    }

    public function testAddComponent()
    {
        $this->if($action = new TestedModel())
            ->exception(function () use ($action) {
                $action->addComponent('subject', new \stdClass(), '\Spy\Timeline\Model\ActionComponent');
            })
            ->isInstanceOf('\InvalidArgumentException')
            ->hasMessage('Component has to be a ComponentInterface or a scalar')
            // scalar
            ->when($action->addComponent('cod', 'chuckNorris', '\Spy\Timeline\Model\ActionComponent'))
            ->string($action->getComponent('cod'))->isEqualTo('chuckNorris')
            // componentInterface
            ->if($this->mockClass('\Spy\Timeline\Model\ComponentInterface', '\Mock'))
            ->and($component = new \Mock\ComponentInterface())
            ->when($action->addComponent('coi', $component, '\Spy\Timeline\Model\ActionComponent'))
            ->object($action->getComponent('coi'))->isIdenticalTo($component)
            // two times same componen
            ->when($action->addComponent('coi', 'text', '\Spy\Timeline\Model\ActionComponent'))
            ->integer(count($action->getActionComponents()))->isEqualTo(2)
        ;
    }

    public function testIsPublished()
    {
        $this->if($action = new TestedModel())
            ->boolean($action->isPublished())->isFalse()
            ->when($action->setStatusCurrent(TestedModel::STATUS_PUBLISHED))
            ->boolean($action->isPublished())->isTrue()
        ;
    }

    public function testHasDuplicateKey()
    {
        $this->if($action = new TestedModel())
            ->boolean($action->hasDuplicateKey())->isFalse()
            ->when($action->setDuplicateKey(uniqid()))
            ->boolean($action->hasDuplicateKey())->isTrue()
        ;
    }

    public function testGetValidStatus()
    {
        $this->if($object = new TestedModel())
            ->array($object->getValidStatus())
            ->isNotEmpty()
        ;
    }

    public function testIsValidStatus()
    {
        $this->if($object = new TestedModel())
            ->boolean($object->isValidStatus(TestedModel::STATUS_PENDING))->isTrue()
            ->boolean($object->isValidStatus(TestedModel::STATUS_PUBLISHED))->isTrue()
            ->boolean($object->isValidStatus(TestedModel::STATUS_FROZEN))->isTrue()
            ->boolean($object->isValidStatus('custom_status'))->isFalse()
        ;
    }

    public function testGetComponent()
    {
        // this is almost the same test than addComponent
        $this->if($action = new TestedModel())
            ->variable($action->getComponent('complement'))->isNull()
            // scalar
            ->when($action->addComponent('cod', 'chuckNorris', '\Spy\Timeline\Model\ActionComponent'))
            ->string($action->getComponent('cod'))->isEqualTo('chuckNorris')
            // componentInterface
            ->if($this->mockClass('\Spy\Timeline\Model\ComponentInterface', '\Mock'))
            ->and($component = new \Mock\ComponentInterface())
            ->when($action->addComponent('coi', $component, '\Spy\Timeline\Model\ActionComponent'))
            ->object($action->getComponent('coi'))->isIdenticalTo($component)
        ;
    }

    public function testGetSubject()
    {
        // this is almost the same test than getComponent
        $this->if($action = new TestedModel())
            ->variable($action->getComponent('subject'))->isNull()
            // scalar
            ->when($action->addComponent('subject', 'chuckNorris', '\Spy\Timeline\Model\ActionComponent'))
            ->string($action->getComponent('subject'))->isEqualTo('chuckNorris')
            // componentInterface
            ->if($this->mockClass('\Spy\Timeline\Model\ComponentInterface', '\Mock'))
            ->and($component = new \Mock\ComponentInterface())
            ->when($action->addComponent('subject', $component, '\Spy\Timeline\Model\ActionComponent'))
            ->object($action->getComponent('subject'))->isIdenticalTo($component)
        ;
    }
}
