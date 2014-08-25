<?php

namespace Spy\Timeline\ResultBuilder\Pager;

abstract class AbstractPager implements \ArrayAccess
{
    /**
     * @var array
     */
    protected $pager;

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->pager);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->pager[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if (null === $offset) {
            $this->pager[] = $value;
        } else {
            $this->pager[$offset] = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->pager[$offset]);
    }
}
