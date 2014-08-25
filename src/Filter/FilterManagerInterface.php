<?php

namespace Spy\Timeline\Filter;

/**
 * @author Stephane PY <py.stephane1@gmail.com>
 */
interface FilterManagerInterface
{
    /**
     * @param array $collection collection
     *
     * @return array
     */
    public function filter($collection);
}
