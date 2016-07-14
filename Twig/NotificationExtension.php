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
    protected $twig;

    /**
     * NotificationExtension constructor.
     * @param NotificationManager $notificationManager
     * @param TokenStorage $storage
     * @param \Twig_Environment $twig
     */
    public function __construct(NotificationManager $notificationManager, TokenStorage $storage, \Twig_Environment $twig)
    {
        $this->notificationManager = $notificationManager;
        $this->storage = $storage;
        $this->twig = $twig;
    }

    /**
     * @return array available Twig functions
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('mgilet_notification_render', array($this, 'render'), array(
                'is_safe' => array('html')
            )),
            new \Twig_SimpleFunction('mgilet_notification_count', array($this, 'countNotifications'), array(
                'is_safe' => array('html')
            )),
            new \Twig_SimpleFunction('mgilet_unseen_notification_count', array($this, 'countUnseenNotifications'), array(
                'is_safe' => array('html')
            ))
        );
    }

    /**
     * Rendering notifications in Twig
     * @param array $options
     * @param null $user
     * @return null|string
     */
    public function render($options = array(), $user = null)
    {
        if( !array_key_exists('seen',$options)) {
            $options['seen'] = true;
        }
        if ($options['display'] === 'list') {
            return $this->renderNotifications($user, $options['seen']);
        }
        if ($options['display'] === 'dropdown') {
            return $this->renderDropdownNotifications($user, $options['seen']);
        }
        return null;
    }

    /**
     * Render notifications for a user
     * @param null $user
     * @param bool $seen
     * @return string
     * @internal param \Twig_Environment $twig
     */
    public function renderNotifications($user = null, $seen = true)
    {
        $user = $this->getUser($user);
        if ($seen) {
            $notifications = $this->notificationManager->getUserNotifications($user);
        } else {
            $notifications = $this->notificationManager->getUnseenUserNotifications($user);
        }

        return $this->twig->render('MgiletNotificationBundle::notification_list.html.twig',
            array(
                'notifications' => $notifications
            )
        );
    }

    /**
     * Render notifications for a user in a dropdown (Bootstrap 3 highly recommended)
     * @param null $user
     * @param bool $seen
     * @return mixed
     */
    public function renderDropdownNotifications($user = null, $seen = true)
    {
        $user = $this->getUser($user);
        if ($seen) {
            $notifications = $this->notificationManager->getUserNotifications($user);
        } else {
            $notifications = $this->notificationManager->getUnseenUserNotifications($user);
        }

        return $this->twig->render('MgiletNotificationBundle::notification_dropdown.html.twig', array(
            'notifications' => $notifications
        ));
    }

    /**
     * Display the total count of notifications for this user
     * @param null $user
     * @return int
     */
    public function countNotifications($user = null)
    {
        $user = $this->getUser($user);
        return $this->notificationManager->getNotificationCount($user);
    }

    /**
     * Display the count of unseen notifications for this user
     * @param null $user
     * @return int
     */
    public function countUnseenNotifications($user = null)
    {
        $user = $this->getUser($user);
        return $this->notificationManager->getUnseenNotificationCount($user);
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
