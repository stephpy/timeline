<?php

namespace Spy\Timeline\Model;

interface TimelineInterface
{
    const TYPE_TIMELINE = 'timeline';

    /**
     * {@inheritdoc}
     */
    public function setId($id);

    /**
     * {@inheritdoc}
     */
    public function getId();

    /**
     * @param  string            $context
     * @return TimelineInterface
     */
    public function setContext($context);

    /**
     * @return string
     */
    public function getContext();

    /**
     * @param  string            $type
     * @return TimelineInterface
     */
    public function setType($type);

    /**
     * @return string
     */
    public function getType();

    /**
     * @param  \DateTime         $createdAt
     * @return TimelineInterface
     */
    public function setCreatedAt(\DateTime $createdAt);

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @param  ComponentInterface $subject
     * @return TimelineInterface
     */
    public function setSubject(ComponentInterface $subject);

    /**
     * @return ComponentInterface
     */
    public function getSubject();

    /**
     * @param  ActionInterface   $action
     * @return TimelineInterface
     */
    public function setAction(ActionInterface $action);

    /**
     * @return ActionInterface
     */
    public function getAction();
}
