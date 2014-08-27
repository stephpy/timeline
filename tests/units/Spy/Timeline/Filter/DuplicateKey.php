<?php

namespace tests\units\Spy\Timeline\Filter;

require_once __DIR__.'/../../../../../vendor/autoload.php';

use Spy\Timeline\Filter\DuplicateKey as TestedModel;
use mageekguy\atoum;

class DuplicateKey extends atoum\test
{
    public function testFilterNoDuplicateKey()
    {
        $this->if($filter = new TestedModel())
            ->and($this->mockClass('\Spy\Timeline\Model\ActionInterface', '\Mock'))
            ->and($action1 = new \Mock\ActionInterface())
            ->and($action2 = new \Mock\ActionInterface())
            ->and($coll = array($action1, $action2))
            ->array($filter->filter($coll))
                ->hasSize(2)
                ->isIdenticalTo($coll)
            ->mock($action1)
                ->call('setIsDuplicated')
                ->never()
            ->mock($action2)
                ->call('setIsDuplicated')
                ->never()
        ;
    }

    public function testFilterOneDuplicateKey()
    {
        $this->if($filter = new TestedModel())
            ->and($this->mockClass('\Spy\Timeline\Model\ActionInterface', '\Mock'))
            ->and($action1 = new \Mock\ActionInterface())
            ->and($action1->getMockController()->hasDuplicateKey = true)
            ->and($action1->getMockController()->getDuplicateKey = '123')
            ->and($action2 = new \Mock\ActionInterface())
            ->and($coll = array($action1, $action2))
            ->array($filter->filter($coll))
                ->hasSize(2)
                ->isIdenticalTo($coll)
            ->mock($action1)
                ->call('setIsDuplicated')
                ->never()
            ->mock($action2)
                ->call('setIsDuplicated')
                ->never()
        ;
    }

    public function testFilterTwoDuplicateKeyDifferent()
    {
        $this->if($filter = new TestedModel())
            ->and($this->mockClass('\Spy\Timeline\Model\ActionInterface', '\Mock'))
            ->and($action1 = new \Mock\ActionInterface())
            ->and($action1->getMockController()->hasDuplicateKey = true)
            ->and($action1->getMockController()->getDuplicateKey = '123')
            ->and($action2 = new \Mock\ActionInterface())
            ->and($action2->getMockController()->hasDuplicateKey = true)
            ->and($action2->getMockController()->getDuplicateKey = '456')
            ->and($coll = array($action1, $action2))
            ->array($filter->filter($coll))
                ->hasSize(2)
                ->isIdenticalTo($coll)
            ->mock($action1)
                ->call('setIsDuplicated')
                ->never()
            ->mock($action2)
                ->call('setIsDuplicated')
                ->never()
        ;
    }

    public function testFilterTwoDuplicateKeyNoPriority()
    {
        $this->if($filter = new TestedModel())
            ->and($this->mockClass('\Spy\Timeline\Model\ActionInterface', '\Mock'))
            ->and($action1 = new \Mock\ActionInterface())
            ->and($action1->getMockController()->hasDuplicateKey = true)
            ->and($action1->getMockController()->getDuplicateKey = '123')
            ->and($action2 = new \Mock\ActionInterface())
            ->and($action2->getMockController()->hasDuplicateKey = true)
            ->and($action2->getMockController()->getDuplicateKey = '123')
            ->and($coll = array($action1, $action2))
            ->array($filter->filter($coll))
                ->hasSize(1)
                ->isIdenticalTo(array($action1))
            ->mock($action1)
                ->call('setIsDuplicated')
                ->once()
            ->mock($action2)
                ->call('setIsDuplicated')
                ->never()
        ;
    }

    public function testFilterTwoDuplicateKeyPriorityEquals()
    {
        $this->if($filter = new TestedModel())
            ->and($this->mockClass('\Spy\Timeline\Model\ActionInterface', '\Mock'))
            ->and($action1 = new \Mock\ActionInterface())
            ->and($action1->getMockController()->hasDuplicateKey = true)
            ->and($action1->getMockController()->getDuplicateKey = '123')
            ->and($action1->getMockController()->getDuplicatePriority = 10)
            ->and($action2 = new \Mock\ActionInterface())
            ->and($action2->getMockController()->hasDuplicateKey = true)
            ->and($action2->getMockController()->getDuplicateKey = '123')
            ->and($action2->getMockController()->getDuplicatePriority = 10)
            ->and($coll = array($action1, $action2))
            ->array($filter->filter($coll))
                ->hasSize(1)
                ->isIdenticalTo(array($action1))
            ->mock($action1)
                ->call('setIsDuplicated')
                ->once()
            ->mock($action2)
                ->call('setIsDuplicated')
                ->never()
        ;
    }

    public function testFilterTwoDuplicateKeyPriorityFirst()
    {
        $this->if($filter = new TestedModel())
            ->and($this->mockClass('\Spy\Timeline\Model\ActionInterface', '\Mock'))
            ->and($action1 = new \Mock\ActionInterface())
            ->and($action1->getMockController()->hasDuplicateKey = true)
            ->and($action1->getMockController()->getDuplicateKey = '123')
            ->and($action1->getMockController()->getDuplicatePriority = 20)
            ->and($action2 = new \Mock\ActionInterface())
            ->and($action2->getMockController()->hasDuplicateKey = true)
            ->and($action2->getMockController()->getDuplicateKey = '123')
            ->and($action2->getMockController()->getDuplicatePriority = 10)
            ->and($coll = array($action1, $action2))
            ->array($filter->filter($coll))
                ->hasSize(1)
                ->isIdenticalTo(array($action1))
            ->mock($action1)
                ->call('setIsDuplicated')
                ->once()
            ->mock($action2)
                ->call('setIsDuplicated')
                ->never()
        ;
    }

    public function testFilterTwoDuplicateKeyPrioritySecond()
    {
        $this->if($filter = new TestedModel())
            ->and($this->mockClass('\Spy\Timeline\Model\ActionInterface', '\Mock'))
            ->and($action1 = new \Mock\ActionInterface())
            ->and($action1->getMockController()->hasDuplicateKey = true)
            ->and($action1->getMockController()->getDuplicateKey = '123')
            ->and($action1->getMockController()->getDuplicatePriority = 10)
            ->and($action2 = new \Mock\ActionInterface())
            ->and($action2->getMockController()->hasDuplicateKey = true)
            ->and($action2->getMockController()->getDuplicateKey = '123')
            ->and($action2->getMockController()->getDuplicatePriority = 20)
            ->and($coll = array($action1, $action2))
            ->array($filter->filter($coll))
                ->hasSize(1)
                ->isIdenticalTo(array(1 => $action2))
            ->mock($action1)
                ->call('setIsDuplicated')
                ->never()
            ->mock($action2)
                ->call('setIsDuplicated')
                ->once()
        ;
    }
}
