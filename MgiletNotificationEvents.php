<?php

namespace Mgilet\NotificationBundle;

final class MgiletNotificationEvents
{
    /**
     * The onCreatedNotification event occurs when an AbstractNotification is created.
     *
     * This event allows you to access the notification.
     *
     * @Event("Mgilet\NotificationBundle\Event\NotificationEvent")
     */
    const onCreatedNotification = 'mgilet.notification.created_notification';

    /**
     * The onNewNotification event occurs when an AbstractNotification is created assigned to an UserNotificationInterface.
     *
     * This event allows you to access the notification.
     *
     * @Event("Mgilet\NotificationBundle\Event\NotificationEvent")
     */
    const onNewNotification = 'mgilet.notification.new_notification';

    /**
     * The onSeenNotification event occurs when an AbstractNotification is marked as seen.
     *
     * This event allows you to access the notification.
     *
     * @Event("Mgilet\NotificationBundle\Event\NotificationEvent")
     */
    const onSeenNotification = 'mgilet.notification.seen_notification';

    /**
     * The onRemovedNotification event occurs when an AbstractNotification is removed.
     *
     * @Event("Mgilet\NotificationBundle\Event\NotificationEvent")
     */
    const onRemovedNotification = 'mgilet.notification.removed_notification';
}
