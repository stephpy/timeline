<?php

namespace Spy\Timeline\Spread;

use Spy\Timeline\Driver\ActionManagerInterface;
use Spy\Timeline\Driver\TimelineManagerInterface;
use Spy\Timeline\Model\ActionInterface;
use Spy\Timeline\Model\TimelineInterface;
use Spy\Timeline\Notification\NotifierInterface;
use Spy\Timeline\Spread\Entry\Entry;
use Spy\Timeline\Spread\Entry\EntryCollection;

class Deployer implements DeployerInterface
{
    /**
     * @var SpreadInterface[]
     */
    protected $spreads;

    /**
     * @var NotifierInterface[]
     */
    protected $notifiers = array();

    /**
     * @var integer
     */
    protected $batchSize;

    /**
     * @var EntryCollection
     */
    protected $entryCollection;

    /**
     * @var boolean
     */
    protected $onSubject;

    /**
     * @var TimelineManagerInterface
     */
    protected $timelineManager;

    /**
     * @var string One of the delivery constants in DeployerInterface
     */
    protected $delivery;

    /**
     * @param TimelineManagerInterface $timelineManager timelineManager
     * @param EntryCollection          $entryCollection entryCollection
     * @param boolean                  $onSubject       onSubject
     * @param integer                  $batchSize       batch size
     */
    public function __construct(TimelineManagerInterface $timelineManager, EntryCollection $entryCollection, $onSubject = true, $batchSize = 50)
    {
        $this->timelineManager = $timelineManager;
        $this->entryCollection = $entryCollection;
        $this->spreads         = new \ArrayIterator();
        $this->onSubject       = (bool) $onSubject;
        $this->batchSize       = (int) $batchSize;
    }

    /**
     * {@inheritdoc}
     */
    public function deploy(ActionInterface $action, ActionManagerInterface $actionManager)
    {
        if ($action->getStatusWanted() !== ActionInterface::STATUS_PUBLISHED) {
            return;
        }

        $this->entryCollection->setActionManager($actionManager);

        $results = $this->processSpreads($action);
        $results->loadUnawareEntries();

        $i = 1;
        foreach ($results as $context => $entries) {
            foreach ($entries as $entry) {

                $this->timelineManager->createAndPersist($action, $entry->getSubject(), $context, TimelineInterface::TYPE_TIMELINE);

                if (($i % $this->batchSize) == 0) {
                    $this->timelineManager->flush();
                }
                $i++;
            }
        }

        if ($i > 1) {
            $this->timelineManager->flush();
        }

        foreach ($this->notifiers as $notifier) {
            $notifier->notify($action, $results);
        }

        $action->setStatusCurrent(ActionInterface::STATUS_PUBLISHED);
        $action->setStatusWanted(ActionInterface::STATUS_FROZEN);

        $actionManager->updateAction($action);

        $this->entryCollection->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function setDelivery($delivery)
    {
        $availableDelivery = array(self::DELIVERY_IMMEDIATE, self::DELIVERY_WAIT);

        if (!in_array($delivery, $availableDelivery)) {
            throw new \InvalidArgumentException(sprintf('Delivery "%s" is not supported, (%s)', $delivery, implode(', ', $availableDelivery)));
        }

        $this->delivery = $delivery;
    }

    /**
     * {@inheritdoc}
     */
    public function isDeliveryImmediate()
    {
        return self::DELIVERY_IMMEDIATE === $this->delivery;
    }

    /**
     * {@inheritdoc}
     */
    public function addSpread(SpreadInterface $spread)
    {
        $this->spreads[] = $spread;
    }

    /**
     * @param ActionInterface $action action
     *
     * @return EntryCollection
     */
    public function processSpreads(ActionInterface $action)
    {
        if ($this->onSubject) {
            $this->entryCollection->add(new Entry($action->getSubject()), 'GLOBAL');
        }

        foreach ($this->spreads as $spread) {
            if ($spread->supports($action)) {
                $spread->process($action, $this->entryCollection);
            }
        }

        return $this->getEntryCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function addNotifier(NotifierInterface $notifier)
    {
        $this->notifiers[] = $notifier;
    }

    /**
     * @return EntryCollection
     */
    public function getEntryCollection()
    {
        return $this->entryCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function getSpreads()
    {
        return $this->spreads;
    }
}
