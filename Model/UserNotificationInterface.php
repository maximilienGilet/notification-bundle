<?php

namespace Mgilet\NotificationBundle\Model;


interface UserNotificationInterface
{

    /**
     * The user identifier
     * Must return an unique identifier
     * @return int
     */
    public function getIdentifier();

    /**
     * @return AbstractNotification
     */
    public function getNotifications();

    /**
     * @param $notification
     * @return $this
     */
    public function addNotification($notification);

    /**
     * @param $notification
     * @return $this
     */
    public function removeNotification($notification);

}