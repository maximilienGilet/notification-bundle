<?php

namespace Mgilet\NotificationBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Mgilet\NotificationBundle\Manager\NotificationManager;

class UrlListener {

    private $svNotificationManager;

    public function __construct(NotificationManager $svNotificationManager) {
        $this->svNotificationManager = $svNotificationManager;
    }

    public function onKernelRequest(GetResponseEvent $event) {
        $request = $event->getRequest();

        $iNotificationId = $request->query->get('notid');
        if ($iNotificationId) {
            $oNotification = $this->svNotificationManager->getNotificationById($iNotificationId);
            if ($oNotification) {
                $this->svNotificationManager->markAsSeen($oNotification);
            }
        }
    }

}
