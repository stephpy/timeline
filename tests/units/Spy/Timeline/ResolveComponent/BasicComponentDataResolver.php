<?php

namespace tests\units\Spy\Timeline\ResolveComponent;

require_once __DIR__ . '/../../../../../vendor/autoload.php';

use mageekguy\atoum;
use Spy\Timeline\ResolveComponent\BasicComponentDataResolver as TestedModel;
use Spy\Timeline\ResolveComponent\TestHelper\User;

/**
 * Test file for Spy\Timeline\ResolveComponent\BasicComponentDataResolver
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class BasicComponentDataResolver extends atoum\test
{
    public function testInvalidModelIdentifier()
    {
        $this->if($object = new TestedModel())
            ->exception(function() use ($object) {
                $object->resolveComponentData('user');
            })
            ->isInstanceOf('Spy\Timeline\Exception\ResolveComponentDataException')
            ->hasMessage('Model has to be an object or a scalar + an identifier in 2nd argument')
            ;
    }

    public function testWithObjectThatHasGetId()
    {
        $user = new User(1);

        $this->if($object = new TestedModel())
            ->string($object->resolveComponentData($user)->getIdentifier())->isEqualTo('1')
            ->string($object->resolveComponentData($user)->getModel())->isEqualTo('Spy\Timeline\ResolveComponent\TestHelper\User')
            ->object($object->resolveComponentData($user)->getData())->isEqualTo($user)
        ;
    }

    public function testWithObjectWhichHasNoGetId()
    {
        $model = new \stdClass();

        $this->if($object = new TestedModel())
            ->exception(function () use ($object, $model) {
                $object->resolveComponentData($model);
            })
            ->isInstanceOf('Spy\Timeline\Exception\ResolveComponentDataException')
            ->hasMessage('Model must have a getId method')
        ;
    }

    public function testWithStringAndIdentifierGiven()
    {
        $this->if($object = new TestedModel())
            ->string($object->resolveComponentData('user', 1)->getIdentifier())->isEqualTo('1')
            ->string($object->resolveComponentData('user', 1)->getModel())->isEqualTo('user')
            ->variable($object->resolveComponentData('user', 1)->getData())->isNull()
        ;
    }

    public function testWhenObjectIdentifierGetsIgnored()
    {
        $user = new User(1);

        $this->if($object = new TestedModel())
            ->string($object->resolveComponentData($user, 5)->getIdentifier())->isEqualTo('1')
        ;
    }
}
