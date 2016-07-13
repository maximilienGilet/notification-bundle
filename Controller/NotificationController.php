<?php

namespace Mgilet\NotificationBundle\Controller;

use Mgilet\NotificationBundle\Model\AbstractNotification;
use Mgilet\NotificationBundle\Model\UserNotificationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class NotificationController
 * the base controller for notifications
 */
class NotificationController extends Controller
{

    /**
     * List of all notifications
     *
     * @Route("/", name="notifications_list")
     * @Method("GET")
     * @throws \LogicException
     */
    public function listAction()
    {
        return $this->render('MgiletNotificationBundle::notifications.html.twig', array(
            'notifications' => $this->get('mgilet.notification')->getUserNotifications($this->getUser())
        ));
    }

    /**
     * Set a Notification as seen
     *
     * @Route("/{notification}/mark_as_seen", name="notification_mark_as_seen")
     * @Method("POST")
     * @param AbstractNotification $notification
     * @return JsonResponse
     * @throws \LogicException
     */
    public function markAsSeenAction($notification)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $notification = $this->get('mgilet.notification')->getNotificationById($notification);
        $notification->setSeen(true);
        $em->persist($notification);
        $em->flush();

        return new JsonResponse(true);
    }

    /**
     * Set a Notification as unseen
     *
     * @Route("/{notification}/mark_as_unseen", name="notification_mark_as_unseen")
     * @Method("POST")
     * @param AbstractNotification $notification
     * @return JsonResponse
     * @throws \LogicException
     */
    public function markAsUnSeenAction($notification)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $notification = $this->get('mgilet.notification')->getNotificationById($notification);
        $notification->setSeen(false);
        $em->persist($notification);
        $em->flush();

        return new JsonResponse(true);
    }

    /**
     * Set all Notifications for a User as seen
     *
     * @Route("/markAllAsSeen", name="notification_mark_all_as_seen")
     * @Method("POST")
     * @throws \LogicException
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    public function markAllAsSeenAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserNotificationInterface) {
            throw new AuthenticationException('This user does not have access to this section.');
        }
        $notifications = $this->get('mgilet.notification')->getUnseenUserNotifications($user);
        foreach ($notifications as $notification) {
            $notification->setSeen(true);
            $em->persist($notification);
        }
        $em->flush();

        return new JsonResponse(true);
    }

}
