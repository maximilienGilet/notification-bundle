<?php

namespace Mgilet\NotificationBundle\Model;

/**
 * Interface UserNotificationInterface
 * Users in your app must implement this in order to use the notification system
 * @package Mgilet\NotificationBundle\Model
 */
interface UserNotificationInterface
{

    /**
     * The user identifier
     * Must return an unique identifier
     * @return int
     */
    public function getIdentifier();

    /**
     * Returns all notifications attached to the user
     * @return AbstractNotification
     */
    public function getNotifications();

    /**
     * Adds a notification to the user
     * @param $notification
     * @return $this
     */
    public function addNotification($notification);

    /**
     * Remove a notification to the user
     * @param $notification
     * @return $this
     */
    public function removeNotification($notification);

}
