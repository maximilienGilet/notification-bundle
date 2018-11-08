<?php
/**
 * Project: notification-bundle
 * User: Leandro Luccerini <leandro.luccerini@gmail.com>
 * Date: 08/11/18
 * Time: 14.01
 */

namespace Mgilet\NotificationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Mgilet\NotificationBundle\Entity\NotificationInterface;
use Symfony\Component\Debug\Exception\ClassNotFoundException;
use Doctrine\ORM\Version;

class ResolveTargetEntitiesPass implements CompilerPassInterface
{

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        // Gets the notification entity defined by the user (or the default one)
        $notificationEntityClass = $container->getParameter('mgilet_notification.notification_class');
        // Skip the resolve_target_entities part if user's has not defined a different entity
        if (NotificationInterface::DEFAULT_NOTIFICATION_ENTITY_CLASS == $notificationEntityClass) {
            return;
        }
        // Throws exception if the class isn't found
        if (!class_exists($notificationEntityClass)) {
            throw new ClassNotFoundException(sprintf("Can't find class %s ", $notificationEntityClass));
        }

        // Get the doctrine ResolveTargetEntityListener
        $def = $container->findDefinition('doctrine.orm.listeners.resolve_target_entity');
        // Adds the resolve_target_enitity parameter
        $def->addMethodCall('addResolveTargetEntity', array(
            NotificationInterface::DEFAULT_NOTIFICATION_ENTITY_CLASS, $notificationEntityClass, array()
        ));
        // This was added due this problem
        // https://stackoverflow.com/a/46656413/7070573
        if (version_compare(Version::VERSION, '2.5.0-DEV') < 0 && !$def->hasTag('doctrine.event_listener')) {
            $def->addTag('doctrine.event_listener', array('event' => 'loadClassMetadata'));
        } elseif (!$def->hasTag('doctrine.event_subscriber')) {
            $def->addTag('doctrine.event_subscriber');
        }
    }
}