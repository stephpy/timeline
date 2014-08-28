<?php

namespace tests\units\Spy\Timeline\ResultBuilder\Pager;

require_once __DIR__.'/../../../../../../vendor/autoload.php';

use Spy\Timeline\ResultBuilder\Pager\KnpPager as TestedModel;
use mageekguy\atoum;

class KnpPager extends atoum\test
{
    public function testPaginate()
    {
        $this->if($this->mockClass('\Knp\Component\Pager\Paginator', '\Mock'))
            ->and($this->mockClass('\Knp\Component\Pager\Pagination\SlidingPagination', '\Mock'))
            ->and($this->mockClass('\Spy\Timeline\Filter\FilterManagerInterface', '\Mock'))
            ->and($pagination = new \Mock\SlidingPagination())
            ->and($pagination->getMockController()->getPaginationData = array(
                'last' => 2,
                'totalCount' => 17,
                'currentItemCount' => 10,
            ))
            ->and($paginator  = new \Mock\Paginator())
            ->and($paginator->getMockController()->paginate = $pagination)
            ->and($pager = new TestedModel($paginator))
            ->when($pagination = $pager->paginate('target', 1, 10))
            ->mock($paginator)
                ->call('paginate')
                ->withArguments('target', 1, 10)
                ->once()
            ->integer($pagination->getLastPage())->isEqualTo(2)
            ->boolean($pagination->haveToPaginate())->isTrue()
            ->integer($pagination->getNbResults())->isEqualTo(17)
            ->integer(count($pagination))->isEqualTo(10)
        ;
    }
}
