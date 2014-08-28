<?php

namespace Spy\Timeline\Tests\Units\Driver\Redis;

require_once __DIR__.'/../../../../../../vendor/autoload.php';

use Spy\Timeline\Driver\Redis\ActionManager as TestedModel;
use Spy\Timeline\ResolveComponent\ValueObject\ResolveComponentModelIdentifier;
use Spy\Timeline\ResolveComponent\ValueObject\ResolvedComponentData;
use mageekguy\atoum;

class ActionManager extends atoum\test
{
    public function testFindOrCreateComponent()
    {
        $model = 'user';
        $identifier = array('foo' => 'bar', 'baz' => 'baz');
        $resolve = new ResolveComponentModelIdentifier($model, $identifier);

        $this
            //mocks
            ->if($this->mockClass('Spy\Timeline\ResultBuilder\ResultBuilderInterface', '\Mock'))
            ->and($this->mockClass('Spy\Timeline\ResolveComponent\ComponentDataResolverInterface', '\Mock'))

            ->and($redis = new \mock\StdClass())
            ->and($resultBuilder = new \mock\ResultBuilderInterface())
            ->and($componentDataResolver = new \mock\ComponentDataResolverInterface())
            ->and($actionClass = 'Spy\Timeline\Model\Action')
            ->and($componentClass = 'Spy\Timeline\Model\Component')
            ->and($actionComponentClass = 'Spy\Timeline\Model\ActionComponent')
            ->and($this->calling($componentDataResolver)->resolveComponentData = function () use ($model, $identifier) {
                return new ResolvedComponentData($model, $identifier);
            })
            ->and($object = new TestedModel($redis, $resultBuilder, 'foo', $actionClass, $componentClass, $actionComponentClass))

            ->and($object->setComponentDataResolver($componentDataResolver))
            ->when($result = $object->findOrCreateComponent($model, $identifier))
            ->then(
                $this->mock($componentDataResolver)->call('resolveComponentData')->withArguments($resolve)->exactly(1)
                ->string($result->getModel())->isEqualTo($model)
                ->array($result->getIdentifier())->isEqualTo($identifier)
            )
        ;
    }
}
