<?php

namespace Spy\Timeline\ResultBuilder\Pager;

use Knp\Component\Pager\Paginator;
use Spy\Timeline\ResultBuilder\Pager\PagerInterface;

/**
 * KnpPager
 *
 * @uses AbstractPager
 * @uses PagerInterface
 * @uses \IteratorAggregate
 * @uses \Countable
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class KnpPager extends AbstractPager implements PagerInterface, \IteratorAggregate, \Countable
{
    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * @var integer
     */
    protected $page;

    /**
     * @var array
     */
    protected $data;

    /**
     * @param Paginator $paginator paginator
     */
    public function __construct(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * {@inheritdoc}
     */
    public function paginate($target, $page = 1, $limit = 10)
    {
        $this->page  = $page;
        $this->pager = $this->paginator->paginate($target, $page, $limit);
        $this->data  = $this->pager->getPaginationData();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastPage()
    {
        return $this->data['last'];
    }

    /**
     * {@inheritdoc}
     */
    public function haveToPaginate()
    {
        return $this->getLastPage() > 1;
    }

    /**
     * {@inheritdoc}
     */
    public function getNbResults()
    {
        return $this->data['totalCount'];
    }

    /**
     * @param array $items items
     */
    public function setItems(array $items)
    {
        if (!$this->pager) {
            throw new \Exception('Paginate before set items');
        }

        $this->pager->setItems($items);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return $this->pager;
    }

    /**
     * @return integer
     */
    public function count()
    {
        return $this->data['currentItemCount'];
    }
}
