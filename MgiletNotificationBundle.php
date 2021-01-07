<?php

namespace Mgilet\NotificationBundle;

use Mgilet\NotificationBundle\DependencyInjection\Compiler\ResolveTargetEntitiesPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;

/**
 * MgiletNotificationBundle
 *
 * A simple Symfony 3 bundle for user notifications
 *
 * @author    Maximilien Gilet <maximilien248@gmail.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      https://github.com/maximilienGilet/notification-bundle Repo for this bundle
 */
class MgiletNotificationBundle extends Bundle
{
    public function build(ContainerBuilder $container){
        parent::build($container);
        $container->addCompilerPass(new ResolveTargetEntitiesPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 1);
    }
}
