<?php

namespace Spy\Timeline\Filter\DataHydrator\Locator;

use Spy\Timeline\Model\ComponentInterface;

interface LocatorInterface
{
    /**
     * Decides whether we support the given string representation of the model.
     *
     * @param string $model model
     *
     * @return boolean
     */
    public function supports($model);

    /**
     * Sets the data for the given components.
     *
     * This method populates the data for the given components by calling setData on those components.
     *
     * @param string               $model      model
     * @param ComponentInterface[] $components Array with components for whom we populate the data by calling setData
     */
    public function locate($model, array $components);
}
