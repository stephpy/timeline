<?php

namespace Spy\Timeline\Spread;

use Spy\Timeline\Model\ActionInterface;
use Spy\Timeline\Spread\Entry\EntryCollection;

interface SpreadInterface
{
    /**
     * You spread class is support the action ?
     *
     * @param ActionInterface $action
     *
     * @return boolean
     */
    public function supports(ActionInterface $action);

    /**
     * @param  ActionInterface $action action we look for spreads
     * @param  EntryCollection $coll   Spreads defined on an EntryCollection
     * @return void
     */
    public function process(ActionInterface $action, EntryCollection $coll);
}
