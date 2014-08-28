<?php

namespace Spy\Timeline\Filter\DataHydrator;

use Doctrine\Common\Persistence\Proxy;
use Spy\Timeline\Model\ActionComponentInterface;
use Spy\Timeline\Model\ActionInterface;

class Entry
{
    /**
     * @var ActionInterface
     */
    private $action;

    /**
     * @var array
     */
    private $components = array();

    /**
     * @var int
     */
    protected $key;

    /**
     * @param ActionInterface $action action
     * @param string          $key    key
     */
    public function __construct(ActionInterface $action, $key)
    {
        $this->action = $action;
        $this->key    = $key;
    }

    /**
     * Build references (subject, directComplement, indirectComplement)
     * of timeline action
     */
    public function build()
    {
        foreach ($this->action->getActionComponents() as $actionComponent) {
            if (!$actionComponent->isText()) {
                $this->buildComponent($actionComponent);
            }
        }
    }

    /**
     * @param ActionComponentInterface $actionComponent actionComponent
     */
    protected function buildComponent(ActionComponentInterface $actionComponent)
    {
        $component = $actionComponent->getComponent();
        if (!is_object($component)) {
            return;
        }

        $data      = $component->getData();

        if (null !== $data
            && (!$data instanceof Proxy || $data->__isInitialized())
        ) {
            return;
        }

        $this->components[$component->getHash()] = $component;
    }

    /**
     * @return array<*,Reference>
     */
    public function getComponents()
    {
        return $this->components;
    }
}
