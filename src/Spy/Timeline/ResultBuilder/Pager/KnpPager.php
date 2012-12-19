<?php

namespace Spy\Timeline\ResultBuilder\Pager;

use Knp\Component\Pager\Paginator;
use Spy\Timeline\Filter\FilterManagerInterface;
use Spy\Timeline\ResultBuilder\Pager\PagerInterface;

/**
 * KnpPager
 *
 * @uses PagerInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class KnpPager implements PagerInterface
{
    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * @var FilterManagerInterface
     */
    protected $filterManager;

    /**
     * @param Paginator              $paginator     paginator
     * @param FilterManagerInterface $filterManager filterManager
     */
    public function __construct(Paginator $paginator, FilterManagerInterface $filterManager)
    {
        $this->paginator     = $paginator;
        $this->filterManager = $filterManager;
    }

    /**
     * {@inheritdoc}
     */
    public function paginate($target, $page = 1, $limit = 10, $options = array())
    {
        return $this->paginator->paginate($target, $page, $limit, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function filter($pager)
    {
        return $this->filterManager->filter($pager);
    }
}
