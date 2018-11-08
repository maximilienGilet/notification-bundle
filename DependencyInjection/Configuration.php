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


class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mgilet');

        $rootNode->children()
            ->arrayNode('notification')
                ->children()
                    ->scalarNode('notification_class')
                        ->isRequired()
                        ->cannotBeEmpty()
                        ->defaultValue('Mgilet\NotificationBundle\Entity\Notification')
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}