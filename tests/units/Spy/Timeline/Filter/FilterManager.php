<?php

namespace tests\units\Spy\Timeline\Filter;

require_once __DIR__.'/../../../../../vendor/autoload.php';

use Spy\Timeline\Filter\FilterManager as TestedFilterManager;
use mageekguy\atoum;

class FilterManager extends atoum\test
{
    public function testException()
    {
        $this->if($manager = new TestedFilterManager())
            ->exception(function () use ($manager) {
                $manager->filter('scalar');
            })
            ->isInstanceof('\Exception')
            ->hasMessage('Collection must be an array or traversable')
        ;
    }

    public function testFilters()
    {
        $this->mockClass('Spy\Timeline\Filter\FilterInterface', '\Mock');

        $this->if($manager = new TestedFilterManager())
            ->and($filter1 = new \Mock\FilterInterface())
            ->and($filter1->getMockController()->getPriority = 50)
            ->and($filter1->getMockController()->filter = function ($collection) { $collection[] = 1; return $collection; })
            ->and($manager->add($filter1))
            ->and($filter2 = new \Mock\FilterInterface())
            ->and($filter2->getMockController()->getPriority = 20)
            ->and($filter2->getMockController()->filter = function ($collection) { $collection[] = 2; return $collection; })
            ->and($manager->add($filter2))
                ->array($manager->filter(array()))
                ->isIdenticalTo(array(2, 1))
            // change property of filter ...
            ->and($filter2->getMockController()->getPriority = 60)
            // not change because not re sorted.
            ->array($manager->filter(array()))
                ->isIdenticalTo(array(2, 1))
            // add a filter
            ->and($filter3 = new \Mock\FilterInterface())
            ->and($filter3->getMockController()->getPriority = -20)
            ->and($filter3->getMockController()->filter = function ($collection) { $collection[] = 3; return $collection; })
            ->and($manager->add($filter3))
            ->array($manager->filter(array()))
                ->isIdenticalTo(array(3, 1, 2))
        ;
    }
}
