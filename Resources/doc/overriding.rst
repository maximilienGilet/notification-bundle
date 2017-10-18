========================
MgiletNotificationBundle
========================
------------------------------------------------
A simple Symfony 3 bundle for user notifications
------------------------------------------------

Overriding parts of the bundle
==============================

While providing configured templates, you can customize them to adjust it to your needs.

The following will show you how.

For more information about overriding parts of bundles, see the `Symfony documentation`_.


Twig templates:
---------------

In order to override templates provided by this bundle, you need to setup folders like this::

    app/Resources
    ├─ MgiletNotificationBundle/
    │   ├─ views/
    │   │  ├─ notification_dropdown.html.twig
    │   │  └─ notification_list.html.twig
    │   │  └─ notifications.html.twig


* ``notification_dropdown.html.twig`` is the template called when you use the dropdown option of ``mgilet_notification_render`` Twig method.

* ``notification_list.html.twig`` is the template called when you use the list option of ``mgilet_notification_render`` Twig method.

* ``notifications.html.twig`` is the template called by the ``NotificationController`` when responding to the ``/notifications`` route call.

Rest of the bundle:
-------------------

According to the `Symfony documentation`_ you can override any other part of this bundle.

Advanced configuration :
------------------------

Go to `advanced configuration`_.

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

.. _Symfony documentation: http://symfony.com/doc/current/cookbook/bundles/override.html