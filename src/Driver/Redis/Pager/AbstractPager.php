<?php

namespace Spy\Timeline\Driver\Redis\Pager;

abstract class AbstractPager
{
    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var object
     */
    protected $client;

    /**
     * @param object $client client
     * @param string $prefix prefix
     */
    public function __construct($client, $prefix)
    {
        $this->client = $client;
        $this->prefix = $prefix;
    }

    /**
     * @param array $ids ids
     *
     * @return array
     */
    public function findActionsForIds(array $ids)
    {
        if (empty($ids)) {
            return array();
        }

        $datas = $this->client->hmget($this->getActionKey(), $ids);

        return array_values(
            array_map(
                function ($v) {
                    return unserialize($v);
                },
                $datas
            )
        );
    }

    /**
     * @return string
     */
    protected function getActionKey()
    {
        return sprintf('%s:action', $this->prefix);
    }
}
