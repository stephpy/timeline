<?php

namespace Spy\Timeline\Driver\QueryBuilder;

use Spy\Timeline\Driver\ActionManagerInterface;
use Spy\Timeline\Driver\QueryBuilder\Criteria\CriteriaInterface;
use Spy\Timeline\Driver\QueryBuilder\Criteria\Operator;
use Spy\Timeline\Model\Component;
use Spy\Timeline\Model\ComponentInterface;

class QueryBuilder
{
    /**
     * @var array
     */
    protected $subjects = array();

    /**
     * @var integer
     */
    protected $page = 1;

    /**
     * @var integer
     */
    protected $maxPerPage = 10;

    /**
     * @var array
     */
    protected $criterias;

    /**
     * @var array(<string>$field, <string>way)
     */
    protected $sort;

    /**
     * @var QueryBuilderFactory
     */
    protected $factory;

    /**
     * @var array
     */
    protected static $fieldLocation = array(
        'context'    => 'timeline',
        'createdAt'  => 'action',
        'verb'       => 'action',
        'type'       => 'actionComponent',
        'text'       => 'actionComponent',
        'model'      => 'component',
        'identifier' => 'component',
    );

    /**
     * @param QueryBuilderFactory $factory factory
     */
    public function __construct(QueryBuilderFactory $factory = null)
    {
        if (null === $factory) {
            $factory = new QueryBuilderFactory();
        }

        $this->factory = $factory;
    }

    /**
     * @return CriteriaInterface
     */
    public function logicalAnd()
    {
        return $this->createNewOperator(Operator::TYPE_AND, func_get_args());
    }

    /**
     * @return CriteriaInterface
     */
    public function logicalOr()
    {
        return $this->createNewOperator(Operator::TYPE_OR, func_get_args());
    }

    /**
     * @param string $field field
     */
    public function field($field)
    {
        if (!in_array($field, $this->getAvailableFields())) {
            throw new \InvalidArgumentException(sprintf('Field "%s" not supported, prefer: %s', $field, implode(', ', $this->getAvailableFields())));
        }

        return $this->factory
            ->createAsserter()
            ->field($field)
        ;
    }

    /**
     * @param string $type type
     * @param array  $args args
     *
     * @return Operator
     */
    public function createNewOperator($type, array $args)
    {
        if (empty($args) || count($args) < 2) {
            throw new \InvalidArgumentException(__METHOD__.' accept minimum 2 arguments');
        }

        return $this->factory
            ->createOperator()
            ->setType($type)
            ->setCriterias($args)
        ;
    }

    /**
     * @param ComponentInterface $component component
     *
     * @return QueryBuilder
     */
    public function addSubject(ComponentInterface $component)
    {
        $this->subjects[$component->getHash()] = $component;

        return $this;
    }

    /**
     * @return array
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * @param CriteriaInterface $criteria criteria
     *
     * @return QueryBuilder
     */
    public function setCriterias(CriteriaInterface $criteria)
    {
        $this->criterias = $criteria;

        return $this;
    }

    /**
     * @return CriteriaInterface|null
     */
    public function getCriterias()
    {
        return $this->criterias;
    }

    /**
     * @param integer $page page
     *
     * @return QueryBuilder
     */
    public function setPage($page)
    {
        $this->page = (int) $page;

        return $this;
    }

    /**
     * @param string $maxPerPage maxPerPage
     *
     * @return QueryBuilder
     */
    public function setMaxPerPage($maxPerPage)
    {
        $this->maxPerPage = (int) $maxPerPage;

        return $this;
    }

    /**
     * @param string $field field
     * @param string $order order
     *
     * @return QueryBuilder
     */
    public function orderBy($field, $order)
    {
        if (!in_array($field, $this->getAvailableFields())) {
            throw new \InvalidArgumentException(sprintf('Field "%s" not supported, prefer: %s', $field, implode(', ', $this->getAvailableFields())));
        }

        if (!in_array($order, array('ASC', 'DESC'))) {
            throw new \InvalidArgumentException(sprintf('Order "%s" not supported, prefer: ASC or DESC', $order));
        }

        $this->sort = array($field, $order);

        return $this;
    }

    /**
     * @param string $field field
     *
     * @return string
     */
    public static function getFieldLocation($field)
    {
        return self::$fieldLocation[$field];
    }

    /**
     * @return array
     */
    public function getAvailableFields()
    {
        return array(
            'context',
            'verb',
            'createdAt',
            'type',
            'text',
            'model',
            'identifier',
        );
    }

    /**
     * @param  array                  $data          data
     * @param  ActionManagerInterface $actionManager actionManager
     * @throws \Exception
     * @return $this
     */
    public function fromArray(array $data, ActionManagerInterface $actionManager = null)
    {
        if (isset($data['criterias']) && isset($data['criterias']['type'])) {
            $criterias = $data['criterias'];
            $type      = $criterias['type'];

            if ('operator' == $type) {
                $method = 'createOperatorFromArray';
            } elseif ('expr' == $type) {
                $method = 'createAsserterFromArray';
            } else {
                throw new \Exception('Invalid array, cannot be unserialized');
            }

            $this->setCriterias($this->factory->{$method}($criterias));
        }

        if (isset($data['page'])) {
            $this->setPage($data['page']);
        }

        if (isset($data['max_per_page'])) {
            $this->setMaxPerPage($data['max_per_page']);
        }

        if (isset($data['sort'])) {
            list ($field, $order) = $data['sort'];
            $this->orderBy($field, $order);
        }

        if (isset($data['subject']) && !empty($data['subject'])) {
            $subjects = $data['subject'];

            if (!$actionManager) {
                throw new \Exception('Please provide the actionManager to retrieve components');
            }

            $components = $actionManager->findComponents($subjects);

            if (count($components) != count($subjects)) {
                foreach ($components as $component) {
                    // remove existing components from subjects to keep only new one components
                    unset($subjects[array_search($component->getHash(), $subjects)]);
                }

                // create new components
                foreach ($subjects as $subject) {
                    list ($model, $identifier) = explode('#', $subject);
                    $components[] = $actionManager->createComponent($model, unserialize($identifier));
                }
            }

            foreach ($components as $component) {
                $this->addSubject($component);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'subject'         => array_values(
                array_map(function ($v) { return $v->getHash(); }, $this->subjects)
            ),
            'page'            => $this->page,
            'max_per_page'    => $this->maxPerPage,
            'criterias'       => $this->criterias ? $this->criterias->toArray() : null,
            'sort'            => $this->sort,
        );
    }
}
