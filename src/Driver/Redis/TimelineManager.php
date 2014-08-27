<?php

namespace Spy\Timeline\Driver\Redis;

use Spy\Timeline\Driver\TimelineManagerInterface;
use Spy\Timeline\Model\ActionInterface;
use Spy\Timeline\Model\ComponentInterface;
use Spy\Timeline\Model\TimelineInterface;
use Spy\Timeline\ResultBuilder\ResultBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimelineManager implements TimelineManagerInterface
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
     * @var boolean
     */
    protected $pipeline;

    /**
     * @var array
     */
    protected $persistedDatas = array();

    /**
     * @param object                 $client        client
     * @param ResultBuilderInterface $resultBuilder resultBuilder
     * @param string                 $prefix        prefix
     * @param boolean                $pipeline      pipeline
     */
    public function __construct($client, ResultBuilderInterface $resultBuilder, $prefix, $pipeline = true)
    {
        $this->client        = $client;
        $this->resultBuilder = $resultBuilder;
        $this->prefix        = $prefix;
        $this->pipeline      = $pipeline;
    }

    /**
     * {@inheritdoc}
     */
    public function getTimeline(ComponentInterface $subject, array $options = array())
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(array(
            'page'         => 1,
            'max_per_page' => 10,
            'type'         => TimelineInterface::TYPE_TIMELINE,
            'context'      => 'GLOBAL',
            'filter'       => true,
            'paginate'     => false,
        ));

        $options = $resolver->resolve($options);

        $token   = new Pager\PagerToken($this->getRedisKey($subject, $options['context'], $options['type']));

        return $this->resultBuilder->fetchResults($token, $options['page'], $options['max_per_page'], $options['filter'], $options['paginate']);
    }

    /**
     * {@inheritdoc}
     */
    public function countKeys(ComponentInterface $subject, array $options = array())
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(array(
            'type'    => TimelineInterface::TYPE_TIMELINE,
            'context' => 'GLOBAL',
        ));

        $options = $resolver->resolve($options);

        $redisKey = $this->getRedisKey($subject, $options['context'], $options['type']);

        return $this->client->zCard($redisKey);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(ComponentInterface $subject, $actionId, array $options = array())
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(array(
            'type'    => TimelineInterface::TYPE_TIMELINE,
            'context' => 'GLOBAL',
        ));

        $options = $resolver->resolve($options);

        $redisKey = $this->getSubjectRedisKey($subject);

        $this->persistedDatas[] = array(
            'zRem',
            $redisKey,
            $actionId,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function removeAll(ComponentInterface $subject, array $options = array())
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(array(
            'type'    => TimelineInterface::TYPE_TIMELINE,
            'context' => 'GLOBAL',
        ));

        $options = $resolver->resolve($options);

        $redisKey = $this->getRedisKey($subject, $options['context'], $options['type']);

        $this->persistedDatas[] = array(
            'del',
            $redisKey,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function createAndPersist(ActionInterface $action, ComponentInterface $subject, $context = 'GLOBAL', $type = TimelineInterface::TYPE_TIMELINE)
    {
        $redisKey = $this->getRedisKey($subject, $context, $type);

        $this->persistedDatas[] = array(
            'zAdd',
            $redisKey,
            $action->getSpreadTime(),
            $action->getId()
        );

        // we want to deploy on a subject action list to enable ->getSubjectActions feature..
        if ('timeline' === $type) {
            $redisKey = $this->getSubjectRedisKey($action->getSubject());

            $this->persistedDatas[] = array(
                'zAdd',
                $redisKey,
                $action->getSpreadTime(),
                $action->getId()
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        if (empty($this->persistedDatas)) {
            return array();
        }

        $client  = $this->client;
        $replies = array();

        if ($this->pipeline) {
            $client = $client->pipeline();
        }

        foreach ($this->persistedDatas as $persistData) {
            switch ($persistData[0]) {
                case 'del':
                    $replies[] = $client->del($persistData[1]);
                    break;
                case 'zAdd':
                    $replies[] = $client->zAdd($persistData[1], $persistData[2], $persistData[3]);
                    break;
                case 'zRem':
                    $replies[] = $client->zRem($persistData[1], $persistData[2]);
                    break;
                default:
                    throw new \OutOfRangeException('This function is not supported');
                    break;
            }
        }

        if ($this->pipeline) {
            //Predis as a specific way to flush pipeline.
            if ($client instanceof \Predis\Pipeline\PipelineContext) {
                $replies = $client->execute();
            } else {
                $replies = $client->exec();
            }
        }

        $this->persistedDatas = array();

        return $replies;
    }

    /**
     * @param ComponentInterface $subject subject
     * @param string             $type    type
     * @param string             $context context
     *
     * @return string
     */
    protected function getRedisKey(ComponentInterface $subject, $type, $context)
    {
        return sprintf('%s:%s:%s:%s', $this->prefix, $subject->getHash(), $type, $context);
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
