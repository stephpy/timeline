<?php

namespace Spy\Timeline\ResultBuilder;

/**
 * ResultBuilderInterface
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
interface ResultBuilderInterface
{
    /**
     * @param mixed   $target     target
     * @param int     $page       page
     * @param int     $maxPerPage maxPerPage
     * @param boolean $filter     filter
     * @param boolean $paginate   paginate
     *
     * @return \Traversable
     */
    public function fetchResults($target, $page = 1, $maxPerPage = 10, $filter = false, $paginate = false);
}
