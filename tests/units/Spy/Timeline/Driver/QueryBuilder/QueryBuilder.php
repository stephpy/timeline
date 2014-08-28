<?php

namespace tests\units\Spy\Timeline\Driver\QueryBuilder;

require_once __DIR__.'/../../../../../../vendor/autoload.php';

use Spy\Timeline\Driver\QueryBuilder\Criteria\Asserter;
use Spy\Timeline\Driver\QueryBuilder\Criteria\Operator;
use Spy\Timeline\Driver\QueryBuilder\QueryBuilder as QueryBuilderTested;
use Spy\Timeline\Driver\QueryBuilder\QueryBuilderFactory;
use mageekguy\atoum;

class QueryBuilder extends atoum\test
{
    public function testLogicalAnd()
    {
        $this->if($qb = new QueryBuilderTested())
            ->exception(function () use ($qb) {
                $qb->logicalAnd();
            })
            ->isInstanceOf('\InvalidArgumentException')
            ->hasMessage('Spy\Timeline\Driver\QueryBuilder\QueryBuilder::createNewOperator accept minimum 2 arguments')
            // add criterias
            ->if($this->mockClass('Spy\Timeline\Driver\QueryBuilder\Criteria\CriteriaInterface', '\Mock'))
            ->and($criteria = new \Mock\CriteriaInterface())
            ->and($criteria2 = new \Mock\CriteriaInterface())
            ->and($resultExpected = new Operator())
            ->and($resultExpected->setType(Operator::TYPE_AND))
            ->and($resultExpected->setCriterias(array($criteria, $criteria2)))
            ->object($qb->logicalAnd($criteria, $criteria2))
            ->isEqualTo($qb->createNewOperator(Operator::TYPE_AND, array($criteria, $criteria2)))
            ->isEqualTo($resultExpected)
        ;
    }

    public function testLogicalOr()
    {
        $this->if($qb = new QueryBuilderTested())
            ->exception(function () use ($qb) {
                $qb->logicalOr();
            })
            ->isInstanceOf('\InvalidArgumentException')
            ->hasMessage('Spy\Timeline\Driver\QueryBuilder\QueryBuilder::createNewOperator accept minimum 2 arguments')
            // add criterias
            ->if($this->mockClass('Spy\Timeline\Driver\QueryBuilder\Criteria\CriteriaInterface', '\Mock'))
            ->and($criteria = new \Mock\CriteriaInterface())
            ->and($criteria2 = new \Mock\CriteriaInterface())
            ->and($resultExpected = new Operator())
            ->and($resultExpected->setType(Operator::TYPE_OR))
            ->and($resultExpected->setCriterias(array($criteria, $criteria2)))
            ->object($qb->logicalOr($criteria, $criteria2))
            ->isEqualTo($qb->createNewOperator(Operator::TYPE_OR, array($criteria, $criteria2)))
            ->isEqualTo($resultExpected)
        ;
    }

    public function testField()
    {
        $this->if($qb = new QueryBuilderTested())
            ->exception(function () use ($qb) {
                $qb->field('unknownfield');
            })
                ->isInstanceOf('\InvalidArgumentException')
                ->hasMessage('Field "unknownfield" not supported, prefer: context, verb, createdAt, type, text, model, identifier')
            // real field
            ->and($resultExpected = new Asserter())
            ->and($resultExpected->field('createdAt'))
            ->object($qb->field('createdAt'))
            ->isEqualTo($resultExpected)
        ;
    }

    public function testOrderBy()
    {
        $this->if($qb = new QueryBuilderTested())
            ->exception(function () use ($qb) {
                $qb->orderBy('unknownfield', 'ASC');
            })
                ->isInstanceOf('\InvalidArgumentException')
                ->hasMessage('Field "unknownfield" not supported, prefer: context, verb, createdAt, type, text, model, identifier')
            // bad order
            ->exception(function () use ($qb) {
                $qb->orderBy('createdAt', 'badorder');
            })
                ->isInstanceOf('\InvalidArgumentException')
                ->hasMessage('Order "badorder" not supported, prefer: ASC or DESC')
        ;
    }

