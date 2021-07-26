<?php
/**
 * Project: notification-bundle
 * User: Leandro Luccerini <leandro.luccerini@gmail.com>
 * Date: 08/11/18
 * Time: 9.29
 */

namespace Mgilet\NotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Mgilet\NotificationBundle\Entity\NotificationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('mgilet_notification');

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            $rootNode = $treeBuilder->root('mgilet_notification');
        }

        $rootNode->children()
            ->scalarNode('notification_class')
                ->cannotBeEmpty()
                ->defaultValue(NotificationInterface::DEFAULT_NOTIFICATION_ENTITY_CLASS)
            ->end()
        ->end();

        return $treeBuilder;
    }
}