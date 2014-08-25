<?php

namespace Spy\Timeline\ResolveComponent;

use Spy\Timeline\ResolveComponent\ValueObject\ResolvedComponentData;
use Spy\Timeline\ResolveComponent\ValueObject\ResolveComponentModelIdentifier;
use Spy\Timeline\Exception\ResolveComponentDataException;

interface ComponentDataResolverInterface
{
    /**
     * @param ResolveComponentModelIdentifier $resolve The ResolveComponentModelIdentifier value object.
     *
     * @return ResolvedComponentData The resolved component data value object.
     *
     * @throws ResolveComponentDataException When not able to resolve the component data
     */
    public function resolveComponentData(ResolveComponentModelIdentifier $resolve);
}
