========================
MgiletNotificationBundle
========================
------------------------------------------------
A simple Symfony 3 bundle for user notifications
------------------------------------------------

Installation
============


Prerequisites
-------------

This version of the bundle requires Symfony 2.7+. For better rendering Bootstrap 3 is recommended.

Warning : For now only Doctrine ORM is supported

Translations
~~~~~~~~~~~~

If you wish to use default texts provided in this bundle, you have to make
sure you have translator enabled in your config.

.. code-block:: yaml

    # app/config/config.yml

    framework:
        translator: ~

For more information about translations, check `Symfony documentation`_.

Basic installation:
-------------------

Require the bundle with composer:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: bash

    $ composer require maximilienGilet/notification-bundle

Composer will install the bundle to your project's ``vendor/mgilet/notification-bundle`` directory.

Then add the following line in the AppKernel.php:

.. code-block:: php

         <?php
         // app/AppKernel.php

         public function registerBundles()
         {
            $bundles = array(
                // ...
                new Mgilet\NotificationBundle\MgiletNotificationBundle(),
                // ...
            );
         }

Define User and Notification classes:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The goal of this bundle is to provide a ``Notification`` to a ``User``, so you need to define these classes.

The bundle provides base classes which are already mapped for most fields
to make it easier to create your entity. Here is how you use it:

1. Implement ``UserNotificationInterface`` interface (from the ``Model`` folder ) on your ``User`` entity
2. Map the ``notifications`` field (we will create it just after)
3. Implement ``UserNotificationInterface`` methods in your ``User`` class

Sample configuration:

.. code-block:: php

    <?php
    // src/AppBundle/Entity/User.php

    namespace AppBundle\Entity;

    ...
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\ORM\Mapping as ORM;
    use Mgilet\NotificationBundle\Model\UserNotificationInterface;

    /**
     * @ORM\Entity
     * @ORM\Table(name="user")
     */
    class User implements UserNotificationInterface
    {
        ...

        // link to notifications
        /**
         * @var Notification
         * @ORM\OneToMany(targetEntity="AppBundle\Entity\Notification", mappedBy="user", orphanRemoval=true)
         */
        protected $notifications;

        ...

        public function __construct()
        {
            ...
            $this->notifications = new ArrayCollection();
        }

        ...

        // method implementation for UserNotificationInterface

        /**
         * {@inheritdoc}
         */
        public function getNotifications()
        {
            return $this->notifications;
        }

        /**
         * {@inheritdoc}
         */
        public function addNotification($notification)
        {
            if (!$this->notifications->contains($notification)) {
                $this->notifications[] = $notification;
                $notification->setUser($this);
            }

            return $this;
        }

        /**
         * {@inheritdoc}
         */
        public function removeNotification($notification)
        {
            if ($this->notifications->contains($notification)) {
                $this->notifications->removeElement($notification);
            }

            return $this;
        }

        /**
         * {@inheritdoc}
         */
        public function getIdentifier()
        {
            $this->getId();
        }

    }

Now we need the Notification class.

Simply extend the provided MappedSuperClass ``AbstractNotification`` class (from the ``Model`` folder) and link it to the ``User`` entity.

Here is a sample configuration:

.. code-block:: php

    <?php

    // src/AppBundle/Entity/Notification.php

    namespace AppBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Mgilet\NotificationBundle\Model\AbstractNotification;

    /**
     * @ORM\Entity
     * @ORM\Table(name="notification")
     */
    class Notification extends AbstractNotification
    {
        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;

        /**
         * @var User
         * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="notifications")
         */
        protected $user;


        /**
         * @return int
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * @return User
         */
        public function getUser()
        {
            return $this->user;
        }

        /**
         * @param User $user
         * @return Notification
         */
        public function setUser($user)
        {
            $this->user = $user;
            $user->addNotification($this);

            return $this;
        }

    }


Update Doctrine
~~~~~~~~~~~~~~~

To finish the installation, don't forget to update your schema:

**Symfony 2.x**

.. code-block:: bash

    $ php app/console doctrine:schema:update --force

**Symfony 3.x**

.. code-block:: bash

    $ php bin/console doctrine:schema:update --force


Enable the Notification controller :
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

This bundle provides a controller named ``NotificationController``, which is used to do basic operations (mark as seen, display all...)

Note: this controller is required to use the default dropdown view.

In order to enable the controller, simply put this in your ``routing.yml`` :

.. code-block:: yaml

    # routing.yml

    mgilet_notifications:
        resource: "@MgiletNotificationBundle/Controller/"
        prefix: /notifications


Assets :
~~~~~~~~

By installing this bundle with composer, all assets will be copied. if it doesn't work, execute the following command:

**Symfony 2.x**

.. code-block:: bash

    $ php app/console assets:install

**Symfony 3.x**

.. code-block:: bash

    $ php bin/console assets:install


Class not located in AppBundle :
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If your ``Notification`` entity is not located in ``AppBundle`` or have different name than default, you must define it's path in your ``config`` file.

Example of configuration :

.. code-block:: yaml

    # config.yml

    mgilet_notification:
        notification_class: AnotherBundle\Entity\MyNotification # default value is AppBundle\Entity\Notification


Basic usage :
~~~~~~~~~~~~~

Go to `basic usage`_

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

.. _Symfony documentation: https://symfony.com/doc/current/book/translation.html
