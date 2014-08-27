<?php

namespace tests\units\Spy\Timeline\Filter\DataHydrator;

require_once __DIR__.'/../../../../../../vendor/autoload.php';

use Spy\Timeline\Filter\DataHydrator\Entry as TestedModel;
use mageekguy\atoum;

class Entry extends atoum\test
{
    public function testBuild()
    {
        $this->if($this->mockClass('\Spy\Timeline\Model\ActionInterface', '\Mock'))
            ->if($this->mockClass('\Spy\Timeline\Model\ActionComponentInterface', '\Mock'))
            ->if($this->mockClass('\Spy\Timeline\Model\ComponentInterface', '\Mock'))
            ->and($component1 = new \Mock\ComponentInterface())
            ->and($component1->getMockController()->getHash = 'hashOA')
            ->and($component2 = new \Mock\ComponentInterface())
            ->and($component2->getMockController()->getHash = 'hashoir')
            // data already set
            ->and($component3 = new \Mock\ComponentInterface())
            ->and($component3->getMockController()->getData = 'already set')
            // action components
            ->and($actionComponent1 = new \Mock\ActionComponentInterface())
            ->and($actionComponent1->getMockController()->getComponent = $component1)
            ->and($actionComponent2 = new \Mock\ActionComponentInterface())
            ->and($actionComponent2->getMockController()->getComponent = $component2)
            ->and($actionComponent3 = new \Mock\ActionComponentInterface())
            ->and($actionComponent3->getMockController()->getComponent = $component2)
            // action
            ->and($action = new \Mock\ActionInterface())
            ->and($action->getMockController()->getActionComponents = array($actionComponent1, $actionComponent2, $actionComponent3))
            // now ... let's play !
            ->and($entry = new TestedModel($action, 'key'))
            ->when($entry->build())
            ->array($entry->getComponents())
            ->isIdenticalTo(array(
                'hashOA' => $component1,
                'hashoir' => $component2,
                // not third because has already data setted.
            ))
        ;
    }
}
