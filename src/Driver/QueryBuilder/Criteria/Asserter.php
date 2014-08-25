<?php

namespace Spy\Timeline\Driver\QueryBuilder\Criteria;

/**
 * Asserter
 *
 * @uses CriteriaInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Asserter implements CriteriaInterface
{
    CONST ASSERTER_EQUAL               = '=';
    CONST ASSERTER_NOT_EQUAL           = '!=';
    CONST ASSERTER_IN                  = 'IN';
    CONST ASSERTER_NOT_IN              = 'NOT IN';
    CONST ASSERTER_LIKE                = 'LIKE';
    CONST ASSERTER_NOT_LIKE            = 'NOT LIKE';
    CONST ASSERTER_LOWER_THAN          = '<';
    CONST ASSERTER_LOWER_THAN_EQUAL    = '<=';
    CONST ASSERTER_GREATHER_THAN       = '>';
    CONST ASSERTER_GREATHER_THAN_EQUAL = '>=';

    /**
     * @var string
     */
    protected $field;

    /**
     * @var string
     */
    protected $operator;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param string $field field
     *
     * @return Asserter
     */
    public function field($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Allow to transform value easily for each assertions.
     *
     * @param mixed $value value
     *
     * @return mixed
     */
    public function transform($value)
    {
        return $value;
    }

    public function equals($value)
    {
        return $this->create(self::ASSERTER_EQUAL, $this->transform($value));
    }

    public function notEquals($value)
    {
        return $this->create(self::ASSERTER_NOT_EQUAL, $this->transform($value));
    }

    public function in(array $values)
    {
        return $this->create(self::ASSERTER_IN, $this->transform($values));
    }

    public function notIn(array $values)
    {
        return $this->create(self::ASSERTER_NOT_IN, $this->transform($values));
    }

    public function like($value)
    {
        return $this->create(self::ASSERTER_LIKE, $this->transform($value));
    }

    public function notLike($value)
    {
        return $this->create(self::ASSERTER_NOT_LIKE, $this->transform($value));
    }

    /**
     * lower than
     *
     * @param mixed $value value
     *
     * @return return DateTimeAsserter
     */
    public function lt($value)
    {
        return $this->create(self::ASSERTER_LOWER_THAN, $this->transform($value));
    }

    /**
     * lower than equal
     *
     * @param mixed $value value
     *
     * @return return DateTimeAsserter
     */
    public function lte($value)
    {
        return $this->create(self::ASSERTER_LOWER_THAN_EQUAL, $this->transform($value));
    }

    /**
     * greather than
     *
     * @param mixed $value value
     *
     * @return return DateTimeAsserter
     */
    public function gt($value)
    {
        return $this->create(self::ASSERTER_GREATHER_THAN, $this->transform($value));
    }

    /**
     * greather than equal
     *
     * @param mixed $value value
     *
     * @return return DateTimeAsserter
     */
    public function gte($value)
    {
        return $this->create(self::ASSERTER_GREATHER_THAN_EQUAL, $this->transform($value));
    }


    /**
     * @param string $operator operator
     * @param mixed  $value    value
     *
     * @return Asserter
     */
    public function create($operator, $value)
    {
        $this->operator = $operator;
        $this->value    = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return array(
            'type' => 'expr',
            'value' => array(
                $this->field,
                $this->operator,
                $this->value
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function fromArray(array $data)
    {
        list ($field, $operator, $value) = $data['value'];

        return $this->field($field)
            ->create($operator, $value);
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
