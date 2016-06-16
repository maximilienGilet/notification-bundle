<?php

namespace Mgilet\NotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        return $this->buildConfigTree();
    }

    private function buildConfigTree()
    {

        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mgilet_notification');
        $rootNode
            ->children()
            ->scalarNode('user_class')
            ->cannotBeEmpty()
            ->defaultValue(User::class)
            ->info('Entity for a user (default: AppBundle\\Entity\\User)')
            ->end();
        $rootNode
            ->children()
            ->scalarNode('notification_class')
            ->cannotBeEmpty()
            ->defaultValue(Notification::class)
            ->info('Entity for a notification (default: AppBundle\\Entity\\Notification)')
            ->end();


        return $treeBuilder;
    }
}
