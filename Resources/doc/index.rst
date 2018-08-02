========================
MgiletNotificationBundle
========================
------------------------------------------------
A simple Symfony 2/3/4 bundle for user notifications
------------------------------------------------

Installation
============


Prerequisites
-------------

This version of the bundle requires Symfony 2.7+.

Warning : For now only Doctrine ORM is supported

Basic installation:
-------------------

Require the bundle with composer:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: bash

    $ composer require mgilet/notification-bundle
    


Symfony 2.x only :
~~~~~~~~~~~~~~~~~~

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

Configure notifiables classes:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The goal of this bundle is to make one or many entities ``notifiables``.

1. Use the ``@Notifiable`` annotation on your entity
2. Implement the ``Mgilet\NotificationBundle\NotifiableInterface`` interface (it's an empty interface)

And that's it !

Example:

.. code-block:: php

    <?php
    // src/AppBundle/Entity/MyEntity.php

    namespace AppBundle\Entity;

    ...
    use Mgilet\NotificationBundle\Annotation\Notifiable;
    use Mgilet\NotificationBundle\NotifiableInterface;

    /**
     * @ORM\Entity
     * @ORM\Table(name="my_entity")
     * @Notifiable(name="my_entity")
     */
    class MyEntity implements NotifiableInterface
    {
        ...


You can set as many entities ``notifiables`` as you want.
Entities with multiple identifiers are supported.

After this, you can send notifications to any ``notifiable`` entity.

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

In order to enable the controller, simply put this in your ``routing.yml`` (config/routes.yaml for Symfony 4) :

.. code-block:: yaml

    # routing.yml / routes.yaml

    mgilet_notification:
        resource: "@MgiletNotificationBundle/Controller/"
        prefix: /notifications


Translations (optionnal)
~~~~~~~~~~~~~~~~~~~~~~~~

If you wish to use default texts provided in this bundle, you have to make
sure you have translator enabled in your config.

.. code-block:: yaml

    # app/config/config.yml

    framework:
        translator: ~

For more information about translations, check `Symfony documentation`_.

Basic usage :
~~~~~~~~~~~~~

Go to `basic usage`_

----------------------------------------------

* `installation`_

* `basic usage`_

* `go further`_


.. _installation: index.rst
.. _basic usage: usage.rst
.. _go further: further.rst

.. _Symfony documentation: https://symfony.com/doc/current/book/translation.html
