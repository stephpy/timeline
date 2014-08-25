<?php

namespace Spy\Timeline\Filter;

interface FilterManagerInterface
{
    /**
     * @param array $collection collection
     *
     * @return array
     */
    public function filter($collection);
}
