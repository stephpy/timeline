<?php

namespace Spy\Timeline\Filter;

abstract class AbstractFilter
{
    /**
     * @var integer
     */
    protected $priority = 255;

    /**
     * @param array $options options
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }
}
