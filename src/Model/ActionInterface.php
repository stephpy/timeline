<?php

namespace Spy\Timeline\Model;

interface ActionInterface
{
    const STATUS_PENDING   = 'pending';
    const STATUS_PUBLISHED = 'published';
    const STATUS_FROZEN    = 'frozen';

    /**
     * @param string                    $type                 type
     * @param string|ComponentInterface $component            component
     * @param string                    $actionComponentClass actionComponentClass
     *
     * @return ActionInterface
     */
    public function addComponent($type, $component, $actionComponentClass);

    /**
     * @param string $type type
     *
     * @return boolean
     */
    public function hasComponent($type);

    /**
     * @param string $type type
     *
     * @return ComponentInterface|null
     */
    public function getComponent($type);

    /**
     * @return integer
     */
    public function getSpreadTime();

    /**
     * @return boolean
     */
    public function isPublished();

    /**
     * @return boolean
     */
    public function hasDuplicateKey();

    /**
     * @param boolean $duplicated duplicated
     *
     * @return ActionInterface
     */
    public function setIsDuplicated($duplicated);

    /**
     * @return boolean
     */
    public function isDuplicated();

    /**
     * @return ComponentInterface|null
     */
    public function getSubject();

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param mixed $id id
     *
     * @return ActionInterface
     */
    public function setId($id);

    /**
     * @param string$verb verb
     *
     * @return ActionInterface
     */
    public function setVerb($verb);

    /**
     * @return string
     */
    public function getVerb();

    /**
     * @param string $statusCurrent statusCurrent
     *
     * @return ActionInterface
     */
    public function setStatusCurrent($statusCurrent);

    /**
     * @return string
     */
    public function getStatusCurrent();

    /**
     * @param string $statusWanted statusWanted
     *
     * @return ActionInterface
     */
    public function setStatusWanted($statusWanted);

    /**
     * @return string
     */
    public function getStatusWanted();

    /**
     * @param string $duplicateKey duplicateKey
     *
     * @return ActionInterface
     */
    public function setDuplicateKey($duplicateKey);

    /**
     * @return string
     */
    public function getDuplicateKey();

    /**
     * @param integer $duplicatePriority duplicatePriority
     *
     * @return ActionInterface
     */
    public function setDuplicatePriority($duplicatePriority);

    /**
     * @return integer
     */
    public function getDuplicatePriority();

    /**
     * @param \DateTime $createdAt createdAt
     *
     * @return ActionInterface
     */
    public function setCreatedAt(\DateTime $createdAt);

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @param ActionComponentInterface $actionComponent actionComponent
     *
     * @return ActionInterface
     */
    public function addActionComponent(ActionComponentInterface $actionComponent);

    /**
     * @return ActionComponentInterface[]
     */
    public function getActionComponents();
}
