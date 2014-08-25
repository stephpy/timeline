<?php

namespace tests\units\Spy\Timeline\Model;

require_once __DIR__ . '/../../../../../vendor/autoload.php';

use mageekguy\atoum;
use Spy\Timeline\Model\ActionComponent as TestedModel;

class ActionComponent extends atoum\test
{
    public function testIsText()
    {
        $this->if($object = new TestedModel())
            ->boolean($object->isText())->isFalse()
            ->and($object->setText('text'))
            ->boolean($object->isText())->isTrue()
        ;
    }
}
