<?php

namespace Mgilet\NotificationBundle\Event;

use Mgilet\NotificationBundle\Entity\AbstractNotification;
use Symfony\Component\EventDispatcher\Event;

class NotificationEvent extends Event
{

    private $notification;

    /**
     * NotificationEvent constructor.
     * @param AbstractNotification $notification
     */
    public function __construct(AbstractNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @return AbstractNotification
     */
    public function getNotification()
    {
        return $this->notification;
    }

}
