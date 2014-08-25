<?php

namespace Spy\Timeline\ResultBuilder\QueryExecutor;

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
