<?php

namespace Spy\Timeline\ResolveComponent;

use Spy\Timeline\ResolveComponent\ValueObject\ResolvedComponentData;
use Spy\Timeline\Exception\ResolveComponentDataException;

/**
 * Interface ComponentDataResolverInterface
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 * @author Stephane PY <py.stephane1@gmail.com>
 */
interface ComponentDataResolverInterface
{
    /**
     * Resolves the component data (model, identifier, data) from the given model and identifier.
     *
     * @param string|object     $model      pass an object and second argument will be ignored.
     *                                      it'll be replaced by $model->getId();
     * @param null|string|array $identifier pass an array for composite keys.
     *
     * @return ResolvedComponentData
     *
     * @throws ResolveComponentDataException When not able to resolve the component data
     */
    public function resolveComponentData($model, $identifier = '');
}
