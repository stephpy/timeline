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
}
