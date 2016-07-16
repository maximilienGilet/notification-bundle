========================
MgiletNotificationBundle
========================
------------------------------------------------
A simple Symfony 3 bundle for user notifications
------------------------------------------------

Usage
=====

This bundle provides some methods and helpers in order to be as simple as possible to use.

mgilet.notification service
---------------------------

This is the notification manager. It's designed to do manage notification in a efficient way.

Currently, it allows you to:

* create notifications
* add notifications to users
* mark notifications as "seen"
* get notifications for a user
* get notification count
* ... and much more !

Basic usage
~~~~~~~~~~~

Lets write a minimalistic route : ``send-notification``.

This sample route will send a notification to the user going there.

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
            $notif = $manager->generateNotification('Hello world !');
            $notif->setMessage('This a notification.');
            $notif->setLink('http://symfony.com/');
            $manager->addNotification($this->getUser(), $notif);

            // or the one-line method :
            // $manager->createNotification($this->getUser(), 'Notification subject','Some random text','http://google.fr');

            return $this->redirectToRoute('homepage');
        }
    }

This will create a notification and associate it with the current user.


Twig functions
--------------

This bundle also provides some useful twig functions helping you to design a great user experience.

Notice : Bootstrap 3 is highly recommended for best results.

If you want to make your own twig template, see : `overriding parts of the bundle`_

List of functions :
~~~~~~~~~~~~~~~~~~~

* mgilet_notification_count
* mgilet_unseen_notification_count

These functions will display the current notification count for the current user.

------------------

* mgilet_notification_render

This function will render notifications for a user (current by default). It takes some arguments to help you personalize notification display to your liking.

Currently, 2 options are available :

* display
     * list : will display a simple list of all notifications
     * dropdown : a responsive Bootstrap dropdown with full notification handling

note : one argument is required

* seen
    * true : will display all notification (default behavior)
    * false : will display only unseen notifications

As optional second argument, you can pass a user. By default current user is selected

Usage:
~~~~~~

**Notification count :**
::

    {{ mgilet_notification_count() }} {# all notifications #}

    {{ mgilet_unseen_notification_count }} {# unseen notifications #}

**Rendering:**

Dropdown with all notifications::

    {{ mgilet_notification_render({ 'display': 'dropdown', 'seen': true }) }}

Or::

    {{ mgilet_notification_render({ 'display': 'dropdown' }) }}


Only unseen notifications in dropdown::

    {{ mgilet_notification_render({ 'display': 'dropdown', 'seen': false }) }}

List with all notifications::

    {{ mgilet_notification_render({ 'display': 'list', 'seen': true }) }}


Or::

    {{ mgilet_notification_render({ 'display': 'list' }) }} {# does the same thing #}


List with only unseen notifications::

    {{ mgilet_notification_render({ 'display': 'list', 'seen': false }) }}


Notification controller:
------------------------

This bundle has a also a controller performing basic notification management for you.

The controller is located in

``vendor/mgilet/notification-bundle/Controller/NotificationController``.

Built in routes :
~~~~~~~~~~~~~~~~~

* ``/notifications`` : return the ``list`` template with all notifications
* ``/notifications/{notification}/markAsSeen`` : mark the given notification as seen
* ``/notifications/{notification}/markAsUnseen``: mark the given notification as unseen
* ``/notifications/markAllAsSeen`` : mark all notifications as seen for the user

Overriding parts of the bundle :
--------------------------------

Go to `overriding parts of the bundle`_

----------------------------------------------

* `installation`_

* `basic usage`_

* `overriding parts of the bundle`_

* `advanced configuration`_

* `go further`_


.. _installation: index.rst
.. _basic usage: usage.rst
.. _overriding parts of the bundle: overriding.rst
.. _advanced configuration: advanced-configuration.rst
.. _go further: further.rst
