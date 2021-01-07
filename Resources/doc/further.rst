========================
MgiletNotificationBundle
========================
----------------------------------------------------
A simple Symfony 2/3/4 bundle for user notifications
----------------------------------------------------


Override the Notification entity
================================

To override the `notification` entity (eg to add fields), you just have to create a new entity like this :


.. code-block:: php

        <?php

        namespace App\Entity;

        use Doctrine\ORM\Mapping as ORM;
        use Mgilet\NotificationBundle\Entity\NotificationInterface;
        use Mgilet\NotificationBundle\Model\Notification as NotificationModel;

        /**
         * Class Notification
         * @ORM\Entity
         *
         * @package Acme\Entity
         */
        class Notification extends NotificationModel implements NotificationInterface
        {
        }


Next, the only other step is to configure the bundle to use your new entity :

**config/packages/mgilet_notification.yaml**

.. code-block:: yaml

        mgilet_notification:
                notification_class: App\Entity\MyCustomNotification



Go further
==========

If you like my bundle and/or want to :

* Suggest something
* Report a problem
* Improve something
* Add translation(s)

Just go to the project's `Github`_. :)

Most important thing : if you like it share it !

Thanks
~~~~~~

A huge thanks to the Symfony community and all amazing free piece of software.

And thank you for using this.

----------------------------------------------

* `installation`_

* `basic usage`_

* `go further`_


.. _installation: index.rst
.. _basic usage: usage.rst
.. _go further: further.rst

.. _Github: https://github.com/maximilienGilet/notification-bundle