    public function testGetAvailableFields()
    {
        $this->if($qb = new QueryBuilderTested())
            ->array($qb->getAvailableFields())
            ->isNotEmpty()
        ;
    }

    public function testAddSubject()
    {
        $this->if($qb = new QueryBuilderTested())
            ->and($this->mockClass('Spy\Timeline\Model\ComponentInterface', '\Mock'))
            ->and($subject  = new \Mock\ComponentInterface())
            ->and($subject->getMockController()->getHash = 'hash')
            ->and($qb->addSubject($subject))

            ->array($qb->getSubjects())->hasSize(1)

            ->and($subject2  = new \Mock\ComponentInterface())
            ->and($subject2->getMockController()->getHash = 'hash')
            ->and($qb->addSubject($subject2))

            ->and($subject3  = new \Mock\ComponentInterface())
            ->and($subject3->getMockController()->getHash = 'hash2')
            ->and($qb->addSubject($subject3))

            ->array($qb->getSubjects())->hasSize(2)
        ;
    }

    public function testFromArray()
    {
        $this->if($this->mockClass('Spy\Timeline\Driver\QueryBuilder\QueryBuilderFactory', '\Mock'))
            ->and($this->mockClass('Spy\Timeline\Driver\QueryBuilder\Criteria\CriteriaInterface', '\Mock'))
            ->and($this->mockClass('Spy\Timeline\Driver\ActionManagerInterface', '\Mock'))
            ->and($this->mockClass('Spy\Timeline\Model\ComponentInterface', '\Mock'))
            ->and($criteria = new \Mock\CriteriaInterface())
            ->and($factory = new \Mock\QueryBuilderFactory())
            ->and($factory->getMockController()->createAsserterFromArray = $criteria)
            ->and($component = new \Mock\ComponentInterface())
            ->and($actionManager = new \Mock\ActionManagerInterface())
            ->and($actionManager->getMockController()->findComponents = array($component))
            ->and($qb = new QueryBuilderTested($factory))
            ->and($data = array(
                'subject' => array(
                    'hash',
                ),
                'page' => 10,
                'max_per_page' => 100,
                'sort' => array(
                    'createdAt',
                    'DESC',
                ),
                'criterias' => array(
                    'type' => 'expr',
                ),
            ))
            ->and($resultExpected = new QueryBuilderTested($factory))
            ->and($resultExpected->setPage(10))
            ->and($resultExpected->setMaxPerPage(100))
            ->and($resultExpected->orderBy('createdAt', 'DESC'))
            ->and($resultExpected->setCriterias($criteria))
            ->and($resultExpected->addSubject($component))
            ->object($data = $qb->fromArray($data, $actionManager))
            ->isEqualTo($resultExpected)
        ;
    }

    public function testToArray()
    {
        $this->if($qb = new QueryBuilderTested())
            ->and($this->mockClass('Spy\Timeline\Driver\QueryBuilder\Criteria\CriteriaInterface', '\Mock'))
            ->and($this->mockClass('Spy\Timeline\Model\ComponentInterface', '\Mock'))
            ->and($subject  = new \Mock\ComponentInterface())
            ->and($subject->getMockController()->getHash = 'hash')
            ->and($criteria = new \Mock\CriteriaInterface())
            ->and($criteria->getMockController()->toArray = 'TOARRAYRESULT')
            ->and($qb->setCriterias($criteria))
            ->and($qb->setPage(10))
            ->and($qb->setMaxPerPage(100))
            ->and($qb->orderBy('createdAt', 'DESC'))
            ->and($qb->addSubject($subject))
            ->array($qb->toArray())
            ->isIdenticalTo(
                array(
                    'subject' => array(
                        'hash',
                    ),
                    'page' => 10,
                    'max_per_page' => 100,
                    'criterias' => 'TOARRAYRESULT',
                    'sort' => array(
                        'createdAt',
                        'DESC',
                    ),
                )
            )
        ;
    }
}
