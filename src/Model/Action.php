<?php

namespace Spy\Timeline\Model;

class Action implements ActionInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $verb;

    /**
     * @var string
     */
    protected $statusCurrent = self::STATUS_PENDING;

    /**
     * @var string
     */
    protected $statusWanted = self::STATUS_PUBLISHED;

    /**
     * @var string
     */
    protected $duplicateKey;

    /**
     * @var integer
     */
    protected $duplicatePriority;

    /**
     * @var boolean
     */
    protected $duplicated = false;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var array
     */
    protected $actionComponents;

    /**
     * @var array
     */
    protected $timelines;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt        = new \DateTime();
        $this->actionComponents = array();
        $this->timelines        = array();
    }

    /**
     * {@inheritdoc}
     */
    public function addComponent($type, $component, $actionComponentClass)
    {
        $actionComponent = new $actionComponentClass();
        $actionComponent->setType($type);

        if ($component instanceof ComponentInterface) {
            $actionComponent->setComponent($component);
        } elseif (is_scalar($component)) {
            $actionComponent->setText($component);
        } else {
            throw new \InvalidArgumentException('Component has to be a ComponentInterface or a scalar');
        }

        $this->addActionComponent($actionComponent);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasComponent($type)
    {
        foreach ($this->getActionComponents() as $actionComponent) {
            if ($actionComponent->getType() == $type) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getComponent($type)
    {
        foreach ($this->getActionComponents() as $actionComponent) {
            if ($actionComponent->getType() == $type) {
                return $actionComponent->getText() ?: $actionComponent->getComponent();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSpreadTime()
    {
        return time();
    }

    /**
     * {@inheritdoc}
     */
    public function isPublished()
    {
        return $this->statusCurrent == self::STATUS_PUBLISHED;
    }

    /**
     * {@inheritdoc}
     */
    public function hasDuplicateKey()
    {
        return null !== $this->duplicateKey;
    }

    /**
     * {@inheritdoc}
     */
    public function setIsDuplicated($duplicated)
    {
        $this->duplicated = (bool) $duplicated;
    }

    /**
     * {@inheritdoc}
     */
    public function isDuplicated()
    {
        return (bool) $this->duplicated;
    }

    /**
     * {@inheritdoc}
     */
    public function getValidStatus()
    {
        return array(
            self::STATUS_PENDING,
            self::STATUS_PUBLISHED,
            self::STATUS_FROZEN,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function isValidStatus($status)
    {
        return in_array((string) $status, $this->getValidStatus());
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject()
    {
        return $this->getComponent('subject');
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
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setVerb($verb)
    {
        $this->verb = $verb;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getVerb()
    {
        return $this->verb;
    }

    /**
     * {@inheritdoc}
     */
    public function setStatusCurrent($statusCurrent)
    {
        if (!$this->isValidStatus($statusCurrent)) {
            throw new \InvalidArgumentException(sprintf('Status "%s" is not valid, (%s)', $statusCurrent, implode(', ', $this->getValidStatus())));
        }

        $this->statusCurrent = $statusCurrent;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCurrent()
    {
        return $this->statusCurrent;
    }

    /**
     * {@inheritdoc}
     */
    public function setStatusWanted($statusWanted)
    {
        if (!$this->isValidStatus($statusWanted)) {
            throw new \InvalidArgumentException(sprintf('Status "%s" is not valid, (%s)', $statusWanted, implode(', ', $this->getValidStatus())));
        }

        $this->statusWanted = $statusWanted;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusWanted()
    {
        return $this->statusWanted;
    }

    /**
     * {@inheritdoc}
     */
    public function setDuplicateKey($duplicateKey)
    {
        $this->duplicateKey = $duplicateKey;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDuplicateKey()
    {
        return $this->duplicateKey;
    }

    /**
     * {@inheritdoc}
     */
    public function setDuplicatePriority($duplicatePriority)
    {
        $this->duplicatePriority = (int) $duplicatePriority;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDuplicatePriority()
    {
        return (int) $this->duplicatePriority;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function addActionComponent(ActionComponentInterface $actionComponent)
    {
        $actionComponent->setAction($this);
        $type = $actionComponent->getType();

        foreach ($this->getActionComponents() as $key => $ac) {
            if ($ac->getType() == $type) {
                unset($this->actionComponents[$key]);
            }
        }

        $this->actionComponents[] = $actionComponent;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getActionComponents()
    {
        return $this->actionComponents;
    }

    /**
     * {@inheritdoc}
     */
    public function addTimeline(TimelineInterface $timeline)
    {
        $timeline->setAction($this);

        $this->timelines[] = $timeline;

        return $this;
    }

    /**
     * @return array
     */
    public function getTimelines()
    {
        return $this->timelines;
    }
}
