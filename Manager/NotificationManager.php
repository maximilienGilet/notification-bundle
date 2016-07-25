<?php

namespace Mgilet\NotificationBundle\Manager;

use Doctrine\ORM\EntityManager;
use Mgilet\NotificationBundle\Entity\AbstractNotification;
use Mgilet\NotificationBundle\Entity\UserNotificationInterface;
use Mgilet\NotificationBundle\Event\NotificationEvent;
use Mgilet\NotificationBundle\MgiletNotificationEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;

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
    private $dispatcher;

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
        $this->dispatcher = new EventDispatcher();
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
        $this->om->persist($notification);

        $event = new NotificationEvent($notification);
        $this->dispatcher->dispatch(MgiletNotificationEvents::onCreatedNotification, $event);

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
        $this->om->flush($notification);

        $event = new NotificationEvent($notification);
        $this->dispatcher->dispatch(MgiletNotificationEvents::onNewNotification, $event);

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
        $this->om->flush();

        $event = new NotificationEvent($notification);
        $this->dispatcher->dispatch(MgiletNotificationEvents::onRemovedNotification, $event);
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
        $this->om->flush($notification);

        $event = new NotificationEvent($notification);
        $this->dispatcher->dispatch(MgiletNotificationEvents::onSeenNotification, $event);

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

    /**
     * @param AbstractNotification $notification
     * @param \DateTime $date
     * @return AbstractNotification
     */
    public function setNotificationDate(AbstractNotification $notification, \DateTime $date)
    {
        $notification->setDate($date);
        $this->om->flush($notification);

        return $notification;
    }

    /**
     * @param AbstractNotification $notification
     * @param string $subject
     * @return AbstractNotification
     */
    public function setNotificationSubject(AbstractNotification $notification, $subject)
    {
        $notification->setSubject($subject);
        $this->om->flush($notification);

        return $notification;
    }

    /**
     * @param AbstractNotification $notification
     * @param string $message
     * @return AbstractNotification
     */
    public function setNotificationMessage(AbstractNotification $notification, $message)
    {
        $notification->setMessage($message);
        $this->om->flush($notification);

        return $notification;
    }

    /**
     * @param AbstractNotification $notification
     * @param string $link
     * @return AbstractNotification
     */
    public function setNotificationLink(AbstractNotification $notification, $link)
    {
        $notification->setLink($link);
        $this->om->flush($notification);

        return $notification;
    }

    /**
     * @param AbstractNotification $notification
     * @param boolean $seen
     * @return AbstractNotification
     */
    public function setNotificationSeen(AbstractNotification $notification, $seen)
    {
        $notification->setLink($seen);
        $this->om->flush($notification);

        return $notification;
    }

}
