<?php

namespace Mgilet\NotificationBundle\Twig;

use Mgilet\NotificationBundle\Manager\NotificationManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Twig_Extension;

/**
 * Twig extension to display notifications
 **/
class NotificationExtension extends Twig_Extension
{
    protected $notificationManager;
    protected $storage;

    /**
     * NotificationExtension constructor.
     * @param NotificationManager $notificationManager
     * @param TokenStorage $storage
     */
    public function __construct(NotificationManager $notificationManager, TokenStorage $storage)
        // service security.token_storage
    {
        $this->notificationManager = $notificationManager;
        $this->storage = $storage;
    }


    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('mgilet_notification_render', array($this, 'renderNotifications'), array(
                'is_safe' => array('html'),
                'needs_environment' => true
            )),
            new \Twig_SimpleFunction('mgilet_notification_count',array($this, 'countNotifications'), array(
                'is_safe' => array('html')
            ))
        );
    }

    /**
     * Render notifications for a user
     * @param \Twig_Environment $twig
     * @param null $user
     * @return string
     */
    public function renderNotifications(\Twig_Environment $twig, $user = null)
    {
        $user = $this->getUser($user);
        $notifications = $this->notificationManager->getUserNotifications($user);

        return $twig->render('MgiletNotificationBundle::notifications.html.twig',
            array(
                'notifications' => $notifications
            )
        );
    }

    public function countNotifications($user = null)
    {
        $user = $this->getUser($user);
        return $this->notificationManager->getNotificationCount($user);
    }

    /**
     * If no user is specified return current user
     * @param null $user
     * @return mixed user
     */
    private function getUser($user = null)
    {
        if (!$user) {
            return $this->storage->getToken()->getUser();
        }

        return $user;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mgilet_notification';
    }
}