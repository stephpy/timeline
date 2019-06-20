<?php

namespace Spy\Timeline\Model;

trait HashTrait
{
    /**
     * @var string
     */
    protected $hash;

    /**
     * {@inheritdoc}
     */
    public function buildHash()
    {
        $this->hash = $this->getModel().'#'.serialize($this->getIdentifier());
    }

    /**
     * Gets the resolved hash.
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }
}