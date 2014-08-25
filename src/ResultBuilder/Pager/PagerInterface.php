<?php

namespace Spy\Timeline\ResultBuilder\Pager;

/**
 * PagerInterface
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
interface PagerInterface
{
    /**
     * @param mixed $target  target
     * @param int   $page    page
     * @param int   $limit   limit
     * @param array $options options
     *
     * @return mixed
     */
    public function paginate($target, $page = 1, $limit = 10);

    /**
     * @return integer
     */
    public function getPage();

    /**
     * @return integer
     */
    public function getLastPage();

    /**
     * @return boolean
     */
    public function haveToPaginate();

    /**
     * @return integer
     */
    public function getNbResults();

    /**
     * @param array $items items
     */
    public function setItems(array $items);
}
