<?php

namespace Spy\Timeline\ResultBuilder;

use Spy\Timeline\Filter\FilterManagerInterface;
use Spy\Timeline\ResultBuilder\QueryExecutor\QueryExecutorInterface;
use Spy\Timeline\ResultBuilder\Pager\PagerInterface;

/**
 * ResultBuilder
 *
 * @uses ResultBuilderInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class ResultBuilder implements ResultBuilderInterface
{
    /**
     * @var FilterManagerInterface
     */
    protected $filterManager;

    /**
     * @var QueryExecutorInterface
     */
    protected $queryExecutor;

    /**
     * @var PagerInterface
     */
    protected $pager;

    public function __construct(QueryExecutorInterface $queryExecutor, FilterManagerInterface $filterManager)
    {
        $this->queryExecutor = $queryExecutor;
        $this->filterManager = $filterManager;
    }

    /**
     * @param PagerInterface $pager pager
     */
    public function setPager(PagerInterface $pager)
    {
        $this->pager = $pager;
    }

    /**
     * @param mixed   $target     target
     * @param int     $page       page
     * @param int     $maxPerPage maxPerPage
     * @param boolean $filter     filter
     * @param boolean $paginate   paginate
     *
     * @return \Traversable
     */
    public function fetchResults($query, $page = 1, $maxPerPage = 10, $filter = false, $paginate = false)
    {
        if ($paginate) {
            if (!$this->pager) {
                throw new \Exception('Please inject a pager on ResultBuilder');
            }

            $results = $this->pager->paginate($query, $page, $maxPerPage);
        } else {
            $results = $this->queryExecutor->fetch($query, $page, $maxPerPage);
        }

        if ($filter) {
            return $this->filterManager->filter($results);
        }

        return $results;
    }
}
