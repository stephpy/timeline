<?php

namespace tests\units\Spy\Timeline\ResolveComponent\ValueObject;

require_once __DIR__.'/../../../../../../vendor/autoload.php';

use Spy\Timeline\ResolveComponent\ValueObject\ResolvedComponentData as TestedModel;
use mageekguy\atoum;

class ResolvedComponentData extends atoum\test
{
    public function testEmptyModelThrowsException()
    {
        $this->exception(function () {
            new TestedModel('', 2);
            })
            ->isInstanceOf('Spy\Timeline\Exception\ResolveComponentDataException')
            ->hasMessage('The resolved model can not be empty')
        ;
    }

    public function testArrayModelThrowsException()
    {
        $this->exception(function () {
                new TestedModel(array('foo'), 2);
            })
            ->isInstanceOf('Spy\Timeline\Exception\ResolveComponentDataException')
            ->hasMessage('The resolved model has to be a string')
        ;
    }

    public function testObjectModelThrowsException()
    {
        $this->exception(function () {
                new TestedModel(new \stdClass(), 2);
            })
            ->isInstanceOf('Spy\Timeline\Exception\ResolveComponentDataException')
            ->hasMessage('The resolved model has to be a string')
        ;
    }

    public function testEmptyIdentifierThrowsException()
    {
        $invalidData = array(null, '');

        foreach ($invalidData as $invalid) {
            $this->exception(function () use ($invalid) {
                    new TestedModel('user', $invalid);
                })
                ->isInstanceOf('Spy\Timeline\Exception\ResolveComponentDataException')
                ->hasMessage('No resolved identifier given')
            ;
        }
    }

    public function testObjectAsIdentifierThrowsException()
    {
        $this->exception(function () {
                new TestedModel('user', new \stdClass());
            })
            ->isInstanceOf('Spy\Timeline\Exception\ResolveComponentDataException')
            ->hasMessage('Identifier has to be a scalar or an array')
        ;
    }

    public function testIntegerIdentifierReturnsAsString()
    {
        $this->if($action = new TestedModel('user', 1))
            ->string($action->getModel())->isEqualTo('user')
            ->string($action->getIdentifier())->isEqualTo('1')
        ;
    }

    public function testValidModelAndIdentifiersWhereIdentifierArray()
    {
        $stringModel = 'foo/bar/baz';
        $arrayIdentifier = array('foo' => 'bar', 'bar' => 5);

        $this->if($action = new TestedModel($stringModel, $arrayIdentifier))
            ->string($action->getModel())->isEqualTo($stringModel)
            ->array($action->getIdentifier())->isEqualTo($arrayIdentifier)
        ;
    }

    public function testSettingDataWorks()
    {
        $object = new \stdClass();
        $object->title = 'foo';

        $this->if($action = new TestedModel('user', 1, $object))
            ->string($action->getData()->title)->isEqualTo('foo')
        ;
    }

    public function testNoDataSetReturnsNull()
    {
        $this->if($action = new TestedModel('user', 1))
            ->variable($action->getData())->isNull()
        ;
    }

    public function testIdentifierCanBeZero()
    {
        $this->if($action = new TestedModel('user', '0'))
            ->string($action->getIdentifier())->isEqualTo('0')
        ;
    }
}
