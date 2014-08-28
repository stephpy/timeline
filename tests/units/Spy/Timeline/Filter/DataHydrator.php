<?php

namespace tests\units\Spy\Timeline\Filter;

require_once __DIR__.'/../../../../../vendor/autoload.php';

use Spy\Timeline\Filter\DataHydrator as TestedModel;
use mageekguy\atoum;

class DataHydrator extends atoum\test
{
    public function testFilterEmptyLocators()
    {
        $this->if($filter = new TestedModel())
            ->and($this->mockClass('\Spy\Timeline\Model\ActionInterface', '\Mock'))
            ->and($action1 = new \Mock\ActionInterface())
            ->and($action2 = new \Mock\ActionInterface())
            ->and($coll = array($action1, $action2))
            ->array($filter->filter($coll))->isIdenticalTo($coll)
        ;
    }

    public function testWithLocators()
    {
        $this->if($this->mockClass('\Spy\Timeline\Filter\DataHydrator', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Filter\DataHydrator\Locator\LocatorInterface', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Model\ActionInterface', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Model\ActionComponentInterface', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Model\ComponentInterface', '\Mock'))

            ->and($locator = new \Mock\LocatorInterface())
            ->and($locator->getMockController()->supports = function ($model) {
                return $model == 'man';
            })
            ->and($locator->getMockController()->locate = function ($model, $components) {
                $components['man#chuck']->getMockController()->getData = 'Chuck Norris';
                $components['man#bruce']->getMockController()->getData = 'Bruce Lee';
            })

            // ---- build action ----
            ->and($component1 = new \Mock\ComponentInterface())
            ->and($component1->getMockController()->getModel = 'man')
            ->and($component1->getMockController()->getHash = 'man#chuck')
            ->and($component2 = new \Mock\ComponentInterface())
            ->and($component2->getMockController()->getModel = 'man')
            ->and($component2->getMockController()->getHash = 'man#bruce')
            ->and($component3 = new \Mock\ComponentInterface())
            ->and($component3->getMockController()->getModel = 'monkey')

            ->and($actionComponent1 = new \Mock\ActionComponentInterface())
            ->and($actionComponent1->getMockController()->getComponent = $component1)
            ->and($actionComponent2 = new \Mock\ActionComponentInterface())
            ->and($actionComponent2->getMockController()->getComponent = $component2)
            ->and($actionComponent3 = new \Mock\ActionComponentInterface())
            ->and($actionComponent3->getMockController()->getComponent = $component3)

            ->and($action  = new \Mock\ActionInterface())
            ->and($action->getMockController()->getActionComponents = array($actionComponent1, $actionComponent2, $actionComponent3))
            // ---- end build action ----

            ->and($filter  = new \Mock\DataHydrator())
            ->and($filter->addLocator($locator))
            ->array($filter->filter(array($action)))
                ->hasSize(1)
            ->string($component1->getData())->isEqualTo('Chuck Norris')
            ->string($component2->getData())->isEqualTo('Bruce Lee')
            ->variable($component3->getData())->isNull()

            // --- filter unresolved (monkey is not resolved :( )---
            ->and($filter  = new \Mock\DataHydrator(true))
            ->and($filter->addLocator($locator))
            ->array($filter->filter(array($action)))
                ->hasSize(0)
        ;
    }
}
