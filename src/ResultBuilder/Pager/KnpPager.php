<?php

namespace Spy\Timeline\ResultBuilder\Pager;

use Knp\Component\Pager\Paginator;

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
     * @param Paginator|null $paginator paginator
     */
    public function __construct(Paginator $paginator = null)
    {
        $this->paginator = $paginator;
    }

    /**
     * {@inheritdoc}
     */
    public function paginate($target, $page = 1, $limit = 10)
    {
        if (null === $this->paginator) {
            throw new \LogicException(sprintf('Knp\Component\Pager\Paginator not injected in constructor of %s', __CLASS__));
        }

        $this->page  = $page;
        $this->pager = $this->paginator->paginate($target, $page, $limit, array('distinct' => true));
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
     * @param  array      $items items
     * @throws \Exception
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
