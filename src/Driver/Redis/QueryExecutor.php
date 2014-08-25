<?php

namespace Spy\Timeline\Driver\Redis;

use Spy\Timeline\ResultBuilder\QueryExecutor\QueryExecutorInterface;
use Spy\Timeline\Driver\Redis\Pager\PagerToken;
use Spy\Timeline\Driver\Redis\Pager\AbstractPager;

/**
 * QueryExecutor
 *
 * @uses AbstractPager
 * @uses QueryExecutorInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class QueryExecutor extends AbstractPager implements QueryExecutorInterface
{
    /**
     * @param mixed $query      query
     * @param int   $page       page
     * @param int   $maxPerPage maxPerPage
     *
     * @return \Traversable
     */
    public function fetch($query, $page = 1, $maxPerPage = 10)
    {
        if (!$query instanceof PagerToken) {
            throw new \Exception('Not supported, must give a PagerToken');
        }

        $offset = ($page - 1) * $maxPerPage;
        $maxPerPage  = $maxPerPage - 1; // due to redis

        $ids    = $this->client->zRevRange($query->key, $offset, ($offset + $maxPerPage));

        return $this->findActionsForIds($ids);
    }
}
