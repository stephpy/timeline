<?php

namespace Spy\Timeline\Model;

class Component implements ComponentInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $model;

    /**
     * @var array
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $hash;

    /**
     * Data defined on this component.
     *
     * @var mixed
     */
    protected $data;

    /**
     * {@inheritdoc}
     */
    public function buildHash()
    {
        $this->hash = $this->getModel().'#'.serialize($this->getIdentifier());
    }

    /**
     * {@inheritdoc}
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * {@inheritdoc}
     */
    public function createFromHash($hash)
    {
        $data = explode('#', $hash);
        if (count($data) == 1) {
            throw new \InvalidArgumentException('Invalid hash, must be formatted {model}#{hash or identifier}');
        }

        $model      = array_shift($data);
        $identifier = unserialize(implode('', $data));

        $this->setModel($model);
        $this->setIdentifier($identifier);

        return $this;
    }

    /**
     * serialization fields
     */
    public function __sleep()
    {
        return array('id', 'model', 'identifier', 'hash');
    }

    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setModel($model)
    {
        $this->model = $model;

        if (null !== $this->getIdentifier()) {
            $this->buildHash();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * {@inheritdoc}
     */
    public function setIdentifier($identifier)
    {
        if (is_scalar($identifier)) {
            // to avoid issue of serialization.
            $identifier = (string) $identifier;
        } elseif (!is_array($identifier)) {
            throw new \InvalidArgumentException('Identifier must be a scalar or an array');
        }

        $this->identifier = $identifier;

        if (null !== $this->getModel()) {
            $this->buildHash();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }
}
