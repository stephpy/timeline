<?php

namespace Spy\Timeline\Model;

interface ComponentInterface
{
    /**
     * Return unique hash for this component.
     *
     * @return string
     */
    public function getHash();

    /**
     * @param string $hash hash
     *
     * @return ComponentInterface
     */
    public function createFromHash($hash);

    /**
     * @param mixed $data data
     *
     * @return Component
     */
    public function setData($data);

    /**
     * @return mixed
     */
    public function getData();

    /**
     * @param mixed $id id
     *
     * @return ComponentInterface
     */
    public function setId($id);

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param string $model model
     *
     * @return ComponentInterface
     */
    public function setModel($model);

    /**
     * @return string
     */
    public function getModel();

    /**
     * @param string $identifier identifier
     *
     * @return ComponentInterface
     */
    public function setIdentifier($identifier);

    /**
     * @return string
     */
    public function getIdentifier();
}
