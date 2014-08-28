<?php

namespace Spy\Timeline\Driver\QueryBuilder\Criteria;

class Asserter implements CriteriaInterface
{
    const ASSERTER_EQUAL               = '=';
    const ASSERTER_NOT_EQUAL           = '!=';
    const ASSERTER_IN                  = 'IN';
    const ASSERTER_NOT_IN              = 'NOT IN';
    const ASSERTER_LIKE                = 'LIKE';
    const ASSERTER_NOT_LIKE            = 'NOT LIKE';
    const ASSERTER_LOWER_THAN          = '<';
    const ASSERTER_LOWER_THAN_EQUAL    = '<=';
    const ASSERTER_GREATER_THAN       = '>';
    const ASSERTER_GREATER_THAN_EQUAL = '>=';

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
     * greater than
     *
     * @param mixed $value value
     *
     * @return Asserter
     */
    public function gt($value)
    {
        return $this->create(self::ASSERTER_GREATER_THAN, $this->transform($value));
    }

    /**
     * greater than equal
     *
     * @param mixed $value value
     *
     * @return Asserter
     */
    public function gte($value)
    {
        return $this->create(self::ASSERTER_GREATER_THAN_EQUAL, $this->transform($value));
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
            ->create($operator, $value)
        ;
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
