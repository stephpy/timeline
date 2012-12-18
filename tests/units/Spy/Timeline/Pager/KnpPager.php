<?php

namespace tests\units\Spy\Timeline\Pager;

require_once __DIR__ . '/../../../../../vendor/autoload.php';

use mageekguy\atoum;
use Spy\Timeline\Pager\KnpPager as TestedModel;

/**
 * KnpPager
 *
 * @uses atoum\test
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class KnpPager extends atoum\test
{
    public function testPaginate()
    {
        $this->if($this->mockClass('\Knp\Component\Pager\Paginator', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Filter\FilterManagerInterface', '\Mock'))
            ->and($paginator     = new \Mock\Paginator())
            ->and($paginator->getMockController()->paginate = null)
            ->and($filterManager = new \Mock\FilterManagerInterface())
            ->and($pager = new TestedModel($paginator, $filterManager))
            ->when($pager->paginate('target', 1, 10, array('options')))
            ->mock($paginator)
                ->call('paginate')
                ->withArguments('target', 1, 10, array('options'))
                ->once()
            ;
    }

    public function testFilter()
    {
        $this->if($this->mockClass('\Knp\Component\Pager\Paginator', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Filter\FilterManagerInterface', '\Mock'))
            ->and($paginator     = new \Mock\Paginator())
            ->and($filterManager = new \Mock\FilterManagerInterface())
            ->and($filterManager->getMockController()->filter = null)
            ->and($pager = new TestedModel($paginator, $filterManager))
            ->when($pager->filter('pager'))
            ->mock($filterManager)
                ->call('filter')
                ->withArguments('pager')
                ->once()
            ;
    }
}
