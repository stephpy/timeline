<?php

namespace Spy\Timeline\ResolveComponent;

use Spy\Timeline\ResolveComponent\ValueObject\ResolvedComponentData;
use Spy\Timeline\Exception\ResolveComponentDataException;
use Spy\Timeline\ResolveComponent\ValueObject\ResolveComponentModelIdentifier;

/**
 * Basic implementation of a component data resolver.
 *
 * When no object is given we use the given model and identifier and data=null as ResolvedComponentData arguments
 *
 * When an object is given
 *  - the model string is extracted by using get_class on the model
 *  - The identifier is extracted by using the getId method on the model
 *
 * When not able to resolve the component data an
 * Spy\Timeline\Exception\ResolveComponentDataException exception is thrown.
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class BasicComponentDataResolver implements ComponentDataResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolveComponentData(ResolveComponentModelIdentifier $resolve)
    {
        $model = $resolve->getModel();
        $identifier = $resolve->getIdentifier();

        $data = null;
        if (is_object($model)) {
            $data = $model;
            $modelClass = get_class($model);

            if (!method_exists($model, 'getId')) {
                throw new ResolveComponentDataException('Model must have a getId method');
            }

            $identifier = $model->getId();
            $model = $modelClass;
        }

        return new ResolvedComponentData($model, $identifier, $data);
    }
}
