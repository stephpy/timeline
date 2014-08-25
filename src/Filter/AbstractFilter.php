<?php

namespace Spy\Timeline\Filter;

abstract class AbstractFilter
{
    /**
     * @var integer
     */
    protected $priority = 255;

    /**
     * @param mixed $priority
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
