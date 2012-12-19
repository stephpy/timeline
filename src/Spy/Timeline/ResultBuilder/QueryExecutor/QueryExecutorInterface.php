<?php

namespace Spy\Timeline\ResultBuilder\QueryExecutor;

/**
 * QueryExecutorInterface
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
interface QueryExecutorInterface
{
    /**
     * @param mixed $query      query
     * @param int   $page       page
     * @param int   $maxPerPage maxPerPage
     *
     * @return \Traversable
     */
    public function fetch($query, $page = 1, $maxPerPage = 10);
}
