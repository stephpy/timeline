<?php

namespace Spy\Timeline\Filter;

interface FilterInterface
{
    /**
     * @return integer
     */
    public function getPriority();

    /**
     * This action will filters each results given in parameters
     * You have to return results
     *
     * @param array|\Traversable $collection
     *
     * @return array
     */
    public function filter($collection);
}
