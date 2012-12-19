<?php

namespace Spy\Timeline\Driver\Redis\Pager;

use Spy\Timeline\Filter\FilterManagerInterface;
use Spy\Timeline\ResultBuilder\Pager\PagerInterface;

/**
 * Pager
 *
 * @uses AbstractPager
 * @uses PagerInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Pager extends AbstractPager implements PagerInterface, \IteratorAggregate, \Countable
{
    /**
     * {@inheritdoc}
     */
    public function paginate($target, $page = 1, $limit = 10, $options = array())
    {
        if (!$target instanceof PagerToken) {
            throw new \Exception('Not supported, must give a PagerToken');
        }

        $offset = ($page - 1) * $limit;
        $limit  = $limit - 1; // due to redis

        $ids    = $this->client->zRevRange($target->key, $offset, ($offset + $limit));

        $this->items     = $this->findActionsForIds($ids);
        $this->nbResults = $this->client->zCard($target->key);
        $this->lastPage  = intval(ceil($this->nbResults / ($limit + 1)));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastPage()
    {
        return $this->lastPage;
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
        return $this->nbResults;
    }

    /**
     * @param array $items items
     */
    public function setItems(array $items)
    {
        $this->items = $items;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * @return integer
     */
    public function count()
    {
        return count($this->items);
    }
}
