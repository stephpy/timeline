<?php

namespace Spy\Timeline\Notification\Unread;

use Spy\Timeline\Driver\TimelineManagerInterface;
use Spy\Timeline\Model\ActionInterface;
use Spy\Timeline\Model\ComponentInterface;
use Spy\Timeline\Notification\NotifierInterface;
use Spy\Timeline\Spread\Entry\EntryCollection;

class UnreadNotificationManager implements NotifierInterface
{
    /**
     * @var TimelineManagerInterface
     */
    protected $timelineManager;

    /**
     * @param TimelineManagerInterface $timelineManager timelineManager
     */
    public function __construct(TimelineManagerInterface $timelineManager)
    {
        $this->timelineManager = $timelineManager;
    }

    /**
     * {@inheritdoc}
     */
    public function notify(ActionInterface $action, EntryCollection $entryCollection)
    {
        $i = 0;
        foreach ($entryCollection as $context => $entries) {
            foreach ($entries as $entry) {
                $i++;
                $this->timelineManager->createAndPersist($action, $entry->getSubject(), $context, 'notification');
            }
        }

        if ($i > 0) {
            $this->timelineManager->flush();
        }
    }

    /**
     * @param ComponentInterface $subject The subject
     * @param string             $context The context
     * @param array              $options An array of options (offset, limit), see your timelineManager
     *
     * @return array
     */
    public function getUnreadNotifications(ComponentInterface $subject, $context = "GLOBAL", array $options = array())
    {
        $options['context'] = $context;
        $options['type']    = 'notification';

        return $this->timelineManager->getTimeline($subject, $options);
    }

    /**
     * count how many timeline had not be read
     *
     * @param ComponentInterface $subject The subject
     * @param string             $context The context
     *
     * @return integer
     */
    public function countKeys(ComponentInterface $subject, $context = "GLOBAL")
    {
        $options = array(
            'context' => $context,
            'type'    => 'notification',
        );

        return $this->timelineManager->countKeys($subject, $options);
    }

    /**
     * @param ComponentInterface $subject          The subject
     * @param string             $timelineActionId The actionId
     * @param string             $context          The context
     */
    public function markAsReadAction(ComponentInterface $subject, $timelineActionId, $context = 'GLOBAL')
    {
        $this->markAsReadActions(array(
            array($context, $subject, $timelineActionId)
        ));
    }

    /**
     * Give an array like this
     * array(
     *   array( *CONTEXT*, *SUBJECT*, *KEY* )
     *   array( *CONTEXT*, *SUBJECT*, *KEY* )
     *   ....
     * )
     *
     * @param array $actions
     */
    public function markAsReadActions(array $actions)
    {
        $options = array(
            'type' => 'notification',
        );

        foreach ($actions as $action) {
            list($context, $subject, $actionId) = $action;

            $options['context'] = $context;

            $this->timelineManager->remove($subject, $actionId, $options);
        }

        $this->timelineManager->flush();
    }

    /**
     * markAllAsRead
     *
     * @param ComponentInterface $subject subject
     * @param string             $context The context
     */
    public function markAllAsRead(ComponentInterface $subject, $context = "GLOBAL")
    {
        $options = array(
            'context' => $context,
            'type'    => 'notification',
        );

        $this->timelineManager->removeAll($subject, $options);
        $this->timelineManager->flush();
    }
}
