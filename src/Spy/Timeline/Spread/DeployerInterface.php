<?php

namespace Spy\Timeline\Spread;

use Spy\Timeline\Driver\ActionManagerInterface;
use Spy\Timeline\Model\ActionInterface;
use Spy\Timeline\Notification\NotificationManagerInterface;

/**
 * Deployer
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
interface DeployerInterface
{
    CONST DELIVERY_IMMEDIATE = 'immediate';
    CONST DELIVERY_WAIT      = 'wait';

    /**
     * @param ActionInterface        $action        action
     * @param ActionManagerInterface $actionManager actionManager
     */
    public function deploy(ActionInterface $action, ActionManagerInterface $actionManager);

    /**
     * @param string $delivery delivery
     */
    public function setDelivery($delivery);

    /**
     * @param NotificationManagerInterface $notificationManager notificationManager
     */
    public function setNotificationManager(NotificationManagerInterface $notificationManager);

    /**
     * @return boolean
     */
    public function isDeliveryImmediate();

    /**
     * @param SpreadInterface $spread spread
     */
    public function addSpread(SpreadInterface $spread);

    /**
     * @return \ArrayIterator of SpreadInterface
     */
    public function getSpreads();
}
