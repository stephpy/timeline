<?php

namespace Spy\Timeline\Driver\Redis\Pager;

use Knp\Component\Pager\Event\ItemsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class KnpSubscriber extends AbstractPager implements EventSubscriberInterface
{
    /**
     * @param ItemsEvent $event event
     */
    public function items(ItemsEvent $event)
    {
        if (!$event->target instanceof PagerToken) {
            return;
        }

        $target = $event->target;
        $offset = $event->getOffset();
        $limit = $event->getLimit() - 1;

        $ids = $this->client->zRevRange($target->key, $offset, ($offset + $limit));

        $event->count = $this->client->zCard($target->key);
        $event->items = $this->actionManager->findActionsForIds($ids);
        $event->stopPropagation();
    }

    /**
     * @return array<string,array<string|integer>>
     */
    public static function getSubscribedEvents()
    {
        return array(
            'knp_pager.items' => array('items', 1)
        );
    }
}
