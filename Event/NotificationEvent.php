<?php

namespace Mgilet\NotificationBundle\Event;

use Mgilet\NotificationBundle\Entity\Notification;
use Mgilet\NotificationBundle\NotifiableInterface;
use Symfony\Component\EventDispatcher\Event;

class NotificationEvent extends Event
{
    private $notification;
    private $notifiable;

    /**
     * NotificationEvent constructor.
     *
     * @param Notification             $notification
     * @param NotifiableInterface|null $notifiable
     */
    public function __construct(Notification $notification, NotifiableInterface $notifiable = null)
    {
        $this->notification = $notification;
        $this->notifiable = $notifiable;
    }

    /**
     * @return Notification
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @return NotifiableInterface
     */
    public function getNotifiable()
    {
        return $this->notifiable;
    }
}
