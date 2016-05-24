<?php

namespace Mgilet\NotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /** @var string */
    const USER_ENTITY = AppBundle\Entity\User::class;

    /** @var string */
    const NOTIFICATION_ENTITY = AppBundle\Entity\Notification::class;

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
            ->scalarNode('user_class')->defaultValue(self::USER_ENTITY)
            ->info('Entity for a user (default: AppBundle\\Entity\\User)')
            ->end();
        $rootNode
            ->children()
            ->scalarNode('notification_class')->defaultValue(self::NOTIFICATION_ENTITY)
            ->info('Entity for a notification (default: AppBundle\\Entity\\Notification)')
            ->end();


        return $treeBuilder;
    }
}