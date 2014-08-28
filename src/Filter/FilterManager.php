<?php

namespace Spy\Timeline\Filter;

class FilterManager implements FilterManagerInterface
{
    /**
     * @var FilterInterface[]
     */
    protected $filters = array();

    /**
     * @var boolean
     */
    protected $sorted = true;

    /**
     * @param FilterInterface $filter filter
     */
    public function add(FilterInterface $filter)
    {
        $this->filters[] = $filter;
        $this->sorted    = false;
    }

    /**
     * {@inheritdoc}
     */
    public function filter($collection)
    {
        if (!$this->sorted) {
            $this->sortFilters();
        }

        if (!is_array($collection) && !$collection instanceof \Traversable) {
            throw new \Exception('Collection must be an array or traversable');
        }

        foreach ($this->filters as $filter) {
            $collection = $filter->filter($collection);
        }

        return $collection;
    }

    /**
     * Sort filters by priority.
     */
    protected function sortFilters()
    {
        usort($this->filters, function (FilterInterface $a, FilterInterface $b) {
            $a = $a->getPriority();
            $b = $b->getPriority();

            if ($a == $b) {
                return 0;
            }

            return $a < $b ? -1 : 1;
        });

        $this->sorted = true;
    }
}
