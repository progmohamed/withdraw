<?php

namespace WithdrawBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RelatedServicesPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container)
    {
        // get all dependencies services
        $collectorService = $container->findDefinition('withdraw.service');
        $sortedServices = $this->findAndSortTaggedServices('withdraw.service', $container);
        $sortedServices = array_reverse($sortedServices);

        // put all dependencies components in our withdraw service
        foreach ($sortedServices as $service) {
            $collectorService->addMethodCall('addRelatedService', [
                $service
            ]);
        }
    }
}