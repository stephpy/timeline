<?php

namespace Spy\Timeline;

class ServiceLocator
{
    /**
     * @var \Pimple
     */
    protected $container;

    /**
     * Build container
     */
    public function __construct()
    {
        $this->buildContainer();
    }

    /**
     * Build default container.
     */
    public function buildContainer()
    {
        if (!class_exists('\Pimple')) {
            throw new \Exception('Please install Pimple.');
        }

        $c = new \Pimple();
        // ---- classes ----

        // filters
        $c['filter.manager.class']                   = 'Spy\Timeline\Filter\FilterManager';
        $c['filter.duplicate_key.class']             = 'Spy\Timeline\Filter\DuplicateKey';
        $c['filter.data_hydrator.class']             = 'Spy\Timeline\Filter\DataHydrator';
        $c['filter.data_hydrator.filter_unresolved'] = false;

        // notifications
        $c['unread_notifications.class']             = 'Spy\Timeline\Notification\Unread\UnreadNotificationManager';

        // query builder
        $c['query_builder.factory.class']            = 'Spy\Timeline\Driver\QueryBuilder\QueryBuilderFactory';
        $c['query_builder.class']                    = 'Spy\Timeline\Driver\QueryBuilder\QueryBuilder';
        $c['query_builder.asserter.class']           = 'Spy\Timeline\Driver\QueryBuilder\Criteria\Asserter';
        $c['query_builder.operator.class']           = 'Spy\Timeline\Driver\QueryBuilder\Criteria\Operator';

        // result builder
        $c['result_builder.class']                   = 'Spy\Timeline\ResultBuilder\ResultBuilder';

        // deployer
        $c['spread.deployer.class']                  = 'Spy\Timeline\Spread\Deployer';
        $c['spread.entry_collection.class']          = 'Spy\Timeline\Spread\Entry\EntryCollection';
        $c['spread.on_subject']                      = true;
        $c['spread.on_global_context']               = true;
        $c['spread.batch_size']                      = 50;
        $c['spread.delivery']                        = 'immediate';

        // ---- services ----

        // filters
        $c['filter.manager'] = $c->share(function ($c) {
            return new $c['filter.manager.class']();
        });

        $c['filter.duplicate_key'] = $c->share(function ($c) {
            return new $c['filter.duplicate_key.class']();
        });

        $c['filter.data_hydrator'] = $c->share(function ($c) {
            return new $c['filter.data_hydrator.class'](
                $c['filter.data_hydrator.filter_unresolved']
            );
        });

        // notifications

        $c['unread_notifications'] = $c->share(function ($c) {
            return new $c['unread_notifications.class'](
                $c['timeline_manager']
            );
        });

        // query_builder

        $c['query_builder.factory'] = $c->share(function ($c) {
            return new $c['query_builder.factory.class'](
                $c['query_builder.class'],
                $c['query_builder.asserter.class'],
                $c['query_builder.operator.class']
            );
        });

        // result builder

        $c['result_builder'] = $c->share(function ($c) {
            $instance = new $c['result_builder.class'](
                $c['query_executor'],
                $c['filter.manager']
            );

            $instance->setPager($c['pager']);

            return $instance;
        });

        // deployers

        $c['spread.deployer'] = $c->share(function ($c) {
            $instance = new $c['spread.deployer.class'](
                $c['timeline_manager'],
                $c['spread.entry_collection'],
                $c['spread.on_subject'],
                $c['spread.batch_size']
            );

            $instance->setDelivery($c['spread.delivery']);

            return $instance;
        });

        $c['spread.entry_collection'] = $c->share(function ($c) {
            return new $c['spread.entry_collection.class'](
                $c['spread.on_global_context'],
                $c['spread.batch_size']
            );
        });

        $this->container = $c;
    }

    public function addRedisDriver($client)
    {
        $c = $this->container;

        $c['timeline_manager.class']  = 'Spy\Timeline\Driver\Redis\TimelineManager';
        $c['action_manager.class']    = 'Spy\Timeline\Driver\Redis\ActionManager';
        $c['query_executor.class']    = 'Spy\Timeline\Driver\Redis\QueryExecutor';
        $c['pager.class']             = 'Spy\Timeline\Driver\Redis\Pager\Pager';
        $c['redis.prefix']            = 'spy_timeline';
        $c['redis.pipeline']          = true;
        $c['class.action']            = 'Spy\Timeline\Model\Action';
        $c['class.component']         = 'Spy\Timeline\Model\Component';
        $c['class.action_component']  = 'Spy\Timeline\Model\ActionComponent';
        $c['class.component_data_resolver'] = 'Spy\Timeline\ResolveComponent\BasicComponentDataResolver';
        $c['redis.client'] = $client;

        $c['timeline_manager'] = $c->share(function ($c) {
            return new $c['timeline_manager.class'](
                $c['redis.client'],
                $c['result_builder'],
                $c['redis.prefix'],
                $c['redis.pipeline']
            );
        });

        $c['action_manager'] = $c->share(function ($c) {
            $instance = new $c['action_manager.class'](
                $c['redis.client'],
                $c['result_builder'],
                $c['redis.prefix'],
                $c['class.action'],
                $c['class.component'],
                $c['class.action_component']
            );

            $instance->setDeployer($c['spread.deployer']);
            $instance->setComponentDataResolver($c['class.component_data_resolver']);

            return $instance;
        });

        $c['query_executor'] = $c->share(function ($c) {
            return new $c['query_executor.class'](
                $c['redis.client'],
                $c['redis.prefix']
            );
        });

        $c['pager'] = $c->share(function ($c) {
            return new $c['pager.class'](
                $c['redis.client'],
                $c['redis.prefix']
            );
        });

        $this->container = $c;
    }

    /**
     * @return \Pimple
     */
    public function getContainer()
    {
        return $this->container;
    }
}
