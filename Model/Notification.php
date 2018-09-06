<?php

namespace Mgilet\NotificationBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Mgilet\NotificationBundle\Entity\NotifiableNotification;

/**
 * Class Notification
 * Notifications defined in your app must implement this class
 *
 * @ORM\MappedSuperclass(repositoryClass="Mgilet\NotificationBundle\Entity\Repository\NotificationRepository")
 * @package Mgilet\NotificationBundle\Model
 */
abstract class Notification implements \JsonSerializable
{

    /**
     * @var integer $id
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @var string
     * @ORM\Column(type="string", length=4000)
     */
    protected $subject;
    /**
     * @var string
     * @ORM\Column(type="string", length=4000, nullable=true)
     */
    protected $message;

    /**
     * @var string
     * @ORM\Column(type="string", length=4000, nullable=true)
     */
    protected $link;

    /**
     * @var NotifiableNotification[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="Mgilet\NotificationBundle\Entity\NotifiableNotification", mappedBy="notification", cascade={"persist"})
     */
    protected $notifiableNotifications;



    /**
     * AbstractNotification constructor.
     */
    public function __construct()
    {
        $this->date = new \DateTime();
        $this->notifiableNotifications = new ArrayCollection();
    }

    /**
     * @return int Notification Id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return string Notification subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject Notification subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string Notification message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message Notification message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string Link to redirect the user
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link Link to redirect the user
     * @return $this
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return ArrayCollection|NotifiableNotification[]
     */
    public function getNotifiableNotifications()
    {
        return $this->notifiableNotifications;
    }

    /**
     * @param NotifiableNotification $notifiableNotification
     *
     * @return $this
     */
    public function addNotifiableNotification(NotifiableNotification $notifiableNotification)
    {
        if (!$this->notifiableNotifications->contains($notifiableNotification)) {
            $this->notifiableNotifications[] = $notifiableNotification;
            $notifiableNotification->setNotification($this);
        }

        return $this;
    }

    /**
     * @param NotifiableNotification $notifiableNotification
     *
     * @return $this
     */
    public function removeNotifiableNotification(NotifiableNotification $notifiableNotification)
    {
        if ($this->notifiableNotifications->contains($notifiableNotification)) {
            $this->notifiableNotifications->removeElement($notifiableNotification);
            $notifiableNotification->setNotification(null);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getSubject() . ' - ' . $this->getMessage();
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'id'      => $this->getId(),
            'date'    => $this->getDate()->format(\DateTime::ISO8601),
            'subject' => $this->getSubject(),
            'message' => $this->getMessage(),
            'link'    => $this->getLink()
        ];
    }
}
