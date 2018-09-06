<?php

namespace Mgilet\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mgilet\NotificationBundle\Model\Notification as NotificationModel;

/**
 * Class Notification
 *
 * @ORM\Entity
 * @package Mgilet\NotificationBundle\Entity
 */
class Notification extends NotificationModel implements NotificationInterface
{

}