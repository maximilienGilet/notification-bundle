<?php

namespace Mgilet\NotificationBundle\Manager;

use Doctrine\ORM\EntityManager;
use Mgilet\NotificationBundle\Model\AbstractNotification;
use Mgilet\NotificationBundle\Model\UserNotificationInterface;

/**
 * Class NotificationManager
 * Manager for notifications
 * @package Mgilet\NotificationBundle\Manager
 */
class NotificationManager
{

    private $om;
    private $notification;
    private $repository;

    /**
     * NotificationManager constructor.
     * @param EntityManager $om
     * @param $notification
     * @internal param $class
     */
    public function __construct(EntityManager $om, $notification)
    {
        $this->om = $om;
        $this->notification = $notification;
        $this->repository = $om->getRepository($notification);
    }

    /**
     * Get a notification by it's id
     * @param $id
     * @return AbstractNotification
     */
    public function getNotificationById($id)
    {
        return $this->repository->findOneBy(array('id' => $id));
    }

    /**
     * Generate a notification
     * @param $subject
     * @param null $message
     * @param null $link
     * @return AbstractNotification
     */
    public function generateNotification($subject, $message = null, $link = null)
    {
        /** @var AbstractNotification $notification */
        $notification = new $this->notification;
        $notification
            ->setSubject($subject)
            ->setMessage($message)
            ->setLink($link);

        return $notification;
    }

    /**
     * Add a notification to a user
     * @param UserNotificationInterface $user
     * @param AbstractNotification $notification
     * @return AbstractNotification
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addNotification(UserNotificationInterface $user, AbstractNotification $notification)
    {
        $user->addNotification($notification);
        $this->om->persist($user);
        $this->om->flush();

        return $notification;
    }

    /**
     * @param UserNotificationInterface $user
     * @param string $subject
     * @param string|null $message
     * @param string|null $link
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createNotification(UserNotificationInterface $user, $subject, $message = null, $link = null)
    {
        $this->addNotification($user, $this->generateNotification($subject,$message,$link));
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
     * @return AbstractNotification
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function markAsSeen(AbstractNotification $notification)
    {
        $notification->setSeen(true);
        $this->om->persist($notification);
        $this->om->flush();

        return $notification;
    }

    /**
     * Mark all notifications as seen
     * @param AbstractNotification[] $notifications
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function markAllAsSeen($notifications)
    {
        foreach ($notifications as $notification) {
            $this->markAsSeen($notification);
        }
    }

    /**
     * Get all notifications for a user
     * @param UserNotificationInterface $user
     * @return AbstractNotification[] list of notifications
     */
    public function getUserNotifications($user)
    {
        return $this->repository->findBy(array('user' => $user),array('date' => 'DESC'));
    }

    /**
     * Get all unseen notifications for a user
     * @param UserNotificationInterface $user
     * @return AbstractNotification[]
     */
    public function getUnseenUserNotifications($user)
    {
        return $this->repository->findBy(
            array(
                'user' => $user,
                'seen' => false
            ),
            array('date' => 'DESC')
        );
    }

    /**
     * Get notification count for a user
     * @param UserNotificationInterface $user
     * @return int
     */
    public function getNotificationCount($user)
    {
        return count($this->repository->findBy(array('user' => $user),array('date' => 'DESC')));
    }

    /**
     * Get unseen notification count for a user
     * @param UserNotificationInterface $user
     * @return int
     */
    public function getUnseenNotificationCount($user)
    {
        return count($this->repository->findBy(
            array(
                'user' => $user,
                'seen' => false
            ),
            array('date' => 'DESC')
        ));
    }

}
