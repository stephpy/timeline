<?php

namespace tests\units\Spy\Timeline\Model;

require_once __DIR__ . '/../../../../../vendor/autoload.php';

use mageekguy\atoum;
use Spy\Timeline\Model\Action as TestedModel;
use Spy\Timeline\Model\ActionInterface;

/**
 * Action
 *
 * @uses atoum\test
 * @author Stephane PY <py.stephane1@gmail.com>
 */
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
    }

    public function testIsPublished()
    {
    }

    public function testHasDuplicateKey()
    {
    }

    public function testGetValidStatus()
    {
        $this->if($object = new TestedModel())
            ->array($object->getValidStatus())
            ->isNotEmpty();
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
    }

    public function testGetSubject()
    {
    }
}
