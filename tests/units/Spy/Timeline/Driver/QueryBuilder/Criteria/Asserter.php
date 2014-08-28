<?php

namespace tests\units\Spy\Timeline\Driver\QueryBuilder\Criteria;

require_once __DIR__.'/../../../../../../../vendor/autoload.php';

use Spy\Timeline\Driver\QueryBuilder\Criteria\Asserter as TestedModel;
use mageekguy\atoum;

class Asserter extends atoum\test
{
    public function testField()
    {
        $this->if($model = new TestedModel())
            ->object($model->field('field'))
            ->isInstanceOf('Spy\Timeline\Driver\QueryBuilder\Criteria\Asserter')
            ->string($model->getField())
            ->isEqualTo('field')
        ;
    }

    public function getAsserters()
    {
        return array(
            array('equals', '=', 'string'),
            array('notEquals', '!=', 'string'),
            array('in', 'IN', array()),
            array('notIn', 'NOT IN', array()),
            array('like', 'LIKE', 'string'),
            array('notLike', 'NOT LIKE', 'string'),
            array('lt', '<', 'string'),
            array('lte', '<=', 'string'),
            array('gt', '>', 'string'),
            array('gte', '>=', 'string'),
        );
    }

    /**
     * @dataProvider getAsserters
     */
    public function testAsserters($method, $operator, $data)
    {
        $this->if($model = new TestedModel())
            ->and($resultExpected = new TestedModel())
            ->and($resultExpected->field('field'))
            ->and($resultExpected->create($operator, $data))
            ->object($model->field('field')->{$method}($data))
            ->isEqualTo($resultExpected)
        ;
    }

    /**
     * @dataProvider getAsserters
     */
    public function testToArray($method, $operator, $data)
    {
        $this->if($model = new TestedModel())
            ->and($model->field('field'))
            ->and($model->create($operator, $data))
            ->and($resultExpected = array(
                'type' => 'expr',
                'value' => array('field', $operator, $data),
            ))
            ->array($model->toArray())
            ->isIdenticalTo($resultExpected)
        ;
    }

    /**
     * @dataProvider getAsserters
     */
    public function testFromArray($method, $operator, $data)
    {
        $this->if($model = new TestedModel())
            ->and($resultExpected = new TestedModel())
            ->and($resultExpected->field('field'))
            ->and($resultExpected->create($operator, $data))
            ->and($arrayRepresentation = array(
                'type' => 'expr',
                'value' => array('field', $operator, $data),
            ))
            ->object($model->fromArray($arrayRepresentation))
            ->isEqualTo($resultExpected)
        ;
    }
}
