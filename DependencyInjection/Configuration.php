<?php

namespace Mgilet\NotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * Defining the DependencyInjection parameters for this bundle
 * @package Mgilet\NotificationBundle\DependencyInjection
 */
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
            ->scalarNode('notification_class')
            ->cannotBeEmpty()
            ->defaultValue('AppBundle\\Entity\\Notification')
            ->info('Entity for a notification (default: AppBundle\\Entity\\Notification)')
            ->end();

        return $treeBuilder;
    }
}
