<?php

namespace Spy\Timeline\Filter;

use Spy\Timeline\Model\TimelineInterface;

/**
 * Defined on "Resources/doc/filter.markdown"
 * This filter will unset from collection timeline_actions which
 * has same duplicate_key property
 */
class DuplicateKey extends AbstractFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function filter($collection)
    {
        $duplicateKeys = array();
        $clonedCollection = array();

        foreach ($collection as $key => $value) {
            $clonedCollection[$key] = $value;
        }

        foreach ($clonedCollection as $key => $action) {
            if ($action instanceof TimelineInterface) {
                $action = $action->getAction();
            }

            if ($action->hasDuplicateKey()) {
                $currentKey      = $action->getDuplicateKey();
                $currentPriority = $action->getDuplicatePriority();

                if (array_key_exists($currentKey, $duplicateKeys)) {
                    //actual entry has bigger priority
                    if ($currentPriority > $duplicateKeys[$currentKey]['priority']) {
                        $keyToDelete = $duplicateKeys[$currentKey]['key'];

                        $duplicateKeys[$currentKey]['key']      = $key;
                        $duplicateKeys[$currentKey]['priority'] = $currentPriority;
                    } else {
                        $keyToDelete = $key;
                    }

                    $duplicateKeys[$currentKey]['duplicated'] = true;
                    unset($collection[$keyToDelete]);
                } else {
                    $duplicateKeys[$currentKey] = array(
                        'key'        => $key,
                        'priority'   => $currentPriority,
                        'duplicated' => false,
                    );
                }
            }
        }

        foreach ($duplicateKeys as $key => $values) {
            if ($values['duplicated']) {
                $action = $collection[$values['key']];

                if ($action instanceof TimelineInterface) {
                    $action->getAction()->setIsDuplicated(true);
                } else {
                    $action->setIsDuplicated(true);
                }
            }
        }

        return $collection;
    }
}
