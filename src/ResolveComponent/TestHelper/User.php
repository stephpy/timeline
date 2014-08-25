<?php

namespace Spy\Timeline\ResolveComponent\TestHelper;

/**
 * User object with get id method.
 */
class User
{
    protected $id;

    /**
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
