========================
MgiletNotificationBundle
========================
------------------------------------------------
A simple Symfony 3 bundle for user notifications
------------------------------------------------

Advanced configuration
======================

By default this bundle is configured to use Implementations of ``UserNotificationInterface`` as user and ``AppBundle\Entity\Notification`` as notification entity.

You can change this behavior by editing your ``config.yml`` file.

Default bundle configuration:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Default configuration:

.. code-block:: yaml

    # config.yml

    mgilet_notification:
        notification_class: AppBundle\Entity\Notification


How to configure:
~~~~~~~~~~~~~~~~~

* ``notification_class``: the entity defining a notification (this class MUST extends the MappedSuperClass ``AbstractNotification`` )

Change the default configuration according to your existing class.

Example :

.. code-block:: yaml

    # config.yml

    mgilet_notification:
        notification_class: AcmeBundle\Entity\MyNotification


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
