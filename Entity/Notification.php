<?php

namespace Mgilet\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mgilet\NotificationBundle\Model\Notification as NotificationModel;

/**
 * Class AbstractNotification
 * @ORM\Entity(repositoryClass="Mgilet\NotificationBundle\Entity\Repository\NotificationRepository")
 */
class Notification extends NotificationModel
{

}
