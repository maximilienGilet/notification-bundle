<?php

namespace Mgilet\NotificationBundle\Manager;

use Doctrine\ORM\EntityManager;
use Mgilet\NotificationBundle\Model\AbstractNotification;
use Mgilet\NotificationBundle\Model\UserNotificationInterface;

class NotificationManager
{

    private $om;
    private $user;
    private $notification;

    /**
     * NotificationManager constructor.
     * @param EntityManager $om
     * @param $user
     * @param $notification
     * @internal param $class
     */
    public function __construct(EntityManager $om, $user, $notification)
    {
        $this->om = $om;
        $this->user = $user;
        $this->notification = $notification;
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
        $notification = new $this->notification;
        $notification
            ->setSubject($subject)
            ->setMessage($message)
            ->setLink($link)
            ->isSeen(false);

        return $notification;
    }

    /**
     * Add a notification to a user
     * @param UserNotificationInterface $user
     * @param AbstractNotification $notification
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
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
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
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
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
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
    public function getUserNotifications($user)
    {
        return $this->om->getRepository($this->notification)->findBy(array('user' => $user));
    }

    /**
     * Get notification count for a user
     * @param UserNotificationInterface $user
     * @return int
     */
    public function getNotificationCount($user)
    {
        return count($this->om->getRepository($this->notification)->findBy(array('user' => $user)));
    }

    /**
     * Get unread notification count for a user
     * @param UserNotificationInterface $user
     * @return int
     */
    public function getUnreadNotificationCount($user)
    {
        return count($this->om->getRepository($this->notification)->findBy(array(
            'user' => $user,
            'seen' => false
        )));
    }


}