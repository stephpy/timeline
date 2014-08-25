<?php

namespace Spy\Timeline\Driver\Redis\Pager;

/**
 * PagerToken
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class PagerToken
{
    public $key;

    /**
     * @param string $key key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }
}
