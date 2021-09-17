<?php

namespace Mgilet\NotificationBundle\Event;

use Mgilet\NotificationBundle\Entity\NotificationInterface;
use Mgilet\NotificationBundle\NotifiableInterface;
use Symfony\Contracts\EventDispatcher\Event;

class NotificationEvent extends Event
{
    private $notification;
    private $notifiable;

    /**
     * NotificationEvent constructor.
     *
     * @param NotificationInterface    $notification
     * @param NotifiableInterface|null $notifiable
     */
    public function __construct(NotificationInterface $notification, NotifiableInterface $notifiable = null)
    {
        $this->notification = $notification;
        $this->notifiable = $notifiable;
    }

    /**
     * @return NotificationInterface
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
