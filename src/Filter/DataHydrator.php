<?php

namespace Spy\Timeline\Filter;

use Spy\Timeline\Filter\DataHydrator\Entry;
use Spy\Timeline\Filter\DataHydrator\Locator\LocatorInterface;
use Spy\Timeline\Model\TimelineInterface;
use Spy\Timeline\ResultBuilder\Pager\PagerInterface;

class DataHydrator extends AbstractFilter implements FilterInterface
{
    /**
     * @var array
     */
    protected $locators = array();

    /**
     * @var array
     */
    protected $components = array();

    /**
     * @var array
     */
    protected $entries = array();

    /**
     * @var boolean
     */
    protected $filterUnresolved;

    /**
     * @param boolean $filterUnresolved filterUnresolved
     */
    public function __construct($filterUnresolved = false)
    {
        $this->filterUnresolved = $filterUnresolved;
    }

    /**
     * @param LocatorInterface $locator locator
     */
    public function addLocator(LocatorInterface $locator)
    {
        $this->locators[] = $locator;
    }

    /**
     * {@inheritdoc}
     */
    public function filter($collection)
    {
        if (empty($this->locators)) {
            return $collection;
        }

        foreach ($collection as $key => $action) {
            if ($action instanceof TimelineInterface) {
                $action = $action->getAction();
            }

            $entry = new Entry($action, $key);
            $entry->build();

            $this->addComponents($entry->getComponents());
        }

        return $this->hydrateComponents($collection);
    }

    /**
     * @param array $components
     */
    protected function addComponents(array $components)
    {
        foreach ($components as $component) {
            $model = $component->getModel();
            if (!array_key_exists($model, $this->components)) {
                $this->components[$model] = array();
            }

            $this->components[$model][$component->getHash()] = $component;
        }
    }

    /**
     * Use locators to hydrate components.
     *
     * @param mixed $collection collection
     *
     * @throws \Exception
     * @return mixed
     */
    protected function hydrateComponents($collection)
    {
        $componentsLocated = array();

        foreach ($this->components as $model => $components) {
            foreach ($this->locators as $locator) {
                if ($locator->supports($model)) {
                    $locator->locate($model, $components);

                    foreach ($components as $key => $component) {
                        $componentsLocated[$key] = $component;
                    }

                    break;
                }
            }
        }

        foreach ($collection as $key => $action) {
            if ($action instanceof TimelineInterface) {
                $action = $action->getAction();
            }

            foreach ($action->getActionComponents() as $actionComponent) {
                $component = $actionComponent->getComponent();
                if (!$actionComponent->isText() && is_object($component) && null === $component->getData()) {
                    $hash = $component->getHash();

                    if (array_key_exists($hash, $componentsLocated) && !empty($componentsLocated[$hash]) && null !== $componentsLocated[$hash]->getData()) {
                        $actionComponent->setComponent($componentsLocated[$hash]);
                    } else {
                        if ($this->filterUnresolved) {
                            if ($collection instanceof PagerInterface) {
                                $items = iterator_to_array($collection->getIterator());
                                unset($items[$key]);
                                $collection->setItems($items);
                            } elseif (is_array($collection)) {
                                unset($collection[$key]);
                            } else {
                                throw new \Exception('Collection must be an array or a PagerInterface');
                            }
                            break;
                        }
                    }
                }
            }
        }

        return $collection;
    }
}
