<?php

namespace Spy\Timeline\Driver\Redis;

use Spy\Timeline\Driver\AbstractActionManager;
use Spy\Timeline\Driver\ActionManagerInterface;
use Spy\Timeline\Model\ActionInterface;
use Spy\Timeline\Model\ComponentInterface;
use Spy\Timeline\ResolveComponent\ValueObject\ResolvedComponentData;
use Spy\Timeline\ResultBuilder\ResultBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActionManager extends AbstractActionManager implements ActionManagerInterface
{
    /**
     * @var object
     */
    protected $client;

    /**
     * @var ResultBuilderInterface
     */
    protected $resultBuilder;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @param object                 $client               client
     * @param ResultBuilderInterface $resultBuilder        resultBuilder
     * @param string                 $prefix               prefix
     * @param string                 $actionClass          actionClass
     * @param string                 $componentClass       componentClass
     * @param string                 $actionComponentClass actionComponentClass
     */
    public function __construct($client, ResultBuilderInterface $resultBuilder, $prefix, $actionClass, $componentClass, $actionComponentClass)
    {
        $this->client = $client;
        $this->prefix = $prefix;
        $this->resultBuilder = $resultBuilder;

        parent::__construct($actionClass, $componentClass, $actionComponentClass);
    }

    /**
     * {@inheritdoc}
     */
    public function findActionsWithStatusWantedPublished($limit = 100)
    {
        throw new \Exception('Method '.__METHOD__.' is currently not supported by redis driver');
    }

    /**
     * {@inheritdoc}
     */
    public function countActions(ComponentInterface $subject, $status = ActionInterface::STATUS_PUBLISHED)
    {
        if ($status != ActionInterface::STATUS_PUBLISHED) {
            throw new \Exception('Method '.__METHOD__.' can only retrieve action published');
        }

        $redisKey = $this->getSubjectRedisKey($subject);

        return $this->client->zCard($redisKey);
    }

    /**
     * {@inheritdoc}
     */
    public function getSubjectActions(ComponentInterface $subject, array $options = array())
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(array(
            'page'         => 1,
            'max_per_page' => 10,
            'filter'       => true,
            'paginate'     => false,
        ));

        $options = $resolver->resolve($options);

        $token   = new Pager\PagerToken($this->getSubjectRedisKey($subject));

        return $this->resultBuilder->fetchResults($token, $options['page'], $options['max_per_page'], $options['filter'], $options['paginate']);
    }

    /**
     * {@inheritdoc}
     */
    public function updateAction(ActionInterface $action)
    {
        $action->setId($this->getNextId());

        $this->client->hset($this->getActionKey(), $action->getId(), serialize($action));

        $this->deployActionDependOnDelivery($action);
    }

    /**
     * {@inheritdoc}
     */
    public function findOrCreateComponent($model, $identifier = null, $flush = true)
    {
        return $this->createComponent($model, $identifier, $flush);
    }

    /**
     * {@inheritdoc}
     */
    public function createComponent($model, $identifier = null, $flush = true)
    {
        $resolvedComponentData = $this->resolveModelAndIdentifier($model, $identifier);

        // we do not persist component on redis driver.
        return $this->getComponentFromResolvedComponentData($resolvedComponentData);
    }

    /**
     * {@inheritdoc}
     */
    public function flushComponents()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function findComponents(array $concatIdents)
    {
        $components = array();

        foreach ($concatIdents as $concatIdent) {
            $component    = new $this->componentClass();
            $components[] = $component->createFromHash($concatIdent);
        }

        return $components;
    }

    /**
     * {@inheritdoc}
     */
    public function findComponentWithHash($hash)
    {
        $component = new $this->componentClass();
        $component = $component->createFromHash($hash);

        return $component;
    }

    /**
     * @return integer|double
     */
    protected function getNextId()
    {
        return ($this->client->hlen($this->getActionKey()) + 1);
    }

    /**
     * @return string
     */
    protected function getActionKey()
    {
        return sprintf('%s:action', $this->prefix);
    }

    /**
     * @param ComponentInterface $subject subject
     *
     * @return string
     */
    protected function getSubjectRedisKey(ComponentInterface $subject)
    {
        return sprintf('%s:%s', $this->prefix, $subject->getHash());
    }
}
