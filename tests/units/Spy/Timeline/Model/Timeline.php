<?php

namespace tests\units\Spy\Timeline\Model;

require_once __DIR__.'/../../../../../vendor/autoload.php';

use Spy\Timeline\Model\Timeline as TestedModel;
use Spy\Timeline\Model\TimelineInterface;
use mageekguy\atoum;

class Timeline extends atoum\test
{
    public function testConstruct()
    {
        $this->if($object = new TestedModel())
            ->object($object->getCreatedAt())->isInstanceOf('\DateTime')
            ->string($object->getType())->isEqualTo(TimelineInterface::TYPE_TIMELINE)
        ;
    }
}
