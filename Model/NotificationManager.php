<?php

namespace Mgilet\NotificationBundle\Manager;


use Doctrine\Bundle\DoctrineBundle\Registry;
use Mgilet\NotificationBundle\Model\AbstractNotification;
use Mgilet\NotificationBundle\Model\UserNotificationInterface;

class NotificationManager
{

    private $om;

    /**
     * NotificationManager constructor.
     * @param Registry $om
     * @param $class
     */
    public function __construct(Registry $om, $class)
    {
        $this->om = $om;
        $this->class = $class;
    }

    /**
     * Create a notification
     * @param $subject
     * @param null $message
     * @param null $link
     * @return mixed
     */
    public function createNotification($subject, $message = null, $link = null)
    {
        $notification = new $this->class;
        $notification->setSubject($subject)
            ->setMessage($message)
            ->setLink($link);

        return $notification;
    }

    /**
     * Add a notification to a user
     * @param UserNotificationInterface $user
     * @param AbstractNotification $notification
     */
    public function addNotification(UserNotificationInterface $user, AbstractNotification $notification)
    {
        $user->addNotification($notification);
        $this->om->persist($user);
        $this->om->flush();
    }

    /**
     * Delete a notification
     * @param AbstractNotification $notification
     */
    public function removeNotification(AbstractNotification $notification)
    {
        $this->om->remove($notification);
        $this->om->persist($notification);
        $this->om->flush();
    }

    /**
     * Mark a notification as seen
     * @param AbstractNotification $notification
     */
    public function markAsSeen(AbstractNotification $notification)
    {
        $notification->setSeen(true);
        $this->om->persist($notification);
        $this->om->flush();
    }

    /**
     * Get all notifications for a user
     * @param UserNotificationInterface $user
     * @return AbstractNotification[] list of notifications
     */
    public function getUserNotifications(UserNotificationInterface $user)
    {
        return $this->om->getRepository($this->class)->findAllByUser($user);
    }


}