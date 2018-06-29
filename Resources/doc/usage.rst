========================
MgiletNotificationBundle
========================
------------------------------------------------
A simple Symfony 3 bundle for user notifications
------------------------------------------------

Usage
=====

This bundle provides manuy methods and helpers in order to be as simple as possible to use.

mgilet.notification service
---------------------------

This is the notification manager. It's designed to do manage notification in a efficient way.

Currently, it allows you to:

* create notifications
* add notifications to entities
* mark notifications as "seen"/"unseen"
* get notifications for an entity
* get notification counts
* ... and much more !

Basic usage
~~~~~~~~~~~

Lets write a minimalistic route : ``send-notification``.

This sample route will send a notification to the user going there.

Your ``User`` entity will need to be ``notifiable`` (see `installation`_)

Sample route:

.. code-block:: php

    <?php

    // AppBundle/Controller/DefaultController.php

    ...

    class DefaultController extends Controller
    {

        ...

        /**
         * @Route("/send-notification", name="send_notification")
         * @param Request $request
         * @return \Symfony\Component\HttpFoundation\RedirectResponse
         */
        public function sendNotification(Request $request)
        {
            $manager = $this->get('mgilet.notification');
            $notif = $manager->createNotification('Hello world !');
            $notif->setMessage('This a notification.');
            $notif->setLink('http://symfony.com/');
            // or the one-line method :
            // $manager->createNotification('Notification subject','Some random text','http://google.fr');

            // you can add a notification to a list of entities
            // the third parameter ``$flush`` allows you to directly flush the entities
            $manager->addNotification(array($this->getUser()), $notif, true);

            return $this->redirectToRoute('homepage');
        }
    }

This will create a notification and associate it with the current user.

Events
~~~~~~

By using the ``NotificationManager`` you can listen to events thrown by the manager.

List of events:

* ``'mgilet.notification.created'``
* ``'mgilet.notification.assigned'`` -> when a notification is added to a notifiable entity
* ``'mgilet.notification.seen'``
* ``'mgilet.notification.unseen'``
* ``'mgilet.notification.modified'``
* ``'mgilet.notification.removed'``
* ``'mgilet.notification.delete'``


Twig functions
--------------

This bundle also provides some useful twig functions helping you to design a great user experience.

If you want to make your own twig template, see : `Symfony documentation`_

List of functions :
~~~~~~~~~~~~~~~~~~~

**Counting notifications**

* ``mgilet_notification_count``
* ``mgilet_notification_unseen_count``

These functions will display the current notification count for a given notifiable

::

    {{ mgilet_notification_count() }} {# all notifications #}

    {{ mgilet_notification_unseen_count() }} {# unseen notifications #}

------------------

**Rendering notifications**

* ``mgilet_notification_render``

This function will render notifications for a user (current by default). It takes some arguments to help you personalize notification display to your liking.

Currently, 2 options are available :

* ``seen``
    * true : will display all notification (default behavior)
    * false : will display only unseen notifications

* ``template``
    * use the the twig file you provide instead of the default one. NOTE : the notification list is called ``notificationList``


::

    {{ mgilet_notification_render(notifiableEntity) }}

    // only unseen notifications
    {{ mgilet_notification_render(notifiableEntity ,{'seen': false }) }}

    // custom template
    {{ mgilet_notification_render({ 'template': 'Path/to/my/template.html.twig'}) }}


Notification controller:
------------------------

This bundle has a also a controller performing basic notification management for you.

The controller is located in

``vendor/mgilet/notification-bundle/Controller/NotificationController``.


Go further :
------------

Go to `go further`_

----------------------------------------------

* `installation`_

* `basic usage`_

* `go further`_


.. _installation: index.rst
.. _basic usage: usage.rst
.. _go further: further.rst

.. _Symfony documentation: http://symfony.com/doc/current/bundles/override.html
