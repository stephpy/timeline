<?php

namespace Spy\Timeline\Driver\QueryBuilder\Criteria;

use Spy\Timeline\Driver\QueryBuilder\QueryBuilderFactory;

class Operator implements CriteriaInterface
{
    const TYPE_AND = 'AND';
    const TYPE_OR  = 'OR';

    /**
     * @var string
     */
    protected $type;

    /**
     * @var CriteriaInterface[]
     */
    protected $criterias = array();

    /**
     * @param string $type type
     *
     * @return Operator
     */
    public function setType($type)
    {
        if (!in_array($type, $this->getAvailableTypes())) {
            throw new \InvalidArgumentException(sprintf('Type "%s" not supported', $type));
        }

        $this->type = $type;

        return $this;
    }

    /**
     * @param array $criterias criterias
     *
     * @return Operator
     */
    public function setCriterias(array $criterias)
    {
        foreach ($criterias as $criteria) {
            $this->addCriteria($criteria);
        }

        return $this;
    }

    /**
     * @param CriteriaInterface $criteria criteria
     */
    public function addCriteria(CriteriaInterface $criteria)
    {
        $this->criterias[] = $criteria;
    }

    /**
     * @return array
     */
    public function getAvailableTypes()
    {
        return array(
            self::TYPE_AND,
            self::TYPE_OR,
        );
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return CriteriaInterface[]
     */
    public function getCriterias()
    {
        return $this->criterias;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $criterias = array_map(function ($criteria) {
            return $criteria->toArray();
        }, $this->getCriterias());

        return array(
            'type'      => 'operator',
            'value'     => $this->getType(),
            'criterias' => $criterias,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function fromArray(array $data, QueryBuilderFactory $factory)
    {
        $criterias = array_map(function ($v) use ($factory) {
            if ('operator' == $v['type']) {
                return $factory->createOperatorFromArray($v);
            } elseif ('expr' == $v['type']) {
                return $factory->createAsserterFromArray($v);
            } else {
                throw new \InvalidArgumentException(sprintf('Type "%s" is not supported, use expr or operator.', $v['type']));
            }
        }, $data['criterias']);

        $this->setType($data['value']);
        $this->setCriterias($criterias);

        return $this;
    }
}
