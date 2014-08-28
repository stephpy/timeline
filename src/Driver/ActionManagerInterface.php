<?php

namespace Spy\Timeline\Driver;

use Spy\Timeline\Model\ActionInterface;
use Spy\Timeline\Model\ComponentInterface;
use Spy\Timeline\ResultBuilder\Pager\PagerInterface;

interface ActionManagerInterface
{
    /**
     * @param integer $limit limit
     *
     * @return ActionInterface[]
     */
    public function findActionsWithStatusWantedPublished($limit = 100);

    /**
     * @param ComponentInterface $subject subject
     * @param string             $status  status
     *
     * @return integer
     */
    public function countActions(ComponentInterface $subject, $status = ActionInterface::STATUS_PUBLISHED);

    /**
     * @param ComponentInterface $subject subject
     * @param array              $options page, max_per_page, status, filter, paginate
     *
     * @return ActionInterface[]|PagerInterface When option paginate is true this will return a PagerInterface,
     *                                          else this will return an ActionInterface[].
     */
    public function getSubjectActions(ComponentInterface $subject, array $options = array());

    /**
     * Saves an action.
     *
     * @param ActionInterface $action action
     */
    public function updateAction(ActionInterface $action);

    /**
     * Creates a new action.
     *
     * @param object $subject    Can be a ComponentInterface or an other one object.
     * @param string $verb       The verb
     * @param array  $components An array of ComponentInterface or other objects.
     *
     * @return ActionInterface
     */
    public function create($subject, $verb, array $components = array());

    /**
     * Find a component or create it.
     *
     * @param string|object     $model      pass an object and second argument will be ignored.
     *                                      it'll be replaced by $model->getId();
     * @param null|string|array $identifier pass an array for composite keys.
     * @param boolean           $flush      is component flushed with this method ?
     *
     * @return ComponentInterface
     */
    public function findOrCreateComponent($model, $identifier = null, $flush = true);

    /**
     * create component.
     *
     * @param string|object     $model      pass an object and second argument will be ignored.
     *                                      it'll be replaced by $model->getId();
     * @param null|string|array $identifier pass an array for composite keys.
     * @param boolean           $flush      is component flushed with this method ?
     *
     * @return ComponentInterface
     */
    public function createComponent($model, $identifier = null, $flush = true);

    /**
     * flushComponents
     */
    public function flushComponents();

    /**
     * @param array $hashes hashes
     *
     * @return ComponentInterface[]
     */
    public function findComponents(array $hashes);

    /**
     * @param string $hash hash
     *
     * @return ComponentInterface|null
     */
    public function findComponentWithHash($hash);
}
