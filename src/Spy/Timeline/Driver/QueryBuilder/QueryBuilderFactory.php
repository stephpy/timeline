<?php

namespace Spy\Timeline\Driver\QueryBuilder;

use Spy\Timeline\Driver\ActionManagerInterface;
use Spy\Timeline\Driver\QueryBuilder\Criteria\Asserter;
use Spy\Timeline\Driver\QueryBuilder\Criteria\Operator;

/**
 * QueryBuilderFactory
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class QueryBuilderFactory
{
    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        return new QueryBuilder($this);
    }

    /**
     * @param array                  $data          data
     * @param ActionManagerInterface $actionManager actionManager
     *
     * @return QueryBuilder
     */
    public function createQueryBuilderFromArray(array $data, ActionManagerInterface $actionManager = null)
    {
        return $this->createQueryBuilder()
            ->fromArray($data, $actionManager);
    }

    /**
     * @return Operator
     */
    public function createOperator()
    {
        return new Operator();
    }

    /**
     * @param array $data data
     *
     * @return Operator
     */
    public function createOperatorFromArray(array $data)
    {
        return $this->createOperator()
            ->fromArray($data, $this);
    }

    /**
     * @return Asserter
     */
    public function createAsserter()
    {
        return new Asserter();
    }

    /**
     * @param array $data data
     *
     * @return Asserter
     */
    public function createAsserterFromArray(array $data)
    {
        return $this->createAsserter()
            ->fromArray($data);
    }
}
