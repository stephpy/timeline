<?php

namespace tests\units\Spy\Timeline\ResolveComponent\ValueObject;

require_once __DIR__.'/../../../../../../vendor/autoload.php';

use Spy\Timeline\ResolveComponent\TestHelper\User;
use Spy\Timeline\ResolveComponent\ValueObject\ResolveComponentModelIdentifier as TestedModel;
use mageekguy\atoum;

class ResolveComponentModelIdentifier extends atoum\test
{
    public function testStringModelEmptyIdentifierThrowsException()
    {
        $this->exception(function () {
            new TestedModel('user');
            })
            ->isInstanceOf('Spy\Timeline\Exception\ResolveComponentDataException')
            ->hasMessage('Model has to be an object or (a scalar + an identifier in 2nd argument)')
        ;
    }

    public function testEmptyModelThrowsException()
    {
        $this->exception(function () {
            new TestedModel('');
        })
            ->isInstanceOf('Spy\Timeline\Exception\ResolveComponentDataException')
            ->hasMessage('Model has to be an object or (a scalar + an identifier in 2nd argument)')
        ;
    }

    public function testObjectModelWithIdentifierGivenReturnsNullAsIdentifier()
    {
        $model = new \stdClass();

        $this->when($object = new TestedModel($model, 5))
            ->variable($object->getIdentifier())->isNull()
        ;
    }

    public function testObjectWithNoIdentifierReturnsObjectAndNullAsIdentifier()
    {
        $model = new User('5');

        $this->when($object = new TestedModel($model, 5))
            ->variable($object->getModel())->isIdenticalTo($model)
            ->variable($object->getIdentifier())->isNull()
        ;
    }

    public function testArrayIdentifier()
    {
        $identifier = array('foo' => 5, 'bar' => 'baz');
        $this->when($object = new TestedModel('user', $identifier))
            ->variable($object->getIdentifier())->isIdenticalTo($identifier)
        ;
    }

    public function testIdentifierCanBeIntegerZero()
    {
        $this->when($object = new TestedModel('user', 0))
            ->integer($object->getIdentifier())->isZero()
        ;
    }

    public function testIdentifierCanBeStringZero()
    {
        $this->when($object = new TestedModel('user', '0'))
            ->string($object->getIdentifier())->isEqualTo('0')
        ;
    }
}
