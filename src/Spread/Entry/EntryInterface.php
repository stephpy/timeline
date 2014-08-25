<?php

namespace Spy\Timeline\Spread\Entry;

use Spy\Timeline\Model\ComponentInterface;

interface EntryInterface
{
    /**
     * @return string
     */
    public function getIdent();

    /**
     * @return ComponentInterface
     */
    public function getSubject();
}
