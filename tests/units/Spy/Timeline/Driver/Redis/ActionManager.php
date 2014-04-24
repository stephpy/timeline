<?php

namespace Spy\Timeline\Tests\Units\Driver\Redis;

require_once __DIR__ . '/../../../../../../vendor/autoload.php';

use mageekguy\atoum;
use Spy\Timeline\Driver\Redis\ActionManager as TestedModel;
use Spy\Timeline\ResolveComponent\ValueObject\ResolvedComponentData;

/**
 * Class ActionManager
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ActionManager extends atoum\test
{
    public function testFindOrCreateComponent()
    {
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
            ->and($this->calling($componentDataResolver)->resolveComponentData = function () {
                return new ResolvedComponentData('user', '1');
            })
            ->and($object = new TestedModel($redis, $resultBuilder, 'foo', $actionClass, $componentClass, $actionComponentClass))

            ->and($object->setComponentDataResolver($componentDataResolver))
            ->when($result = $object->findOrCreateComponent('user', 1))
            ->then(
                $this->mock($componentDataResolver)->call('resolveComponentData')->withArguments('user', 1)->exactly(1)
                ->string($result->getModel())->isEqualTo('user')
                ->string($result->getIdentifier())->isEqualTo('1')
            )
            ;
    }
}
