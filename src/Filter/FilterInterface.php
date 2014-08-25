<?php

namespace Spy\Timeline\Filter;

/**
 * This interface define how filters must be used
 */
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
