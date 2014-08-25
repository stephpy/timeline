<?php

namespace Spy\Timeline\Driver\Redis\Pager;

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
