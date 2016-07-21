<?php

namespace Mgilet\NotificationBundle\Entity;

/**
 * Class AbstractNotification
 * Notifications defined in your app must implement this class
 * @package maximilienGilet\NotificationBundle\Model
 */
abstract class AbstractNotification
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @var string
     */
    protected $subject;
    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $link;

    /**
     * @var boolean
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
