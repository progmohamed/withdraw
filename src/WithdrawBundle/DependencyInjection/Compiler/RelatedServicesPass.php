<?php

namespace WithdrawBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\Reference;

class RelatedServicesPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container)
    {
        $collectorService = $container->findDefinition('withdraw.service');
        $sortedServices = $this->findAndSortTaggedServices('withdraw.service', $container);
        $sortedServices = array_reverse($sortedServices);

        foreach ($sortedServices as $service) {
            $collectorService->addMethodCall('addRelatedService', [
                $service
            ]);
        }
    }
}