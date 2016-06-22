========================
MgiletNotificationBundle
========================
------------------------------------------------
A simple Symfony 3 bundle for user notifications
------------------------------------------------

Advanced configuration
======================

By default this bundle is configured to use ``AppBundle\Entity\User`` as user entity and ``AppBundle\Entity\Notification`` as notification entity.

You can change this behavior by editing your ``config.yml`` file.

Default bundle configuration:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Default configuration:

.. code-block:: yaml

    # config.yml

    mgilet_notification:
        user_class: AppBundle\Entity\User
        notification_class: AppBundle\Entity\Notification


How to configure:
~~~~~~~~~~~~~~~~~

* ``user_class``: the class defining a user (this class MUST implement ``UserNotificationInterface`` )
* ``notification_class``: the class defining a notification (this class MUST extends ``AbstractNotification`` )

Change the default configuration according to your existing classes.

Go further :
------------

`go further`_

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
