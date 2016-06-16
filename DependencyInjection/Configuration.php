<?php

namespace Mgilet\NotificationBundle\DependencyInjection;

use AppBundle\Entity\User;
use AppBundle\Entity\Notification;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /** @var string */
    const USER_ENTITY = User::class;

    /** @var string */
    const NOTIFICATION_ENTITY = Notification::class;


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
