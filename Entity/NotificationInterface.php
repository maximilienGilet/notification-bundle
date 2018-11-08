<?php

namespace Mgilet\NotificationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Interface NotificationInterface
 *
 * @package Mgilet\NotificationBundle\Entity
 */
interface NotificationInterface
{

    /**
     * Defines the default entity used for the notification
     *
     * @var string
     */
    const DEFAULT_NOTIFICATION_ENTITY_CLASS = 'Mgilet\NotificationBundle\Entity\Notification';

    /**
     * @return int Notification Id
     */
    public function getId();

    /**
     * @return \DateTime
     */
    public function getDate();

    /**
     * @param \DateTime $date
     *
     * @return NotificationInterface
     */
    public function setDate($date);

    /**
     * @return string Notification subject
     */
    public function getSubject();

    /**
     * @param string $subject Notification subject
     *
     * @return NotificationInterface
     */
    public function setSubject($subject);

    /**
     * @return string Notification message
     */
    public function getMessage();

    /**
     * @param string $message Notification message
     *
     * @return NotificationInterface
     */
    public function setMessage($message);

    /**
     * @return string Link to redirect the user
     */
    public function getLink();

    /**
     * @param string $link Link to redirect the user
     *
     * @return NotificationInterface
     */
    public function setLink($link);

    /**
     * @return ArrayCollection|NotifiableNotification[]
     */
    public function getNotifiableNotifications();

    /**
     * @param NotifiableNotification $notifiableNotification
     *
     * @return NotificationInterface
     */
    public function addNotifiableNotification(NotifiableNotification $notifiableNotification);

    /**
     * @param NotifiableNotification $notifiableNotification
     *
     * @return NotificationInterface
     */
    public function removeNotifiableNotification(NotifiableNotification $notifiableNotification);
}