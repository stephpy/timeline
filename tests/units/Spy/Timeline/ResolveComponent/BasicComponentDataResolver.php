<?php

namespace tests\units\Spy\Timeline\ResolveComponent;

require_once __DIR__.'/../../../../../vendor/autoload.php';

use Spy\Timeline\ResolveComponent\BasicComponentDataResolver as TestedModel;
use Spy\Timeline\ResolveComponent\TestHelper\User;
use Spy\Timeline\ResolveComponent\ValueObject\ResolveComponentModelIdentifier;
use mageekguy\atoum;

class BasicComponentDataResolver extends atoum\test
{
    public function testWithObjectThatHasGetId()
    {
        $user = new User(1);
        $resolve = new ResolveComponentModelIdentifier($user);

        $this->if($object = new TestedModel())
            ->string($object->resolveComponentData($resolve)->getIdentifier())->isEqualTo('1')
            ->string($object->resolveComponentData($resolve)->getModel())->isEqualTo('Spy\Timeline\ResolveComponent\TestHelper\User')
            ->object($object->resolveComponentData($resolve)->getData())->isEqualTo($user)
        ;
    }

    public function testWithObjectWhichHasNoGetId()
    {
        $model = new \stdClass();
        $resolve = new ResolveComponentModelIdentifier($model);
        $this->if($object = new TestedModel())
            ->exception(function () use ($object, $resolve) {
                $object->resolveComponentData($resolve);
            })
            ->isInstanceOf('Spy\Timeline\Exception\ResolveComponentDataException')
            ->hasMessage('Model must have a getId method')
        ;
    }

    public function testWithStringAndIdentifierGiven()
    {
        $identifier = array('foo' => 'bar', 'baz' => 5);
        $resolve = new ResolveComponentModelIdentifier('user', $identifier);

        $this->if($object = new TestedModel())
            ->array($object->resolveComponentData($resolve)->getIdentifier())->isEqualTo($identifier)
            ->string($object->resolveComponentData($resolve)->getModel())->isEqualTo('user')
            ->variable($object->resolveComponentData($resolve)->getData())->isNull()
        ;
    }

    public function testWhenObjectGivenIdentifierGetsIgnored()
    {
        $user = new User(1);
        $identifier = 5;
        $resolve = new ResolveComponentModelIdentifier($user, $identifier);

        $this->if($object = new TestedModel())
            ->string($object->resolveComponentData($resolve)->getIdentifier())->isEqualTo('1')
        ;
    }
}
