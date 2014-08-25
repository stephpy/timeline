<?php

namespace Spy\Timeline\Notification;

use Spy\Timeline\Model\ActionInterface;
use Spy\Timeline\Spread\Entry\EntryCollection;

interface NotifierInterface
{
    /**
     * @param ActionInterface $action          action
     * @param EntryCollection $entryCollection entryCollection
     */
    public function notify(ActionInterface $action, EntryCollection $entryCollection);
}
