<?php

namespace Mgilet\NotificationBundle;

final class MgiletNotificationEvents
{
    /**
     * Occurs when a Notification is created.
     *
     * @Event("Mgilet\NotificationBundle\Event\NotificationEvent")
     */
    const CREATED = 'mgilet.notification.created';

    /**
     * Occurs when a Notification is assigned to a NotifiableEntity.
     *
     * @Event("Mgilet\NotificationBundle\Event\NotificationEvent")
     */
    const ASSIGNED = 'mgilet.notification.assigned';

    /**
     * Occurs when a Notification is marked as seen.
     *
     * @Event("Mgilet\NotificationBundle\Event\NotificationEvent")
     */
    const SEEN = 'mgilet.notification.seen';

    /**
     * Occurs when a Notification is marked as unseen.
     *
     * @Event("Mgilet\NotificationBundle\Event\NotificationEvent")
     */
    const UNSEEN = 'mgilet.notification.unseen';

    /**
     * Occurs when a Notification is modified.
     *
     * @Event("Mgilet\NotificationBundle\Event\NotificationEvent")
     */
    const MODIFIED = 'mgilet.notification.modified';

    /**
     * Occurs when a Notification is removed.
     *
     * @Event("Mgilet\NotificationBundle\Event\NotificationEvent")
     */
    const REMOVED = 'mgilet.notification.removed';

    /**
     * Occurs when a Notification is deleted
     *
     * @Event("Mgilet\NotificationBundle\Event\NotificationEvent")
     */
    const DELETED = 'mgilet.notification.delete';
}
