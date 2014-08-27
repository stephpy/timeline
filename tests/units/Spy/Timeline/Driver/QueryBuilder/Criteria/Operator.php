<?php

namespace tests\units\Spy\Timeline\Driver\QueryBuilder\Criteria;

require_once __DIR__.'/../../../../../../../vendor/autoload.php';

use Spy\Timeline\Driver\QueryBuilder\Criteria\Operator as TestedModel;
use mageekguy\atoum;

class Operator extends atoum\test
{
    public function testSetType()
    {
        $this->if($model = new TestedModel())
            ->exception(function () use ($model) {
                $model->setType('unknown');
            })
            ->isInstanceOf('\InvalidArgumentException')
            ->hasMessage('Type "unknown" not supported')
            ->object($model->setType('AND'))
            ->isInstanceOf('Spy\Timeline\Driver\QueryBuilder\Criteria\Operator')
            ->string($model->getType())
            ->isEqualTo('AND')
        ;
    }

    public function testGetAvailableTypes()
    {
        $this->if($model = new TestedModel())
            ->array($model->getAvailableTypes())
            ->isNotEmpty();
    }

    public function testToArray()
    {
        $this->if($model = new TestedModel())
            ->and($this->mockClass('Spy\Timeline\Driver\QueryBuilder\Criteria\CriteriaInterface', '\Mock'))
            ->and($criteria = new \Mock\CriteriaInterface())
            ->and($criteria->getMockController()->toArray = 'CRITERIA_TO_ARRAY')
            ->and($model->addCriteria($criteria))
            ->and($model->setType('AND'))
            ->array($model->toArray())
            ->isEqualTo(array(
                'type' => 'operator',
                'value' => 'AND',
                'criterias' => array(
                    'CRITERIA_TO_ARRAY'
                ),
            ))
        ;
    }

    public function testFromArray()
    {
        $this->if($model = new TestedModel())
            ->and($this->mockClass('Spy\Timeline\Driver\QueryBuilder\Criteria\Operator', '\Mock'))
            ->and($this->mockClass('Spy\Timeline\Driver\QueryBuilder\Criteria\Asserter', '\Mock'))
            ->and($this->mockClass('Spy\Timeline\Driver\QueryBuilder\QueryBuilderFactory', '\Mock'))
            ->and($operator = new \Mock\Operator())
            ->and($asserter = new \Mock\Asserter())
            // init factory
            ->and($factory = new \Mock\QueryBuilderFactory())
            ->and($factory->getMockController()->createOperatorFromArray = $operator)
            ->and($factory->getMockController()->createAsserterFromArray = $asserter)
            // reuslt expected
            ->and($resultExpected = new TestedModel())
            ->and($resultExpected->addCriteria($asserter))
            ->and($resultExpected->addCriteria($operator))
            ->and($resultExpected->setType('AND'))
            // let's go.
            ->object($model->fromArray(array(
                'type'      => 'operator',
                'value'     => 'AND',
                'criterias' => array(
                    array('type' => 'expr', /*...*/),
                    array('type' => 'operator', /*...*/),
                ),
            ), $factory))
            ->isEqualTo($resultExpected)
            // to be sure
            ->object($model->fromArray(array(
                'type'      => 'operator',
                'value'     => 'AND',
                'criterias' => array(
                    array('type' => 'operator', /*...*/),
                    array('type' => 'expr', /*...*/),
                ),
            ), $factory))
            ->isNotEqualTo($resultExpected)
        ;
    }
}
