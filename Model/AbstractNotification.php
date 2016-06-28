<?php

namespace Mgilet\NotificationBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class AbstractNotification
 * Notifications defined in your app must implement this class
 * @package maximilienGilet\NotificationBundle\Model
 * @ORM\MappedSuperclass()
 */
abstract class AbstractNotification
{

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    protected $date;

    /**
     * @var string
     * @ORM\Column(name="subject", type="string", nullable=false)
     */
    protected $subject;
    /**
     * @var string
     * @ORM\Column(name="message", type="string", nullable=true)
     */
    protected $message;

    /**
     * @var string
     * @ORM\Column(name="link", type="string", nullable=true)
     */
    protected $link;

    /**
     * @var boolean
     * @ORM\Column(name="seen", type="boolean")
     */
    protected $seen;

    /**
     * AbstractNotification constructor.
     */
    public function __construct()
    {
        $this->seen = false;
        $this->date = new \DateTime();
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
     * @return boolean Seen status of the notification
     */
    public function isSeen()
    {
        return $this->seen;
    }

    /**
     * @param boolean $isSeen Seen status of the notification
     * @return $this
     */
    public function setSeen($isSeen)
    {
        $this->seen = $isSeen;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getSubject() . ' - ' . $this->getMessage();
    }
}
