<?php

namespace Mgilet\NotificationBundle\Twig;

use Mgilet\NotificationBundle\Manager\NotificationManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Twig extension to display notifications
 **/
class NotificationExtension extends \Twig_Extension
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


    public function getFilters()
    {
        new \Twig_SimpleFunction('notification', array($this, 'renderNotification'), array(
            'is_safe' => array('html'),
            'needs_environment' => true
        ));
    }

    /**
     * Render notifications for a user
     * @param \Twig_Environment $twig
     * @param null $user
     * @return string
     */
    public function renderNotification(\Twig_Environment $twig, $user = null)
    {
        if (!$user) {
            $user = $this->storage->getToken()->getUser();
        }
        $notifications = $this->notificationManager->getUserNotifications($user);

        return $twig->render('NotificationBundle::notifications.html.twig',
            array(
                'notifications' => $notifications
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'notification_extension';
    }
}